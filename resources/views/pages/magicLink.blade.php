@extends('layouts.auth',['title' => 'Login', 'active' => 'login'])

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
                            <a class="btn btn-brand  py-12 px-4 ms-4" href="/prices">Prices & Services</a>
                        </div>
                        <h1 class="text-center mb-4 fw-bolder">Welcome back</h1>
                        <div class="form-login-wrap">
                            <h5 class="text-center mb-30">Welcome back! Please enter your details</h5>
                            <p class="text-center my-5 color-primary before"><b class="bg-white">OR</b></p>
                            <form method="POST" action="{{ route('send.magicLink') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="email" class="form-label mb-2">Email address</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @if (session('error'))
                                    <div id="error" class="error text-danger pl-3 my-5" for="error" style="display: block;">
                                        <strong>{{ session('error') }}</strong>
                                    </div>
                                @endif
                                <button type="submit" class="btn btn-brand w-100 b-radius-16 py-12 px-4 mt-40">Login</button>
                                <div class="text-center mt-32">
                                    <a class="btn google-link" href="/login/oauth/google"><i class="icon icon-google me-2"></i> Sign in with Google</a>
                                </div>
                                <p class="text-center mt-32">Don’t have an account?<a href="/register" class="color-brand text-decoration-none">Sign up</a></p>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
