@extends('layout.master')
@section('content')
    <div class="m-4 flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>General Setting</h5>
                    </div>
                    <div class="card-body">
                        <form id="add-branch">
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Title</label>
                                    <input class="form-control form-control-sm" type="text" name="title"
                                        value="{{ gs()->title }}">
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control form-control-sm" type="text" name="phone"
                                        value="{{ gs()->phone }}">
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Email</label>
                                    <input class="form-control form-control-sm" type="text" name="email"
                                        value="{{ gs()->email }}">
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Favicon</label>
                                    <input class="form-control form-control-sm" type="file" name="favicon">
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Logo</label>
                                    <input class="form-control form-control-sm" type="file" name="logo">
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Logo Height</label>
                                    <input class="form-control form-control-sm" type="tes" name="logo_height"
                                        value="{{ gs()->logo_height }}">
                                    <div class="valid-feedback">Looks good!</div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Address</label>
                                    <input class="form-control form-control-sm" type="text" name="address"
                                        value="{{ gs()->address }}">
                                    <div class="valid-feedback">Looks good!</div>
                                </div>

                            </div>
                            <button
                                onclick="addFormData(event,'post','{{ $url . '/' . gs()->id }}','{{ $url }}','add-branch')"
                                class="btn btn-primary btn-pill btn-sm me-3  float-end " type="button">Update</button>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
