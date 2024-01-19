@extends('layouts.auth',['title' => 'Reset Password', 'active' => 'reset'])

@section('content')
    <section class="section-signup">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="left-design">
                        <div><a href=""><img src="{{asset('/assets/images/white-logo.png')}}" alt="logo"></a></div>
                        <div class="form-design"><a href=""><img src="{{asset('/assets/images/login-design.png')}}" alt="logo" class="img-fluid"></a></div>
                        <div><p class="text-white">© 2022 HelloLaundry.Co.UK. All right reserved.</p></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-form">
                        <div class="text-end mb-96">
                            <a class="btn btn-brand outline py-12 px-4" href="https://book.hellolaundry.co.uk/">Place Order</a>
                            <a class="btn btn-brand  py-12 px-4 ms-4" href="{{env('APP_URL')}}/prices">Prices & Services</a>
                        </div>
                        <h1 class="text-center mb-4 fw-bolder">{{ __('Reset Password') }}</h1>
                        <div class="form-login-wrap">

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="mb-4">
                                    <label for="email" class="form-label mb-2">Email address</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password-confirm" class="form-label">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" >
                                    @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-brand w-100 b-radius-16 py-12 px-4 mt-40">{{ __('Reset Password') }}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
