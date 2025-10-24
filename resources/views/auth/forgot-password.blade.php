    @extends('auth.master')
    <!-- Content -->
    @section('css')
    @stop
    @section('content')
        <div class="w-px-400 mx-auto">

            <!-- /Logo -->
            <h3 class="mb-1">{{ gs()->title }} 🚀</h3>
            @if (session('message'))
                <p class="alert alert-danger text-capitalize">{{ session('message') }}</p>
            @endif
            <form action="{{ url()->current() }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Enter your email" required />
                </div>
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="password" data-validator="notEmpty">{{ $errors->first('email') }}</div>
                </div>
                <button class="btn btn-primary d-grid w-100" type="submit">Request New Password</button>
            </form>
            <br>
            <p class="text-center">
                <span>I already have account?</span>
                <a href="{{ url('login') }}">
                    <span>Login</span>
                </a>
            </p>

            {{-- <div class="divider my-4">
            <div class="divider-text">or</div>
        </div>

        <div class="d-flex justify-content-center">
            <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
                <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
            </a>

            <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
                <i class="tf-icons fa-brands fa-google fs-5"></i>
            </a>

            <a href="javascript:;" class="btn btn-icon btn-label-twitter">
                <i class="tf-icons fa-brands fa-twitter fs-5"></i>
            </a>
        </div> --}}
        </div>
    @stop
    @section('js')
    @stop
    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
