@extends('layout.admin_main')

@section('page_css')
<link href="{{ asset('css/withdraw.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_js')
<script src="{{ asset('js/seller/withdraw.js') }}"></script>
@endsection

@section('page_content')
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
        Withdraw <small>for Seller{{ Auth::user()->seller_id }}</small>
    </h3>
    <!-- END PAGE HEADER-->
    <div class="rwow">
        <div class="col-md-12">
            <div class="tabbable-custom ">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a href="#tab_sale" data-toggle="tab">
                        Sales </a>
                    </li>
                    <li>
                        <a href="#tab_eidt_address" data-toggle="tab">
                        Edit Address </a>
                    </li>
                    <li>
                        <a href="#tab_payment_history" data-toggle="tab">
                        Payment History </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_sale">
                        <div class="portlet light">
                            <div class="portlet-body">
                                <div class="invoice">
                                    <div class="row invoice-logo">
                                        <div class="col-md-12 invoice-logo-space text-center">
                                            <h2 class="text-success bold">Seller{{ Auth::user()->seller_id }} Invoice</h2>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-push-4 col-md-4 text-center">
                                            <ul class="list-unstyled amounts">
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <label class="bold">Sales : </label>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <label class="text-primary">$</label>
                                                        <label class="text-danger bold" id="sales_amount">
                                                            @if ($resseller == null)
                                                                0
                                                            @else
                                                                {{ $resseller->sold_btc }}
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <label class="bold">Pending Sales : <abbr title="Please understand that each tool you sell will be pending for 24 hours before we pay you in order to give the buyer a chance to report it if it was bad."> [?]</abbr> {{ $t }}</label>

                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <label class="text-primary">$</label>
                                                        <label class="text-danger bold" id="sales_amount">{{ $pending_orders }}</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <label class="bold">Total : </label>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <label class="text-primary">$</label>
                                                        <label class="text-danger bold" id="total_amount">{{ $total }}</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <label class="bold">Your Part : </label>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <label class="text-primary">65%</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <label class="bold">Receive : </label>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <label class="text-primary">$</label>
                                                        <label class="text-danger bold" id="receive_amount">{{ $total*65/100 }}</label>
                                                    </div>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row margin-top-20">
                                        <div class="col-md-push-4 col-md-4 text-right" id="withdraw_state">
                                            @php
                                                $receive = $total*65/100;
                                            @endphp
                                            @if ($receive < 20)
                                            <div class="alert alert-warning text-left">
                                                Your receive should be more than 20$ in order to request <b>Withdraw</b>!
                                            </div>
                                            @else
                                                @if ($resseller != null && $resseller->withdrawal == 'requested')
                                                <div class="alert alert-success text-left">
                                                    <h4 class="bold">Request submitted!</h4>
                                                    Your <b>Withdraw</b> request will be sent by admin soon!
                                                </div>
                                                @else
                                                <div class="alert alert-info text-left">
                                                    Please click on <b>'Withdraw'</b> to request payment!
                                                </div>
                                                <button type="button" id="btn_withdraw" class="btn btn-lg btn-primary"><label id="withdraw_label"><i class="fa fa-check"></i> Withdraw</label></button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_eidt_address">
                        <div class="row">
                            <div class="col-md-12">
                                <form>
                                    <div class="form-group">
                                        <label class="control-label bold text-primary">Bitcoin Address</label>
                                        <input type="text" class="form-control input-lg" id="btc_address" placeholder="1CoQrt8TjFJs974KW3qEv37hMTo5FBqk2d">

                                    </div>
                                    <button type="button" class="btn btn-primary" id="btn_save_btc_address"><i class="fa fa-save"></i> Change</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_payment_history">
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-striped table-hover" id="payment_history_table">
                                    <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                <i class="fa fa-calendar"></i> Date
                                            </th>
                                            <th class="hidden-480">
                                                Sold
                                            </th>
                                            <th class="hidden-480">
                                                Percentage
                                            </th>
                                            <th class="hidden-480">
                                                BTC Rate
                                            </th>
                                            <th>
                                                BTC
                                            </th>
                                            <th>
                                                USD
                                            </th>
                                            <th>
                                                Fee
                                            </th>
                                            <th>
                                                Bitcoin Address
                                            </th>
                                            <th>
                                                Hash
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $num = 1;
                                        @endphp
                                        @foreach ($payment_histories as $history)
                                            <tr>
                                                <td>{{ $num++ }}</td>
                                                <td>{{ $history->created_at }}</td>
                                                <td><label class="text-danger bold">{{ $history->amount }}</label><label class="text-primary">$</label></td>
                                                <td>65%</td>
                                                <td>
                                                    @if (empty($history->rate))
                                                        @php
                                                            $rate = 'N/A';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $rate = $history->rate;
                                                        @endphp
                                                    @endif
                                                    {{ $rate }}
                                                </td>
                                                <td><label class="text-danger bold">{{ $history->amount_btc }}</label><span class="fa fa-bitcoin text-primary"></span></i></td>
                                                <td><label class="text-danger bold">{{ substr(($history->amount)*100/65, 0, 5) }}</label><label class="text-primary">$</label></td>
                                                <td>{{ $history->fee }}</td>
                                                <td>
                                                    <a target="_blank" href="https://mempool.space/address/{{ $history->btc_address }}">
                                                     {{ $history->btc_address }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ $history->url }}" target="_blank" class="btn btn-sm btn-primary">
                                                        info
                                                    </a>
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
    </div>
@endsection
