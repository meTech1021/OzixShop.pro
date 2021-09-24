@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/admin/tickets.js') }}"></script>
@endsection

@section('page_css')
<link href="{{ asset('css/admin.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Tickets
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-clock font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Pending Tickets</span> <span class="badge badge-danger" id="ticket_cnt"> {{ count($tickets) }} </span>
                </div>
                <div class="tools">
                    <button type="button" class="btn btn-primary" id="btn_new"><i class="fa fa-plus"></i> Insert Ticket</button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="ticket_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Created Date</th>
                                    <th>Title</th>
                                    <th>Ticket State</th>
                                    <th>Last Reply</th>
                                    <th>Last Updated</th>
                                    <th>View Ticket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>
                                        <td>{{ $ticket->user }}</td>
                                        <td>{{ $ticket->created_at }}</td>
                                        <td>{{ $ticket->subject }}</td>
                                        <td>
                                            @if ($ticket->status == 0)
                                                <label class="text-danger bold"><i class="fa fa-times-circle"></i> Closed</label>
                                            @elseif($ticket->status == 1 || $ticket->status == 2)
                                                <label class="text-primary bold"><i class="fa fa-check-circle"></i> Open</label>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->last_reply }}</td>
                                        <td>{{ $ticket->updated_at }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm btn_view" type="button" ticket_id="{{ $ticket->id }}"><i class="fa fa-eye"></i> View</button>
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
<div class="modal fade" id="NewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-edit font-red-sharp"></i> Insert Ticket</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="ticket_form" novalidate="novalidate">
                    <input type="hidden" id="type">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Ticket Title <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <input type="text" class="form-control" name="title" id="title">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Username <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <select class="form-control select2me" id="username" name="username">
                                        <option value=""></option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_save"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="TicketModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-file font-red-sharp"></i> Ticket #<label id="ticket_number"></label></h4>
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
