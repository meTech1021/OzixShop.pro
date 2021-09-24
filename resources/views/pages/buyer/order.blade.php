@extends('layout.main')

@section('page_js')
    <script src="{{ asset('js/buyer/order.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>My Orders <small>You can only report a bad tool within <b>10 hours</b> by clicking on <a class="btn btn-danger btn-xs"><font color=white>Report #[Order Id]</a></font> , Otherwise we can't give you refund or replacement!</small></h1>
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
                        <table class="table table-bordered table-striped table-hover" id="order_table">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="11%">Type</th>
                                    <th width="40%">Item Information</th>
                                    <th width="8%">Open</th>
                                    <th width="8%">Price</th>
                                    <th width="8%">Seller</th>
                                    <th width="8%">Report</th>
                                    <th width="12%">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->id }}</td>
                                        <td>{{ $purchase->type }}</td>
                                        <td>{{ $purchase->url }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn_open" purchase_id="{{ $purchase->id }}">Open # {{ $purchase->id }}</button>
                                        </td>
                                        <td>
                                            <label class="text-danger bold">{{ $purchase->price }}</label><label class="text-primary">$</label>
                                        </td>
                                        <td>seller {{ $purchase->seller_id }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger btn_report" type="button" user="{{ Auth::user()->name }}" id="order_{{$purchase->id}}" purchase_type="{{ $purchase->type }}" purchase_id="{{ $purchase->id }}" report_id="{{ $purchase->report_id }}">Report # {{ $purchase->id }}</button>
                                        </td>
                                        <td>
                                            {{ $purchase->created_at }}
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

<div class="modal fade" id="OrderModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-shopping-cart font-red-sharp"></i> Order #<b id="order_number"></b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4 class="control-label col-md-3 bold">Country : </h4>
                                    <div class="col-md-9">
                                        <h4 class="form-control-static" id="country_span"></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4 class="control-label col-md-3 bold">Type : </h4>
                                    <div class="col-md-9">
                                        <h4 class="form-control-static" id="type_span"></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4 class="control-label col-md-3 bold">Price : </h4>
                                    <div class="col-md-9">
                                        <h4 class="form-control-static" id="price_span"></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4 class="control-label col-md-3 bold">Seller : </h4>
                                    <div class="col-md-9">
                                        <h4 class="form-control-static" id="seller_span"></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4 class="control-label col-md-3 bold">Item Information : </h4>
                                    <div class="col-md-9">
                                        <h4 class="form-control-static" id="info_span"></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4 class="control-label col-md-3 bold">Description : </h4>
                                    <div class="col-md-9">
                                        <h4 class="form-control-static" id="description_span"></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn_close" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ReportModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <form class="margin-top-20" id="report_form">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="well">
                                    <h4><b>Report Of Order # <font id="order_id"></font> </b></h4>
                                    <p>Please write clearly what is wrong with this <b id="order_type"></b> and why you want to refund it</p>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding-bottom: 10px;">
                            <div class="col-md-12 ">
                                <div class="margin-bottom-10" id="report_history"  style="max-height:600px; overflow-y: auto;">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <form id="reply_form">
                                    <div class="input-group">
                                        <textarea type="password" class="form-control" name="reply" id="reply" rows="1"></textarea>
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" id="btn_send" report_id=""><i class="fa fa-paper-plane"></i> Reply</button>
                                        </span>

                                    </div>
                                    <span class="help-block">( Please press <b>"Enter"</b> to send message and press <b class="bold">"Shift+Enter"</b> to next line)</span>
                                </form>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
