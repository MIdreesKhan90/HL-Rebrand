@extends('layouts.app',['title' => 'Hotels', 'active' => 'hotels'])

@section('content')
    <!-- section hero -->
    <section class="section-hero bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-2 order-md-1">
                    <div class="content">
                        {!! $hotel_page->banner_heading !!}

                        <form class="form-search-hotel" action="#">
                            <i class="fa fal fa-search"></i>
                            <input class="form-control" type="text" placeholder="Find your hotel">
                            <input type="submit" value="Show hotels near me" class="d-inline-block">
                        </form>
                    </div>
                </div>
                <div class="col-md-6 order-1 order-md-2">
                    <img class="img-fluid" src="{{Storage::url($hotel_page->banner_image)}}" alt="hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- section ranking -->
    <section class="section-ranked bg-blue">
        <div class="container">
            <div class="row justify-content-center">
                <div class="has-arrow ">
                    <div class="text-white me-md-5 me-0">
                        {!! $hotel_page->text_copy !!}

                    </div>
                </div>

            </div>
        </div>
    </section>



    <!-- section about -->
    <section class="section section-laundry-works">
        <div class="container">
            <h1 class="text-left text-md-center fw-bolder mb-96 has-right-icon">{{$hotel_page->how_we_work_heading}}
                <i class="icon icon-heading"></i></h1>
            @foreach($hotel_page->processes as $process)
                @if($process->type == 'textIcon')
                    <div class="row mb-78 @if($loop->odd) reverse-column @endif">
                        @if($loop->even)
                            <div class="col-lg-6 mb-3 mb-0">
                                <img class="img-fluid" src="{{Storage::url($process->image)}}" alt="{{$process->heading}}">
                            </div>
                        @endif
                        <div class="col-lg-6 m-auto">
                            <span class="our-service-subtitle">{{$process->subtitle}}</span>
                            <h2 class="fw-500 mb-3">{{$process->heading}}</h2>
                            {!! $process->text !!}
                        </div>
                        @if($loop->odd)
                            <div class="col-lg-6 mb-3 mb-0">
                                <img class="img-fluid" src="{{Storage::url($process->image)}}" alt="{{$process->heading}}">
                            </div>
                        @endif
                    </div>
                @else
                    <div class="row">
                        @if($loop->even)
                            <div class="col-lg-6 mb-3 mb-0">
                                <img class="img-fluid" src="{{Storage::url($process->image)}}" alt="{{$process->heading}}">
                            </div>
                        @endif
                        <div class="col-lg-6 m-auto">
                            <span class="our-service-subtitle">{{$process->subtitle}}</span>
                            <h2 class="fw-500 mb-3">{{$process->heading}}</h2>
                            {!! $process->text !!}
                            @if($process->link_url)
                                <a class="btn btn-brand  py-3 px-32 d-inline-block mt-40" href="{{$process->link_url}}">{{$process->link_label}}</a>
                            @endif
                        </div>
                        @if($loop->odd)
                            <div class="col-lg-6 mb-3 mb-0">
                                <img class="img-fluid" src="{{Storage::url($process->image)}}" alt="{{$process->heading}}">
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </section>


    <!-- section services -->
    <section class="section section-facility bg-dark-light">
        <div class="container">
            <h1 class="fw-bolder mb-32">{{$hotel_page->services_heading}}<i class="icon icon-heading"></i></h1>
            <div class="row">
                @foreach($hotel_page->services as $service)
                    <div class="col-lg-4">
                        <div class="box b-radius-16 bg-white">
                            <img class="mb-30 icon" src="{{Storage::url($service->icon)}}" alt="{{$service->service_title}}">
                            <h4 class="mb-3">{{$service->service_title}}</h4>
                            <p class="mb-4">{{$service->service_description}}</p>
                            <h6>{!! $service->service_cost !!}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-center">
            @if($hotel_page->services_link_url)
                <a class="btn btn-brand  py-3 px-32 d-inline-block mb-4 mt-40" href="{{$hotel_page->services_link_url}}">{{$hotel_page->services_link_label}}</a>
            @endif
            <p class="text-center fw-600">{{$hotel_page->minimum_order_text}}</p>
        </div>
    </section>

    <!-- customer review -->
    <section class="section section-reviews">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto">
                    <h1 class="mb-1 fw-500">Reviews by our hotel customers
                        <i class="icon icon-heading"></i></h1>

                    <div class="d-flex mb-32">
                        <p class="pr-12">Rated Excellent by 3,000+ users</p>
                        <span><i class="icon icon-star"></i></span>
                        <span><i class="icon icon-star"></i></span>
                        <span><i class="icon icon-star"></i></span>
                        <span><i class="icon icon-star"></i></span>
                        <span><i class="icon icon-star"></i></span>
                    </div>
                    <div>
                        <a class="btn btn-brand outline py-12 px-4 " href="#">Read more reviews</a>
                        <a class="btn btn-brand  py-12 px-4 ms-4" href="#">Schedule your pickup</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="reviews bg-white b-radius-16 p-4 mt-md-0 mt-3">
                        <div class="d-flex flex-column flex-md-row align-items-center mb-30">
                            <div class="me-3">
                                <img src="./assets/images/profile-image.png" alt="profile-image">
                            </div>
                            <div class="">
                                <img class="mb-2" src="./assets/images/coma-image.png" alt="coma-image">
                                <p>Love this company. They're so efficient, friendly and reasonably priced for the convenience they bring my life. I highly recommend them!</p>
                                <div class="d-flex">
                                    <p>George F.</p>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row align-items-center mb-30">
                            <div class="me-3">
                                <img src="./assets/images/profile-image.png" alt="profile-image">
                            </div>
                            <div class="">
                                <img class="mb-2" src="./assets/images/coma-image.png" alt="coma-image">
                                <p>Love this company. They're so efficient, friendly and reasonably priced for the convenience they bring my life. I highly recommend them!</p>
                                <div class="d-flex">
                                    <p>George F.</p>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row align-items-center mb-30">
                            <div class="me-3">
                                <img src="./assets/images/profile-image.png" alt="profile-image">
                            </div>
                            <div class="">
                                <img class="mb-2" src="./assets/images/coma-image.png" alt="coma-image">
                                <p>Love this company. They're so efficient, friendly and reasonably priced for the convenience they bring my life. I highly recommend them!</p>
                                <div class="d-flex">
                                    <p>George F.</p>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                    <span><i class="icon icon-star"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="faq-accordian help-advice">


        <div class="container">
            <h2>{{$hotel_page->faq_heading}}</h2>
            <div class="bg-white">  <!-- Accordion -->
                <div id="accordionExample" class="accordion  ">

                    @foreach($hotel_page->faqs as $faq )
                        <div class="card">
                            <div id="heading{{$loop->index}}" class="card-header border-0">
                                <h2 class="mb-0">
                                    <button type="button" data-toggle="collapse" data-target="#collapse{{$loop->index}}" aria-expanded="true"
                                            aria-controls="collapse{{$loop->index}}"
                                            class="btn btn-link text-dark font-weight-bold text-uppercase collapsible-link">{{$faq->question}}</button>
                                </h2>
                            </div>
                            <div id="collapse{{$loop->index}}" aria-labelledby="heading{{$loop->index}}" data-parent="#accordionExample" class="collapse @if($loop->first) show @endif">
                                <div class="card-body">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- End -->
            </div>

            <a href="{{$hotel_page->faqs_link_url}}" class="visit-help-center" title="Visit our help center">{{$hotel_page->faqs_link_label}}</a>
        </div>
    </section>
@endsection
