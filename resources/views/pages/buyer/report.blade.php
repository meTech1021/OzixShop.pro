@extends('layout.main')

@section('page_js')
    <script src="{{ asset('js/buyer/report.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>How to report a bad item ? <small><b>Account</b> &gt; <b>My Orders</b> then choose the item you want to report and click on <b>Report</b> button.</small></h1>
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
                        <table class="table table-bordered table-striped table-hover" id="report_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Created Date</th>
                                    <th>Order ID</th>
                                    <th>Item Type</th>
                                    <th>Seller</th>
                                    <th>Report State</th>
                                    <th>Last Reply</th>
                                    <th>Last Updated</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->created_at }}</td>
                                        <td>
                                            @if (empty($report->order_id))
                                                N/A
                                            @else
                                                {{ $report->order_id }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $report->acctype }}
                                        </td>
                                        <td>seller {{ $report->seller_id }}</td>
                                        <td>
                                            @if ($report->status == 0)
                                                <label class="text-danger"><i class="fa fa-times"></i> Closed</label>
                                            @else
                                                <label class="text-success"><i class="fa fa-check"></i> Pending</label>
                                            @endif
                                        </td>
                                        <td>{{ $report->last_reply }}</td>
                                        <td>
                                            {{ $report->updated_at }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn_view" report_id="{{ $report->id }}"><i class="fa fa-eye"></i> View</button>
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

<div class="modal fade" id="ReportModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-file font-red-sharp"></i> Report #<b id="report_number"></b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row" style="padding-bottom: 10px;">
                            <div class="col-md-12 ">
                                <div class="margin-bottom-10" id="report_history"  style="max-height:500px; overflow-y: auto;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="report_user" value="{{ Auth::user()->name }}">
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
                <button type="button" class="btn btn-danger" id="btn_close" data-dismiss="modal"><i class="fa fa-times"></i> Report Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
