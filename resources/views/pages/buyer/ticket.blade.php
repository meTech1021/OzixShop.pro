@extends('layout.main')

@section('page_js')
    <script src="{{ asset('js/buyer/ticket.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>My Tickets <small>Add & list</small></h1>
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
                        <div class="tabbable-custom ">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_5_1" data-toggle="tab">
                                    My Tickets </a>
                                </li>
                                <li>
                                    <a href="#tab_5_2" data-toggle="tab">
                                    New Ticket </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_5_1">
                                    <table class="table table-hover table-bordered table-striped" id="ticket_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Created Date</th>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Last Reply</th>
                                                <th>Last Updated</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tickets as $ticket)
                                                <tr>
                                                    <td>{{ $ticket->id }}</td>
                                                    <td>{{ $ticket->created_at }}</td>
                                                    <td>{{ $ticket->subject }}</td>
                                                    <td>
                                                        @if ($ticket->status == 0)
                                                            <label class="text-danger bold"><i class="fa fa-times"></i> Closed</label>
                                                        @else
                                                            <label class="text-success bold"><i class="fa fa-check"></i> Open</label>
                                                        @endif
                                                    </td>
                                                    <td>{{ $ticket->last_reply }}</td>
                                                    <td>{{ $ticket->updated_at }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary btn_view" ticket_id="{{ $ticket->id }}"><i class="fa fa-eye"></i> View</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab_5_2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="well">
                                                <ul>
                                                    <li>In order to refund ticket go to <b>Account</b> -&gt; <b>My Orders</b> and choose the tool and click on <b>Report</b> button</li>
                                                    <li>Do not create double-tickets , create just one ticket and include all your problems then wait for your ticket to be replied</li>
                                                </ul>
                                           </div>
                                        </div>
                                        <div class="col-md-6">
                                            <form method="post" action="{{ url('/ticket/save') }}" id="ticket_form">
                                                @csrf
                                                <div class="form-body">
                                                    <div class="form-group">
                                                        <label class="control-label">Title <span class="required" aria-required="true">
                                                            * </span></label>
                                                        <input type="text" class="form-control" placeholder="Seller request..." id="title" name="title">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Message <span class="required" aria-required="true">
                                                            * </span></label>
                                                        <textarea class="form-control" placeholder="Message here..." rows="5" id="message" name="message"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-actions text-center">
                                                    <button type="submit" class="btn green" id="btn_submit">Submit</button>
                                                    <button type="reset" class="btn default">Reset</button>
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
