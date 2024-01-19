<?php

namespace App\Providers;

use App\Nova\AppSetting;
use App\Nova\BannerImage;
use App\Nova\BlogCategory;
use App\Nova\Country;
use App\Nova\PostCode;
use App\Nova\ZoneLocation;
use App\Nova\Customer;
use App\Nova\Dashboards\Main;
use App\Nova\DeliveryBoy;
use App\Nova\Faq;
use App\Nova\HomePage;
use App\Nova\ItemType;
use App\Nova\Label;
use App\Nova\Order;
use App\Nova\PaymentMethod;
use App\Nova\PrivacyPolicy;
use App\Nova\PromoCode;
use App\Nova\TodayTasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $this->getCustomMenu();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function getCustomMenu()
    {
        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),
                MenuSection::resource(TodayTasks::class)->icon('clipboard-check'),
                MenuSection::resource(Order::class)->icon('shopping-bag'),
                MenuSection::make('Hotel Vendors', [
                    MenuItem::make('Vendors Management', 'resources/vendors-managements'),
                    MenuItem::make('Vendor Orders', 'resources/vendor-orders'),
                ])->icon('library')->collapsable()->collapsedByDefault(),
                MenuSection::make('Facility Center', [
                    MenuItem::make('Center Management', 'resources/facility-centers'),
                    MenuItem::make('Services', 'resources/facility-services'),
                    MenuItem::make('Sub Services', 'resources/facility-categories'),
                    MenuItem::make('Facility Price Management', 'resources/facility-price-managements'),
                    MenuItem::make('Orders', 'resources/facility-orders'),
                ])->icon('library')->collapsable()->collapsedByDefault(),
                MenuSection::make('Items Management')->path('resources/items')->icon('plus'),
                MenuSection::resource(ItemType::class)->icon('plus'),
                MenuSection::make('Service Fee Management')->path('resources/fare-managements')->icon('currency-pound'),
                MenuSection::resource(Customer::class)->icon('users'),
                MenuSection::resource(DeliveryBoy::class)->icon('truck'),
                MenuSection::resource(PromoCode::class)->icon('ticket'),
                MenuSection::resource(Country::class)->icon('globe'),
                MenuSection::make('Zone Management', [
                    MenuItem::resource(ZoneLocation::class),
                    MenuItem::make('Zone', 'resources/zones'),
                    MenuItem::resource(PostCode::class),
                    MenuItem::make('Blocked Post Codes')->path('resources/blocked-post-codes'),
                    MenuItem::make('Holiday')->path('resources/holidays'),
                ])->icon('location-marker')->collapsable()->collapsedByDefault(),
                MenuSection::resource(PaymentMethod::class)->icon('credit-card'),
                MenuSection::resource(Label::class)->icon('pencil'),
                MenuSection::resource(Faq::class)->icon('information-circle'),
                MenuSection::make('Privacy Policy')->path('/resources/privacy-policies/1/edit')->icon('document-text'),
                MenuSection::resource(AppSetting::class)->icon('cog'),
                MenuSection::resource(BannerImage::class)->icon('photograph'),
                MenuSection::make('Services', [
                    MenuItem::make('Service', 'resources/services'),
                    MenuItem::make('Category', 'resources/categories'),
                ])->icon('view-list')->collapsable()->collapsedByDefault(),
                MenuSection::make('CMS', [
                    MenuItem::make('Home Page','/resources/home-pages/1/edit'),
                    MenuItem::make('Commercial Page','/resources/commercials/1/edit'),
                    MenuItem::make('Hotel Page','/resources/hotel-pages/1/edit'),
                    MenuItem::make('Blogs','/resources/blogs'),
                    MenuItem::make('Price & Services','/resources/price-services'),
                    MenuItem::resource(BlogCategory::class),

                ])->icon('view-list')->collapsable()->collapsedByDefault(),
                MenuSection::make('Admin', [
                    MenuItem::make('User', 'resources/users'),
                ])->icon('user')->collapsable()->collapsedByDefault(),
            ];
        });
    }
}
