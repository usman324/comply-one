    @extends('auth.master')
    @section('css')
    @stop
    @section('content')
        <div class="p-lg-5 p-4">
            <div>
                <h5 class="text-primary">Welcome Back !</h5>
                <p class="text-muted">Sign in to continue to {{ gs()->title }}.</p>
            </div>

            <div class="mt-4">
                <form action="{{ url('login') }}" method="post" id="login-form">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Enter email">
                    </div>

                    <div class="mb-3">
                        <div class="float-end">
                            <a href="javascript:" class="text-muted">Forgot
                                password?</a>
                        </div>
                        <label class="form-label" for="password-input">Password</label>
                        <div class="position-relative auth-pass-inputgroup mb-3">
                            <input type="password" name="password" class="form-control pe-5 password-input"
                                placeholder="Enter password" id="password-input">
                            <button
                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                        <label class="form-check-label" for="auth-remember-check">Remember
                            me</label>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-success w-100" type="submit">Sign
                            In</button>
                    </div>
                </form>
            </div>


        </div>
    @stop
    @section('js')
    @stop
