@extends('layout.main')

@section('page_js')
    <script src="{{ asset('js/buyer/balance.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>My Balance <small>Add & payment history</small></h1>
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
                                    Add Balance </a>
                                </li>
                                <li>
                                    <a href="#tab_5_2" data-toggle="tab">
                                    Payment History </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_5_1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="well">
                                                <ul>
                                                    <li>If you sent <b>Money</b> but it don't appear in your account please <a class="label label-primary " href="{{ url('/ticket') }}"><span class="fa fa-pencil"></span> Write Ticket</a></b></li>
                                                    <li>After payment funds will be added automatically to your account <b>INSTANTLY</b></li>
                                                    <li><b>PerfectMoney</b>/<b>Bitcoin</b> is a secure way to fund your account </li>
                                                    <li>Min is 5 USD For Bitcoin</li>
                                                   <!-- <li>Min is 5 USD For Perfect Money</li>-->
                                                    <li><b>Buyer Protection</b>
                                                      - any time you like , you can ask for <b>BALANCE REFUND !</b>
                                                       </li>

                                                </ul>
                                           </div>
                                        </div>
                                        <div class="col-md-6">
                                            <form method="post" action="{{ url('/balance/save') }}" id="balance_form">
                                                @csrf
                                                <div class="form-body">
                                                    <div class="form-group">
                                                        <label class="control-label">Method <span class="required" aria-required="true">
                                                            * </span></label>
                                                        <select class="form-control" multiple id="method" name="method">
                                                            <option class="fa fa-btc" value="BitcoinPayment" selected>Bitcoin</option>
                                                            <option value="PerfectMoneyPayment">Perfect Money</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Amount <span class="required" aria-required="true">
                                                            * </span></label>
                                                        <input type="number" class="form-control" id="amount" name="amount" placeholder="5">
                                                    </div>
                                                </div>
                                                <div class="form-actions text-center">
                                                    <button type="button" class="btn green" id="btn_submit"><i class="fa fa-plus"></i> Add Balance</button>
                                                    <button type="reset" class="btn default">Reset</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="tab_5_2">
                                    <table class="table table-hover table-bordered table-striped" id="balance_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Method</th>
                                                <th>BTC Amount</th>
                                                <th>USD Amount</th>
                                                <th>Address</th>
                                                <th>State</th>
                                                <th>Created Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->id }}</td>
                                                <td>{{ $payment->method }}</td>
                                                <td><b class="text-primary">{{ $payment->amount }}</b><b class="text-danger">BTC</b></td>
                                                <td><b class="text-primary">{{ $payment->amount_usd }}</b><b class="text-danger">$</b></td>
                                                <td>{{ $payment->address }}</td>
                                                <td><b class="text-success">{{ $payment->state }}</b></td>
                                                <td>{{ $payment->created_at }}</td>
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
    </div>
</div>
@endsection
