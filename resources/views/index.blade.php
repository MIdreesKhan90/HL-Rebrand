@extends('layouts.app',['title' => 'Home','active' => 'home'])

@section('content')
    <!-- section hero -->
    <section class="section-hero bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-2 order-md-1">
                    <div class="content">
                        {!! $homepage->banner_heading !!}
                        <form class="form-wegit" action="#">
                            <input class="form-control" type="text" placeholder="Enter Postcode">
                            <input type="submit" value="Book Now" class="btn btn-brand py-12 px-4 py-md-4 px-md-5 ms-lg-4 d-inline-block">
                        </form>
                    </div>
                </div>
                <div class="col-md-6 order-1 order-md-2">
                    <img class="img-fluid" src="{{Storage::url($homepage->banner_image)}}" alt="hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- section ranking -->
    <section class="section-ranked bg-blue">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-auto has-arrow ">
                    <div class="me-md-5 me-0">
                        <h3 class="text-white fw-bold mb-2">{{$homepage->rank_heading}}</h3>
                        <p class="text-white">{{$homepage->rank_text_copy}}</p>
                    </div>
                </div>
                <div class="col-md-3 ranking-review">
                    <ul class="d-flex mb-2 ps-0 list-unstyled">
                        <li><i class="icon icon-star"></i></li>
                        <li><i class="icon icon-star"></i></li>
                        <li><i class="icon icon-star"></i></li>
                        <li><i class="icon icon-star"></i></li>
                        <li><i class="icon icon-star"></i></li>
                    </ul>
                    <a class="d-block text-white text-decoration-none fw-bold" href="{{$homepage->rank_review_link_url}}">{{$homepage->rank_review_link_label}}</a>
                </div>
            </div>
        </div>
    </section>

    <!-- section our facility widgets  -->
    <section class="section section-facility">
        <div class="container">
            <div class="row">
                @foreach($homepage->facilities as $facility)
                    <div class="col-lg-4">
                        <div class="box b-radius-16">
                            {{--                        <i class="mb-4 icon icon-24th"></i>--}}
                            <img class="mb-4 icon" src="{{Storage::url($facility->icon)}}" alt="{{$facility->text}}">
                            <h4 class="fw-600">{!! nl2br($facility->text) !!}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- section about -->
    <section class="section section-laundry-works">
        <div class="container">
            <h1 class="text-left text-md-center fw-bolder mb-96 has-right-icon">{{$homepage->how_we_work_heading}}
                <i class="icon icon-heading"></i></h1>
            @foreach($homepage->processes as $process)
                @if($process->type == 'textIcon')
                    <div class="row mb-78 @if($loop->odd) reverse-column @endif">
                        @if($loop->even)
                            <div class="col-lg-6 mb-3 mb-0">
                                <img class="img-fluid" src="{{Storage::url($process->image)}}" alt="{{$process->heading}}">
                            </div>
                        @endif
                        <div class="col-lg-6 m-auto">
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

{{--            <div class="row mb-78">--}}
{{--                <div class="col-lg-6  mb-4 mb-0">--}}
{{--                    <img class="img-fluid" src="./assets/images/about-image-02.png" alt="about-image">--}}
{{--                </div>--}}
{{--                <div class="col-lg-6 m-auto">--}}
{{--                    <h2 class="fw-500 mb-3">Pack your laundry</h2>--}}
{{--                    <p class="fs-5 mb-20">Pack your laundry items in a disposable bag.</p>--}}
{{--                    <ul>--}}
{{--                        <li class="fw-500 fs-5">Use one disposable bag per service <br> (Laundry, Dry cleaning, Ironing).</li>--}}
{{--                        <li class="fw-500 fs-5">Counting or weighing your laundry is not necessary.</li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row mb-78 reverse-column">--}}
{{--                <div class="col-lg-6 m-auto">--}}
{{--                    <h2 class="fw-500 mb-3">Driver will collect the laundry</h2>--}}
{{--                    <p class="fs-5 mb-20">Our driver will collect the laundry and drop them to our nearby cleaning facility.</p>--}}
{{--                    <ul>--}}
{{--                        <li class="fw-500 fs-5">Once the driver is near you, you will be notified.</li>--}}
{{--                        <li class="fw-500 fs-5">Live order tracking.</li>--}}
{{--                        <li class="fw-500 fs-5">Regular order updates.</li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--                <div class="col-lg-6  mb-4 mb-0">--}}
{{--                    <img class="img-fluid" src="./assets/images/about-image-03.png" alt="about-image">--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row">--}}
{{--                <div class="col-lg-6  mb-4 mb-0">--}}
{{--                    <img class="img-fluid" src="./assets/images/about-image-04.png" alt="about-image">--}}
{{--                </div>--}}
{{--                <div class="col-lg-6 m-auto">--}}
{{--                    <h2 class="fw-500 mb-3">Cleaners will do the job</h2>--}}
{{--                    <p class="fs-5 mb-20">Our cleaner will handle your laundry items with care and deliver them back to you.</p>--}}
{{--                    <ul>--}}
{{--                        <li class="fw-500 fs-5 mb-2">24 hours turnaround time.</li>--}}
{{--                        <li class="fw-500 fs-5 mb-2">Rescheduling is possible.</li>--}}
{{--                    </ul>--}}
{{--                    <a class="btn btn-brand  py-3 px-32 d-inline-block mt-40" href="#">Schedule your pickup</a>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </section>

    <!-- section services -->
    <section class="section section-facility bg-light">
        <div class="container">
            <h1 class="fw-bolder mb-32">{{$homepage->services_heading}}<i class="icon icon-heading"></i></h1>
            <div class="row">
                @foreach($homepage->services as $service)
                    <div class="col-lg-4">
                        <div class="box b-radius-16 bg-white">
                            <img class="mb-30 icon" src="{{Storage::url($service->icon)}}" alt="{{$service->service_title}}">
                            <h4 class="mb-3">{{$service->service_title}}</h4>
                            <p class="mb-4">{{$service->service_description}}</p>
                            <h6>{!! $service->service_cost !!}</h6>
                        </div>
                    </div>
                @endforeach

{{--                <div class="col-lg-4 ">--}}
{{--                    <div class="box b-radius-16 bg-white">--}}
{{--                        <i class="mb-30 icon icon-cleaning-service"></i>--}}
{{--                        <h4 class="mb-3">Dry Cleaning Services</h4>--}}
{{--                        <p class="mb-4">London's best dry cleaning service returns clothes just like new, in affordable rates.</p>--}}
{{--                        <h6>From <span class="fw-bold color-brand"> £2.50</span> per item</h6>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-lg-4">--}}
{{--                    <div class="box b-radius-16 bg-white">--}}
{{--                        <i class="mb-30 icon icon-ironing-service"></i>--}}
{{--                        <h4 class="mb-3">Ironing Service</h4>--}}
{{--                        <p class="mb-4">Our steam ironing service by industry experts, makes your clothes look crisp and fresh.</p>--}}
{{--                        <h6>From <span class="fw-bold color-brand"> £1.75</span> per item</h6>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
        <div class="text-center">
            @if($homepage->services_link_url)
            <a class="btn btn-brand  py-3 px-32 d-inline-block mb-4 mt-40" href="{{$homepage->services_link_url}}">{{$homepage->services_link_label}}</a>
            @endif
            <p class="text-center fw-600">{{$homepage->minimum_order_text}}</p>
        </div>
    </section>

    <!-- customer review -->
    <section class="section section-reviews">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto">
                    <h1 class="mb-1 fw-bolder">Our happy customers <i class="icon icon-heading"></i></h1>
                    <p class="pb-11">Check out what our happy customers say about their experience <br> with Hello Laundry.</p>
                    <div class="d-flex mb-32">
                        <p class="pr-12">Rated Excellent by 3,000+ users</p>
                        <span><i class="icon icon-star"></i></span>
                        <span><i class="icon icon-star"></i></span>
                        <span><i class="icon icon-star"></i></span>
                        <span><i class="icon icon-star"></i></span>
                        <span><i class="icon icon-star"></i></span>
                    </div>
                    <a class="btn btn-brand  py-3 px-32 d-inline-block" href="#">See prices and services</a>
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


    <!-- section blog -->
    <section class="section section-blog">
        <div class="container">
            <h1 class="fw-bolder mb-32">Our Services <i class="icon icon-heading"></i></h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="card b-radius-16 bg-white">
                        <img src="./assets/images/blog-1.jpg" class="card-img-top b-radius-16" alt="...">
                        <div class="card-body p-32">
                            <p class="desc color-light mb-1">June 28, 2022</p>
                            <h5 class="card-title fw-500 mb-3">Are Wet Dry Cleaning Services Good For Health?</h5>
                            <p class="card-text"> Dry cleaning your clothes has a lot of health
                                benefits that a regular washing machine ...</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card b-radius-16 bg-white">
                        <img src="./assets/images/blog-2.jpg" class="card-img-top b-radius-16" alt="...">
                        <div class="card-body p-32">
                            <p class="desc color-light mb-1">June 23, 2022</p>
                            <h5 class="card-title fw-500 mb-3">Hello to Sunshine and Summer Fun</h5>
                            <p class="card-text">They Say “Summer means happy times and good sunshine” and we agree with that … </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card b-radius-16 bg-white">
                        <img src="./assets/images/blog-3.jpg" class="card-img-top b-radius-16" alt="...">
                        <div class="card-body p-32">
                            <p class="desc color-light mb-1">June 18, 2022</p>
                            <h5 class="card-title fw-500 mb-3">The Complete Flannel Shirt Pressing Guide</h5>
                            <p class="card-text"> Flannel shirts don’t wrinkle easily, but you might want to keep them looking crisp for special ...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
