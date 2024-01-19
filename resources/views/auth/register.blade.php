@extends('layouts.auth',['title' => 'Sign Up', 'active' => 'sign-up'])

@section('content')
<section class="section-signup">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="left-design">
                    <div><a href=""><img src="./assets/images/white-logo.png" alt="logo"></a></div>
                    <div class="form-design"><a href=""><img src="./assets/images/login-design.png" alt="logo" class="img-fluid"></a></div>
                    <div><p class="text-white">© 2022 HelloLaundry.Co.UK. All right reserved.</p></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="right-form">
                    <div class="text-end mb-96">
                        <a class="btn btn-brand outline py-12 px-4" href="https://book.hellolaundry.co.uk/">Place Order</a>
                        <a class="btn btn-brand  py-12 px-4 ms-4" href="{{env('APP_URL')}}/pricing">Prices & Services</a>
                    </div>
                    <div class="form-login-wrap">
                        <h1 class="text-center mb-4 fw-bolder">Create your account</h1>
                        <h5 class="text-center mb-30">To schedule a pickup for your laundry </h5>
                        <div class="text-center">
                            <a class="btn google-link" href="/login/oauth/google"><i class="icon icon-google me-2"></i> Sign up with Google</a>
                        </div>
                        <p class="text-center my-5 color-primary before"><b class="bg-white">OR</b></p>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
{{--                            <div class="mb-4">--}}
{{--                                <label for="customer_name" class="form-label mb-2">Full name</label>--}}
{{--                                <input id="customer_name" type="text" class="form-control @error('customer_name') is-invalid @enderror" name="customer_name" value="{{ old('customer_name') }}"  autocomplete="customer_name" autofocus>--}}

{{--                                @error('customer_name')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
                            <div class="mb-4">
                                <label for="email" class="form-label mb-2">Email address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
{{--                            <div class="mb-4">--}}
{{--                                <label for="password" class="form-label">Password</label>--}}
{{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">--}}

{{--                                @error('password')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
                            <button type="submit" class="btn btn-brand w-100 b-radius-16 py-12 px-4 mt-40">Create Account</button>
                            <p class="text-center mt-32">Already have an account?<a href="/login" class="color-brand text-decoration-none"> Log in</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
