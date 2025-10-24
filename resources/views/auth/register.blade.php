<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('front-assets/img/favicon.png') }}">
    @include('frontend.layout.partial.style')
</head>

<body>

    {{-- <div class="loader">
        <div class="d-table">
            <div class="d-table-cell">
                <div class="pre-box-one">
                    <div class="pre-box-two"></div>
                </div>
            </div>
        </div>
    </div> --}}
    @include('frontend.layout.nav')

    <div class="user-form-area">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-lg-6 p-0">
                    <div class="user-img">
                        {{-- <img src="{{ asset('front-assets/img/user-form-bg.jpg') }}" alt="User"> --}}
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="user-content">
                        <div class="d-table">
                            <div class="d-table-cell">
                                <div class="user-content-inner">
                                    <div class="top">
                                        <a href="javascript:">
                                            {{-- <img style="height: 35px !important"
                                                src="{{ Storage::url('general/' . gs()->logo) }}"
                                                alt="Logo"> --}}
                                        </a>
                                        <h2>Sign Up</h2>
                                    </div>
                                    <form action="{{ url('register') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="email" required class="form-control" name="email"
                                                        placeholder="Email">
                                                    <small class="text-danger ">{{ $errors->first('email') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="password" required name="password" class="form-control"
                                                        placeholder="New Password">
                                                    <small class="text-danger ">{{ $errors->first('password') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="password" required name="password_confirmation"
                                                        class="form-control" placeholder="Confirm assword">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <button type="submit" class="btn common-btn">Sign Up</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="bottom">
                                        <p>Already created account? <a href="{{ url('login') }}">Sign In</a></p>
                                        <h4>OR</h4>
                                        <ul>
                                            <li>
                                                <a href="{{ url('login/facebook') }}" target="_blank">
                                                    <i class="icofont-facebook"></i>
                                                    Connect with Facebook
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ url('login/google') }}" target="_blank">
                                                    <i class="icofont-google-plus"></i>
                                                    Connect with Google
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="go-top">
        <i class="icofont-arrow-up"></i>
        <i class="icofont-arrow-up"></i>
    </div>

    @include('frontend.layout.partial.script')
</body>

</html>
