@extends('layouts.app',['title' => 'Commercial Page', 'active' => 'commercial'])

@section('content')
    <!-- section hero -->
    <section class="section-hero bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-2 order-md-1">
                    <div class="content">
                        {!! $commercial_page->banner_heading !!}

                        <div class="d-flex">
                            <a href="#" class="btn btn-brand outline py-3 px-32 d-inline-block mt-40 mr-5">Get a custom quote</a>
                            <a href="#" class="btn btn-brand  py-3 px-32 d-inline-block mt-40">Schedule your pickup</a>
                        </div>

                    </div>
                </div>
                <div class="col-md-6 order-1 order-md-2">
                    <img class="img-fluid" src="{{Storage::url($commercial_page->banner_image)}}" alt="hero-image">
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
                        <h3 class="text-white fw-bold mb-2">{{$commercial_page->rank_heading}}</h3>
                        <p class="text-white">{{$commercial_page->rank_text_copy}}</p>
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
                    <a class="d-block text-white text-decoration-none fw-bold" href="{{$commercial_page->rank_review_link_url}}">{{$commercial_page->rank_review_link_label}}</a>
                </div>
            </div>
        </div>
    </section>


    <!-- section about -->
    <section class="section section-laundry-works">
        <div class="container">
            <h1 class="text-left text-md-center fw-bolder mb-96 has-right-icon">{{$commercial_page->services_heading}}
                <i class="icon icon-heading"></i></h1>
            @foreach($commercial_page->services as $service)
                    <div class="row mb-78 @if($loop->odd) reverse-column @endif">
                        @if($loop->even)
                            <div class="col-lg-6 mb-3 mb-0">
                                <img class="img-fluid" src="{{Storage::url($service->image)}}" alt="{{$service->heading}}">
                            </div>
                        @endif
                        <div class="col-lg-6 m-auto">
                            <h2 class="fw-500 mb-3">{{$service->heading}}</h2>
                            {!! $service->text !!}
                        </div>
                        @if($loop->odd)
                            <div class="col-lg-6 mb-3 mb-0">
                                <img class="img-fluid" src="{{Storage::url($service->image)}}" alt="{{$service->heading}}">
                            </div>
                        @endif
                    </div>
            @endforeach
        </div>
    </section>
@endsection
