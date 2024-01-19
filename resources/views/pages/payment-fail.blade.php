@extends('layouts.app',['title' => 'Privacy Policy', 'active' => 'privacy-policy'])

@section('content')
    <!-- section our prices  -->
    <section class="thanks-content bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">


                    <div class="thanks-inner bg-white ">
                        <img src="assets/images/thanks-page-img.svg" alt="" class="mb-5 ">

                        <h3 class="mb-4 text-danger">Payment Failed!</h3>
                        <p>Sorry for the inconvenience. Please try again.</p>
                    </div>

                    <a class="btn btn-brand  py-3 px-45 d-table ml-auto mt-40" href="/prices">Continue</a>

                </div>

            </div>
        </div>
    </section>
@endsection
