<?php

namespace App\Http\Controllers\API;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CustomerOrderVoucher;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\PromoCode;
use App\Models\PaymentMethod;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Validation
        $data = $request->all();

//         Handle Address
        $addressId = $this->handleAddress($data);

        if (!$addressId) {
            return response(['message' => 'Address processing failed'], 400);
        }

        $validatedData['address_id'] = $addressId;

//         Handle Promo Code
        if (isset($data['promo_code']) && $data['promo_code'] != ''){
            $data['promo_code'] = $this->handlePromoCode($data);
        }

        // Storing to session
        Session::put('order_details', $data);
        Session::put('order_items', $data['selectedServices']);

        return 1;
    }

    public function verifyVoucher(Request $request)
    {
        $input = $request->all();
        $valid_promo = PromoCode::where('promo_code',$input['promo_code'])->first();
        $data =[];
        if(isset($valid_promo )){
            $valid_promo_for_user = CustomerOrderVoucher::where('promo_id',$input['promo_code'])->where('customer_id',$input['customer_id'])->first();
            if(isset($valid_promo_for_user)){
                $data['message'] = "You have alredy used this Voucher code";
                $data['status'] = "1";
            }else{
                $data['message'] = $valid_promo['description'];
                $data['status'] = "2";
            }
        }else{
            $data['message'] = "Invalid Voucher code";
            $data['status'] = "0";
        }
        return json_encode($data);
    }

    private function handleAddress($data)
    {
        if (!isset($data['address_id'])) {
            $address = Address::firstOrNew([
                'city' => $data['city'],
                'country' => $data['country'] ?? '',
                'customer_id' => $data['customer_id'],
                'address' => $data['address'] ?? '',
                'status' => 1,
                'type' => $data['type']
            ]);

            if (!$address->exists) {
                $address->fill([
                    'manual_address' => $data['manual_address'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'country' => $data['country'] ?? '',
                    'customer_id' => $data['customer_id'],
                    'unique_id' => $data['postcode'],
                    'status' => 1,
                    'type' => $data['type']
                ])->save();

                return $address->id;
            }

            return $address->id;
        }

        return $data['address_id'];
    }

    private function handlePromoCode($data)
    {
        $promo = PromoCode::where('promo_code', $data['promo_code'])->first();

        if ($promo) {
            $isUsedByUser = CustomerOrderVoucher::where([
                'promo_id' => $data['promo_code'],
                'customer_id' => $data['customer_id']
            ])->exists();

            if (!$isUsedByUser) {
                return $promo->promo_code;
            }
        }

        return '';
    }

    public function stripePayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect('login')->with('error', 'You need to be logged in to perform this action.');
        }

        $uniqueid = Str::random(9);
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $customer = \Stripe\Customer::create();
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'metadata' => [
                'stripe_response_id' => $uniqueid,
                'payment_via' => 'hello_laundry'
            ],
            'line_items' => [
                [
                    'price' => $this->getStripePrice(),
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => config('app.url') . "/thankyou?token={CHECKOUT_SESSION_ID}",
            'cancel_url' => config('app.url') . "/payment-failure",
            'payment_intent_data' => ['setup_future_usage' => 'off_session'],
            'customer' => $customer->id
        ]);
        return redirect($checkout_session->url);
    }

    private function getStripePrice()
    {
        return app()->environment('production') ? 'price_1MHmRfCbow3Ummd6zeOfbBXD' : 'price_1J6fWICbow3Ummd699fqMv54';
    }

    public function payment_success(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($request->token);
            $stripe_response_id = $session->metadata['stripe_response_id'];

            if (!Order::where('stripe_response_id', $stripe_response_id)->exists()) {
                $this->createNewOrder($stripe_response_id);
            }

        } catch (\Exception $e) {
            \Log::error("Order creation failed: " . $e->getMessage());
            // Here, you might want to handle the error, like redirecting to a failure page or showing an error message
            return redirect('payment-failure');
        }

        return redirect('thank-you');
    }

    private function createNewOrder($stripe_response_id)
    {
        DB::transaction(function () use ($stripe_response_id) {
            $order_input = Session::get('order_details', []);
            $items = Session::get('order_items', []);
            $order_input['stripe_response_id'] = $stripe_response_id;
            $order_input['order_type'] = '1';

            $order = Order::create($order_input);
            $this->assignOrderId($order);
            $this->storeOrderServices($order, $items);
            $this->storeOrderPromo($order);

            // Additional operations
            $this->order_registers($order->id);
            $this->order_admin_registers($order->id);

            Session::forget(['order_details', 'order_items']);
        });
    }

    private function assignOrderId($order)
    {
        $order_id = str_pad($order->id, 5, "0", STR_PAD_LEFT);
        $order->update(['order_id' => $order_id]);
    }

    private function storeOrderServices($order, $items)
    {
        foreach ($items as $val) {
            if ($val) {
                $data = [
                    'order_id' => $order->id,
                    'service_id' =>  $val['cat'],
                    'category_id' =>  $val['sub_cat'],
                ];
                OrderService::create($data);
            }
        }
    }

    private function storeOrderPromo($order)
    {
        $promo_code = Session::get('order_details')['promo_code'];
        $customer_id = Session::get('order_details')['customer_id'];
        CustomerOrderVoucher::create([
            'promo_id' => $promo_code,
            'customer_id' => $customer_id,
            'order_id' => $order->id
        ]);
    }

    public function order_registers($id){

        $data = array();
        $orders = Order::where('id',$id)->first();
        $order_data = OrderService::where('order_id',$orders->id)->get();
        $customer = Customer::where('id',$orders->customer_id)->first();
        $currency = AppSetting::where('id',1)->value('default_currency');

        $items_data = array();
        $i=0;
        foreach ($order_data as $key => $value) {
            $service = Service::where('id',$value->service_id)->value('service_name');
            $category = category::where('id',$value->category_id)->value('category_name');
            $items_data[$i]['service_name'] = $service;
            $items_data[$i]['category_name'] = $category;
            $i++;
        }
        // $items = json_encode($items_data, TRUE);
        // print($items); exit;
        $data['order_id'] = $orders->order_id;
        $data['delivery_date'] = date('d M-Y',strtotime($orders->delivery_date));
        $data['delivery_time'] = $orders->delivery_time;
        $data['pickup_date'] = date('d M-Y',strtotime($orders->pickup_date));
        $data['pickup_time'] = $orders->pickup_time;
        $data['delivery_address'] = Address::where('id',$orders->address_id)->value('address');
        $data['postcode'] = Address::where('id',$orders->address_id)->value('postcode');
        $data['items'] = $items_data;
        $data['payment_mode'] = PaymentMethod::where('id',$orders->payment_mode)->value('payment_mode');
        $data['currency'] = $currency;
        // print(json_encode($data, TRUE)); exit;

        $mail_header = array("data" => $data);
        $this->order_register($mail_header,'Order Placed Successfully',$customer->email);
    }

    public function order_admin_registers($id){

        $data = array();
        $orders = Order::where('id',$id)->first();
        $order_data = OrderService::where('order_id',$orders->id)->get();
        $customer = Customer::where('id',$orders->customer_id)->first();
        $currency = AppSetting::where('id',1)->value('default_currency');
        $admin_email = AppSetting::where('id',1)->value('email');

        $items_data = array();
        $i=0;
        foreach ($order_data as $key => $value) {
            $service = Service::where('id',$value->service_id)->value('service_name');
            $category = category::where('id',$value->category_id)->value('category_name');
            $items_data[$i]['service_name'] = $service;
            $items_data[$i]['category_name'] = $category;
            $i++;
        }
        // $items = json_encode($items_data, TRUE);
        // print($items); exit;
        $data['order_id'] = $orders->order_id;
        $data['customer_name'] = $customer->uFName;
        $data['customer_phone'] = $customer->phone_number;

        $data['delivery_date'] = date('d M-Y',strtotime($orders->delivery_date));
        $data['delivery_time'] = $orders->delivery_time;
        $data['pickup_date'] = date('d M-Y',strtotime($orders->pickup_date));
        $data['pickup_time'] = $orders->pickup_time;
        $data['delivery_address'] = Address::where('id',$orders->address_id)->value('address');
        $data['postcode'] = Address::where('id',$orders->address_id)->value('postcode');
        $data['items'] = $items_data;
        $data['payment_mode'] = PaymentMethod::where('id',$orders->payment_mode)->value('payment_mode');
        $data['currency'] = $currency;
        // print(json_encode($data, TRUE)); exit;

        $mail_header = array("data" => $data);
        $this->order_admin_register($mail_header,'New Order Received',$admin_email);
    }

    public function order_register($mail_header,$subject,$to_mail){
        Mail::send('mail_templates.order_template', $mail_header, function ($message)
        use ($subject,$to_mail) {
            $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
            $message->subject($subject);
            $message->to($to_mail);
        });
    }

    public function order_admin_register($mail_header,$subject,$to_mail){
        Mail::send('mail_templates.order_admin_template', $mail_header, function ($message)
        use ($subject,$to_mail) {
            $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
            $message->subject($subject);
            $message->to($to_mail);
        });
    }

}
