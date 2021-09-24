@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/admin/withdraw/approval.js') }}"></script>
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Withdraw Approval
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-users font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Withdraw Request</span> <span class="badge badge-danger" id="withraw_cnt"> {{ count($sellers) }} </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="withdraw_table">
                            <thead>
                                <tr>
                                    <th>Seller</th>
                                    <th>Sales USD</th>
                                    <th>Pending USD</th>
                                    <th>Total USD</th>
                                    <th>Receive USD</th>
                                    <th>Receive BTC</th>
                                    <th>BTC Address</th>
                                    <th>Ozix % USD</th>
                                    <th>Pay Seller</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sellers as $seller)
                                    <tr>
                                        <td><label class="bold"> {{ $seller['username'] }}</label></td>
                                        <td><label class="text-primary">$</label><label class="text-danger bold">{{ $seller['sold_btc'] }}</label></td>
                                        <td><label class="text-primary">$</label><label class="text-danger bold">{{ $seller['pending_orders'] }}</label></td>
                                        <td><label class="text-primary">$</label><label class="text-danger bold">{{ $seller['total'] }}</label></td>
                                        <td><label class="text-primary">$</label><label class="text-danger bold">{{ $seller['receive'] }}</label></td>
                                        <td><label class="text-danger bold">{{ $seller['receive_btc'] }}</label></td>
                                        <td>{{ $seller['btc_address'] }}</td>
                                        <td><label class="text-primary">$</label><label class="text-danger bold">{{ $seller['receive_fee'] }}</label></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary btn_pay" type="button" seller="{{ $seller['username'] }}" receive_usd="{{ $seller['receive'] }}" receive_btc="{{ $seller['receive_btc'] }}" btc_address="{{ $seller['btc_address'] }}" pending="{{ $seller['pending_orders'] }}" type="button" seller_id="{{ $seller['id'] }}" total_amount="{{ $seller['total'] }}">Pay </button>
                                            <button class="btn btn-sm btn-danger btn_pay_manual" type="button" seller_id="{{ $seller['id'] }}" seller="{{ $seller['username'] }}" receive_usd="{{ $seller['sold_btc'] - $seller['pending_orders'] }}" receive_btc="{{ $seller['receive_btc'] }}" btc_address="{{ $seller['btc_address'] }}" pending="{{ $seller['pending_orders'] }}" total_amount="{{ $seller['total'] }}">Pay Manual</button>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><h4 class="text-danger bold">TOTAL</h4></td> <td></td><td></td><td></td><td><h4 class="text-danger bold" id="total_seller">{{ $total_seller }}</h4></td><td></td><td></td><td><h4 class="text-danger bold" id="total_fee">{{ $total_fee }}</h4></td><td></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="PayModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-money font-red-sharp"></i> Pay for <b id="seller_name"></b></h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" class="form-horizontal" id="pay_form" novalidate="novalidate">
                    <input type="hidden" id="type">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Seller Name <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="username" type="text" name="username" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Amount in USD <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="amount" type="number" name="amount" disabled>
                                            <span class="input-group-addon">
												<i class="fa fa-usd"></i>
												</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Amount in BTC <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="amount_btc" type="number" name="amount_btc" disabled>
                                            <span class="input-group-addon">
												<i class="fa fa-btc"></i>
												</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">BTC Address <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="btc_address" name="btc_address" disabled></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Fee Estimated : </label>
                                    <div class="col-md-8">
                                        <label class="text-danger bold" id="estimate_fee" style="font-size: 25px;"></label> <b style="font-size: 25px;">~</b> <label class="text-danger bold" id="total_fee" style="font-size: 25px;"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_send"><i class="fa fa-paper-plane"></i> Send </button>
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ManualPayModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-money font-red-sharp"></i> Pay for <b id="seller_manual_name"></b></h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" class="form-horizontal" id="manual_form" novalidate="novalidate">
                    <input type="hidden" id="type">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Seller Name <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="manual_username" type="text" name="manual_username">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Amount in USD <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="manual_amount" type="number" name="manual_amount">
                                            <span class="input-group-addon">
												<i class="fa fa-usd"></i>
												</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Amount in BTC <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="manual_amount_btc" type="number" name="manual_amount_btc">
                                            <span class="input-group-addon">
												<i class="fa fa-btc"></i>
												</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">BTC Address <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="manual_btc_address" name="manual_btc_address"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Pending USD <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="manual_pending" type="number" name="manual_pending">
                                            <span class="input-group-addon">
												<i class="fa fa-usd"></i>
												</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Fee Rate <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" id="manual_fee_rate" type="number" name="manual_fee_rate">
                                            <span class="input-group-addon">
												<i class="fa fa-btc"></i>
												</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_manual_send"><i class="fa fa-paper-plane"></i> Pay </button>
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
