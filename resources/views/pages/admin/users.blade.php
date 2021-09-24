@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/admin/users.js') }}"></script>
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Users
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-users font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Total Users</span> <span class="badge badge-danger" id="user_cnt"> {{ count($users) }} </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="user_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Balance</th>
                                    <th>Items purch</th>
                                    <th>Invited By</th>
                                    <th>Last Login</th>
                                    <th>Seller</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><label class="text-danger bold">{{ $user->balance }}</label><label class="text-primary">$</label></td>
                                        <td>{{ $user->ipurchassed }}</td>
                                        <td>
                                            @if (empty($user->ref))
                                                N/A
                                            @else
                                                {{ $user->ref }}
                                            @endif
                                        </td>
                                        <td>{{ $user->last_login_at }}</td>
                                        <td>
                                            @if ($user->role < 3)
                                                <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Seller</button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-primary btn_make" user_id="{{ $user->id }}">Make Seller</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
