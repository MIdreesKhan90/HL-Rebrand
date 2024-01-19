@extends('layouts.app',['title' => 'Privacy Policy', 'active' => 'privacy-policy'])

@section('content')
    <!-- section our prices  -->
    <section class="section-pricing">
        <h1 class="text-center bg-light fw-bolder">{{$privacyPolicy->title}}
        </h1>
        <div class="container section p-32 -mt-90 b-radius-16 bg-white">
            <div class="row privacy-policy-content    mb-32">
                <div class="col-md-12">
                    {!! $privacyPolicy->description !!}
                </div>
            </div>
        </div>
    </section>
@endsection
