    @extends('auth.master')
    <!-- Content -->
    @section('css')
    @stop
    @section('content')
        <div class="w-px-400 mx-auto">
            <!-- Logo -->
            {{-- <div class="app-brand mb-4">
        <a href="index.html" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                        fill="#7367F0" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                        fill="#161616" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                        fill="#161616" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                        fill="#7367F0" />
                </svg>
            </span>
        </a>
        </div> --}}
            <!-- /Logo -->
            <h3 class="mb-1">{{ gs()->title }} 🚀</h3>

            <form class="mb-3" action="{{ url('login') }}" method="POST" id="login-form">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Enter your email" />
                </div>
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="password" data-validator="notEmpty">{{ $errors->first('email') }}</div>
                </div>
                <div class="mb-3 form-password-toggle">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        <div data-field="password" data-validator="notEmpty">{{ $errors->first('password') }}
                        </div>
                    </div>
                </div>
                <div class="mb-3 form-password-toggle">
                    {{-- <label class="form-label" for="password">OTP</label> --}}
                    <div class="input-group input-group-merge">
                        <input type="text" id="otp" name="otp" style="display: none" class="form-control mb-3"
                            placeholder="OTP">
                    </div>
                </div>

                <button class="btn btn-primary d-grid w-100" type="submit">Sign In</button>
            </form>

            <p class="text-center">
                <span>I forgot my password?</span>
                <a href="{{ url('forgot-password') }}">
                    <span>Forgot Password</span>
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
