@extends('layout.master')
@section('content')
    <div class="m-4 flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header">Profile Details</h5>
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <img src="{{ getUser()->getImage() }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded"
                        id="uploadedAvatar" />
                </div>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <form class="add-new-user pt-0" id="add-user">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="firstName" class="form-label">Name</label>
                            <input class="form-control form-control-sm" type="text" id="name" name="name"
                                value="{{ getUser()->name }}" autofocus />
                        </div>
                        {{-- <div class="mb-3 col-md-6">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input class="form-control form-control-sm" type="text" name="last_name" id="last_name"
                                value="{{ getUser()->last_name }}" />
                        </div> --}}
                        <div class="mb-3 col-md-3">
                            <label for="email" class="form-label">Phone</label>
                            <input class="form-control form-control-sm" type="text" id="phone" name="phone"
                                value="{{ getUser()->phone }}" placeholder="" />
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="avatar" class="form-label">Avatar</label>
                            <input class="form-control form-control-sm" type="file" id="avatar" name="avatar" placeholder="" />
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button"
                            onclick="addFormData(event,'post','{{ url('profile/' . getUser()->id) }}','{{ url('profile') }}','add-user')"
                            class="btn btn-primary me-2">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-4">
            <h5 class="card-header">Password Change</h5>
            <hr class="my-0" />
            <div class="card-body">
                <form class="add-new-user pt-0" id="add-password">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="firstName" class="form-label">Current Password</label>
                            <input class="form-control form-control-sm" type="password" id="current_password" name="current_password" />
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control form-control-sm" type="password" id="password" name="password" />
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lastName" class="form-label">Password Confirmation</label>
                            <input class="form-control form-control-sm" type="password" name="password_confirmation" id="password_confirmation" />
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button"
                            onclick="addFormData(event,'post','{{ url('update-password/' . getUser()->id) }}','{{ url('profile') }}','add-password')"
                            class="btn btn-primary me-2">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
