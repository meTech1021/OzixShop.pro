@extends('layout.admin_main')

@section('page_js')
<script>
    var sales = {!! $sales !!};
    var users = {!! $users !!};
</script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection

@section('page_content')
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
        Dashboard <small>reports & statistics</small>
    </h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN DASHBOARD STATS -->
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                            {{ $tickets_cnt }}
                    </div>
                    <div class="desc">
                        <label class="bold">Pending Tickets</label>
                    </div>
                </div>
                <a class="more" href="{{ url('/admin/tickets') }}">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                            {{ $reports_cnt }}
                    </div>
                    <div class="desc">
                        <label class="bold">Pending Reports</label>
                    </div>
                </div>
                <a class="more" href="{{ url('/admin/reports') }}">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    <i class="icon-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                            {{ $users_cnt }}
                    </div>
                    <div class="desc">
                        <label class="bold">Users</label>
                    </div>
                </div>
                <a class="more" href="{{ url('/admin/users') }}">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple-plum">
                <div class="visual">
                    <i class="glyphicon glyphicon-fire"></i>
                </div>
                <div class="details">
                    <div class="number">
                            {{ $sellers_cnt }}
                    </div>
                    <div class="desc">
                        <label class="bold">Sellers</label>
                    </div>
                </div>
                <a class="more" href="{{ url('/admin/sellers') }}">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- END DASHBOARD STATS -->
    <div class="clearfix">
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="portlet light tasks-widget">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-clock font-green-haze"></i>
                        <span class="caption-subject font-green-haze bold uppercase">Last Tickets</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover" id="ticket_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Title</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recent_tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->user }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>{{ $ticket->created_at }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn_view" type="button" ticket_id="{{ $ticket->id }}"><i class="fa fa-eye"></i> View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="portlet light tasks-widget">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-users font-green-haze"></i>
                        <span class="caption-subject font-green-haze bold uppercase">Last Users</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover" id="user_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recent_users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix">
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-green-haze"></i>
                        <span class="caption-subject bold uppercase font-green-haze">Ozix Shop Sales</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="sale_chart" class="chart" style="height: 400px; width:100%;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-green-haze"></i>
                        <span class="caption-subject bold uppercase font-green-haze">Registered Users</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="user_chart" class="chart" style="height: 400px; width:100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="TicketModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title bold text-danger"><i class="fa fa-file font-red-sharp"></i> Ticket #<b id="ticket_number"></b></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel panel-primary">
                                <!-- Default panel contents -->
                                <div class="panel-heading">
                                    <h3 class="panel-title bold">Ticket Information</h3>
                                </div>
                                <ul class="list-group" id="ticket_info">
                                    <li class="list-group-item">
                                        Title <span class="badge badge-primary" id="ticket_title"></span>
                                    </li>
                                    <li class="list-group-item">
                                        User <span class="badge badge-success" id="ticket_user"></span>
                                    </li>
                                    <li class="list-group-item">
                                        Date <span class="badge badge-warning" id="ticket_date"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row" style="padding-bottom: 10px;">
                                <div class="col-md-12 ">
                                    <div class="margin-bottom-10" id="ticket_history"  style="max-height:500px; overflow-y: auto;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="reply_form">
                                        <div class="input-group">
                                            <textarea type="password" class="form-control" name="reply" id="reply" rows="1"></textarea>
                                            <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary" id="btn_send"><i class="fa fa-paper-plane"></i> Reply</button>
                                            </span>

                                        </div>
                                        <span class="help-block">( Please press <b>"Enter"</b> to send message and press <b class="bold">"Shift+Enter"</b> to next line)</span>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn_close" ticket_id="" data-dismiss="modal"><i class="fa fa-times"></i> Ticket Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
