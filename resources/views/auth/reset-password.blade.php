    @extends('auth.master')
    <!-- Content -->
    @section('css')
    @stop
    @section('content')
        <div class="w-px-400 mx-auto">
            <h3 class="mb-1">{{ gs()->title }} 🚀</h3>
            @if (session('message'))
                <p class="alert alert-danger text-capitalize">{{ session('message') }}</p>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach
            @endif
            <form action="{{ url('reset-password') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ request()->segment(2) }}">
                <input type="hidden" name="public_id" value="{{ request()->segment(3) }}">

                <div class="mb-3 form-password-toggle">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password"
                            aria-describedby="password" required />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        <div data-field="password" data-validator="notEmpty">{{ $errors->first('password') }}
                        </div>
                    </div>
                </div>
                <div class="mb-3 form-password-toggle">
                    <label class="form-label" for="password">Confirm Password</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                            aria-describedby="password" required />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        <div data-field="password" data-validator="notEmpty">{{ $errors->first('password_confirmation') }}
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary d-grid w-100" type="submit">Reset password</button>
            </form>

        </div>
    @stop
    @section('js')
    @stop
    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
