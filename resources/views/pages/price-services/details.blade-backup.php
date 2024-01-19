@extends('layouts.app',['title' => $service->title, 'active' => 'prices'])

@section('content')
    @php
        $sub_active = request()->segment(2);
    @endphp
    <section class="section-categories bg-boll">
        <div class="container">
            <div class="row">
                <ul class="filter-by-category d-flex justify-content-around mb-0 pt-16 pb-16">
                    @foreach($services as $service)
                        <li><a href="{{ route('pricing.details', ['service' => $service->slug])}}" class="{{ $sub_active == $service->slug ? 'active' : '' }}">{{$service->title}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
    <!-- section our prices  -->
    <section class="section-category-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-content d-md-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="icon icon-wash"></i>
                        </div>
                        <div class="flex-grow-1 ps-md-4">
                            <h1 class="mb-md-3 mb-2 fw-bolder">{{ $service->title }}</h1>
                            <p class="mb-md-3 mb-2">{{ $service->description }}</p>
                            <p class="color-primary fw-lighter">@foreach($service->tags as $tag)
                                    {{$tag->title}} @if(!$loop->last) + @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section section-tabs">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    @foreach($service->price_details as $priceDetail)
                        @if($priceDetail->name == 'repeatable')
                    <ul class="filter-by-category nav">
                        <li class="nav-item"><a class="nav-link link-secondary" id="Home-Bedding-tab" data-bs-toggle="tab" data-bs-target="#Home-Bedding" href="#">Home & Bedding</a></li>
                        <li class="nav-item"><a class="nav-link link-secondary active" id="Bedding-tab" data-bs-toggle="tab" data-bs-target="#Bedding" href="#">Bedding</a></li>
                        <li class="nav-item"><a class="nav-link link-secondary " id="Fold-tab" data-bs-toggle="tab" data-bs-target="#Fold" href="#">Wash, Tumble Dry & Fold</a></li>
                    </ul>
                        @endif
                    @endforeach

                    <div class="main b-radius-16">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="table">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($service->price_details as $priceDetail)
                                            <tr>
                                                <td>{{$priceDetail->heading}}</td>
                                                <td>£{{$priceDetail->price}}</td>
                                                <td>
                                                    <div class="counter">
                                                        <div class="decrement-count">
                                                            <i class="fas fa-minus"></i>
                                                        </div>
                                                        <div class="total-count">00 </div>
                                                        <div class="increment-count">
                                                            <i class="fal fa-plus"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>£6.80</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4 p-0">
                                    <div class="aside">
                                        <h4 class="border-bottom">1 Service Selected: Shirts</h4>
                                        <div class="services-list border-bottom">
                                            <div class="d-flex justify-content-between">
                                                <p>Folded T-Shirt</p>
                                                <p>£2.80</p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p>Folded T-Shirt</p>
                                                <p>£2.80</p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p>Folded T-Shirt</p>
                                                <p>£2.80</p>
                                            </div>
                                        </div>
                                        <p class="border-bottom pb-3 pt-3">This is an estimated price, final price will be calculated after laundry job is done.</p>
                                        <div class="d-flex justify-content-between pt-3 pb-3 border-bottom">
                                            <p>Estimated price</p>
                                            <p>£62.39</p>
                                        </div>
                                        <div class="text-center mt-40">
                                            <a class="btn btn-brand  py-3 px-32 d-inline-block" href="#">Schedule an Order</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
{{--    <section class="section section-prices bg-light">--}}
{{--        <div class="container">--}}
{{--            <h2 class="mb-32 text-center fw-600">Up to 30% saving on prepaid packages</h2>--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-6 col-lg-4">--}}
{{--                    <div class="box b-radius-16 bg-white position-relative pb-60">--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <i class="icon icon-laundary-service"></i>--}}
{{--                            <div class="price-text">--}}
{{--                                <h3 class="fw-600">5 Loads</h3>--}}
{{--                                <h4 class="mt-2"><span class="fw-bold color-brand-orange fw-600">£15.80</span></h4>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="off-price">£16.95/item</div>--}}
{{--                        <a href="#" title="View offer" class="view-offer">View offer</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-6 col-lg-4">--}}
{{--                    <div class="box b-radius-16 bg-white position-relative pb-60">--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <i class="icon icon-laundary-service"></i>--}}
{{--                            <div class="price-text">--}}
{{--                                <h3 class="fw-600">10 Loads</h3>--}}
{{--                                <h4 class="mt-2"><span class="fw-bold color-brand-orange fw-600">£14.90</span></h4>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="off-price">£16.95/item</div>--}}
{{--                        <a href="#" title="View offer" class="view-offer">View offer</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-6 col-lg-4">--}}
{{--                    <div class="box b-radius-16 bg-white position-relative pb-60">--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <i class="icon icon-laundary-service"></i>--}}
{{--                            <div class="price-text">--}}
{{--                                <h3 class="fw-600">20 Loads</h3>--}}
{{--                                <h4 class="mt-2"><span class="fw-bold color-brand-orange fw-600">£13.95</span></h4>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="off-price">£16.95/item</div>--}}
{{--                        <a href="#" title="View offer" class="view-offer">View offer</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
    <section class="section section-prices ">
        <div class="container text-center">
            <h2 class="mb-32 text-center fw-600">{{$service->service_question}}</h2>
            <h3 class="fw-500">{{$service->service_answer}}</h3>
        </div>
    </section>
@endsection
