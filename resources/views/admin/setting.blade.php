@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Settings</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="#">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Settings</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="col-lg-12">
                <div class="page-content my-account__edit">
                    @if($errors->has('old_password'))
                        <p class="alert alert-danger">{{ implode(', ', $errors->get('old_password')) }}</p>
                    @endif
                        @if(Session::has('success'))
                            <p class="alert alert-success">{{ Session::get('success') }}</p>
                        @endif
                    <div class="my-account__edit-form">
                        <form name="account_edit_form" action="{{ route('admin.password.update', ['id' => $admin->id]) }}" method="POST"
                              class="form-new-product form-style-1 needs-validation"
                              novalidate="">
                                @csrf
                                @method('PUT')
                            <fieldset class="name">
                                <div class="body-title">Name <span class="tf-color-1">*</span>
                                </div>
                                <input class="flex-grow" type="text" placeholder="Full Name"
                                       name="name" tabindex="0" value="{{ $admin->name }}" aria-required="true"
                                       required="">
                            </fieldset>

                            <fieldset class="name">
                                <div class="body-title">Mobile Number <span
                                        class="tf-color-1">*</span></div>
                                <input class="flex-grow" type="text" placeholder="Mobile Number"
                                       name="phone_number" tabindex="0" value="{{ $admin->phone_number }}" aria-required="true"
                                       required="">
                            </fieldset>

                            <fieldset class="name">
                                <div class="body-title">Email Address <span
                                        class="tf-color-1">*</span></div>
                                <input class="flex-grow" type="text" placeholder="Email Address"
                                       name="email" tabindex="0" value="{{ $admin->email }}" aria-required="true"
                                       required="">
                            </fieldset>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="my-3">
                                        <h5 class="text-uppercase mb-0">Password Change</h5>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <fieldset class="name">
                                        <div class="body-title pb-3">Old password <span
                                                class="tf-color-1">*</span>
                                        </div>
                                        <input class="flex-grow" type="password"
                                               placeholder="Old password" id="old_password"
                                               name="old_password" tabindex="0" value=""
                                               required="">
                                    </fieldset>

                                </div>
                                <div class="col-md-12">
                                    <fieldset class="name">
                                        <div class="body-title pb-3">New password <span
                                                class="tf-color-1">*</span>
                                        </div>
                                        <input class="flex-grow" type="password"
                                               placeholder="New password" id="new_password"
                                               name="new_password" aria-required="true"
                                               required="">
                                    </fieldset>
                                    @error('new_password')
                                    <span class="alert alert-danger text-center">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <fieldset class="name">
                                        <div class="body-title pb-3">Confirm new password <span
                                                class="tf-color-1">*</span></div>
                                        <input class="flex-grow" type="password"
                                               placeholder="Confirm new password" cfpwd=""
                                               data-cf-pwd="#new_password"
                                               id="new_password_confirmation"
                                               name="new_password_confirmation"
                                               aria-required="true" required="">
                                        @error('new_password_confirmation')
                                        <span class="alert alert-danger text-center">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                                <div class="col-md-12">
                                    <div class="my-3">
                                        <button type="submit"
                                                class="btn btn-primary tf-button w208">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/apexcharts/apexcharts.js"></script>
<script src="js/main.js"></script>
</body>

</html>