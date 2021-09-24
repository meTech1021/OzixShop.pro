@extends('layout.main')

@section('page_js')
<script src="{{ asset('js/buyer/setting.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>My Profile</h1>
        </div>
        <!-- END PAGE TITLE -->
    </div>
</div>
<!-- END PAGE HEAD -->
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-4" id="account_setting">
                                <h4 class="text-center bold">Account Information</h4><hr>
                                <p>Your Name is <b>{{ Auth::user()->name }}</b></p>
                                <p>Your Email is <b>{{ Auth::user()->email }}</b></p>
                                <p>
                                    @if (Auth::user()->role == 1)
                                        @php
                                            $role = 'Admininstrator';
                                        @endphp
                                    @elseif (Auth::user()->role == 2)
                                        @php
                                            $role = 'Seller';
                                        @endphp
                                    @else
                                        @php
                                            $role = 'Buyer';
                                        @endphp
                                    @endif
                                    Your Role is <b>[{{ $role }}]</b>.
                                </p>
                                <p>Current balance is <b class="text-primary">$</b><b class="text-danger">{{ Auth::user()->balance }}</b></p>
                                <p>Registered on <b>{{ Auth::user()->created_at }}</b></p>
                                <p>Last login on <b>{{ Auth::user()->last_login_at }}</b></p>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-center bold">Edit Information</h4><hr>
                                <form id="setting_form" method="post">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="control-label">Current Password <span class="required">*</span></label>
                                            <input type="password" class="form-control" id="current_password" name="current_password">
                                        </div>
                                    </div>
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="control-label">New Password <span class="required">*</span></label>
                                            <input type="password" class="form-control" name="password" id="password">
                                        </div>
                                    </div>
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="control-label">Password Confirm <span class="required">*</span></label>
                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                                        </div>
                                    </div>
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="control-label">Email <span class="required">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email" value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>
                                    <div class="form-body">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" id="btn_submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
