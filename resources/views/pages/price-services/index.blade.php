@extends('layouts.app',['title' => 'Price & Services', 'active' => 'prices'])

@section('content')
    <!-- section our prices  -->
    <section class="section-pricing">
        <h1 class="text-center bg-light fw-bolder">Simple, Affordable Pricing</h1>
        <div class="container section p-32 -mt-90 b-radius-16 bg-white">
                @foreach($services as $service)
                <div class="row pricing-list px-32 py-4 mb-32">
                    <div class="col-md-8">
                        <div class="list-details d-md-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img style="width: 80%" src="{{ asset('storage/' . $service->icon) }}" alt="{{$service->title}}">
                            </div>
                            <div class="flex-grow-1 ps-md-4">
                                <h3 class="mb-md-3 mb-2 fw-600">{{$service->title}}</h3>
                                <p class="mb-md-3 mb-2">{{$service->description}}</p>
                                <p class="color-primary fw-600">
                                    @foreach($service->tags as $tag)
                                        {{$tag->title}} @if(!$loop->last) + @endif
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="list-details d-md-flex align-items-center justify-content-between">
                            <div class="">
                                <p class="grey-color">{{$service->price_heading}}</p>
                                {!! $service->price_per_item !!}
                            </div>
                            <div class="">
                                <a class="btn btn-brand  py-3 px-32 ms-md-4" href="{{ route('pricing.details', ['service' => $service->slug])}}">+ Add</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
        </div>
    </section>
@endsection
