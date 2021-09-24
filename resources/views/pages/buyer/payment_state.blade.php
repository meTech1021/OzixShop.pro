@extends('layout.main')

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Pay using Bitcoin</h1>
        </div>
        <!-- END PAGE TITLE -->
    </div>
</div>
<!-- END PAGE HEAD -->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <center>
                                    <a id="bitcoinbutton" href="bitcoin:{{ $payment->address }}?amount={{ $payment->amount+0.00002000 }}&amp;message=Ozix-Shop-15" target="_blank" title="Pay with Bitcoin"><img alt="Pay with Bitcoin" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&amp;data=bitcoin:{{ $payment->address }}?amount={{ $payment->amount }}&amp;message=Ozix-Shop-15&amp;choe=UTF-8&amp;chs=200x200" style="" height="150" width="150"></a><br><br>
                                </center>
                            </div>
                            <div class="col-md-7">
                                <form class="form-horizontal">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Send exactly : </label>
                                                    <div class="col-md-9">
                                                        <label class="bold form-control-static">{{ $payment->amount }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">BTC to : </label>
                                                    <div class="col-md-9">
                                                        <label class="bold form-control-static">{{ $scam->amount_usd }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table border="0" width="100%" style="margin:0px;">

                                                    <tbody>
                                                        <tr style="display: table-row;">
                                                            <td align="left"><span class="text">State</span></td>
                                                            <td align="center"><span class="text">:</span></td>
                                                            <td align="left"><span class="label label-primary" id="state"></span></td>
                                                        </tr>
                                                        <tr style="display: table-row;">
                                                            <td align="left"><span class="text">Loaded BTC</span></td>
                                                            <td align="center"><span class="text">:</span></td>
                                                            <td align="left"><span class="label label-primary" id="amount"></span> </td>
                                                        </tr>
                                                        <tr style="display: table-row;">
                                                            <td align="left"><span class="text">Last Checked</span></td>
                                                            <td align="center"><span class="text">:</span></td>
                                                            <td align="left"><span class="label label-primary" id="time"></span> <span id="Img"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="well well">
                            <ul>
                                <li><b>DO NOT CLOSE THIS PAGE</b></li>
                                <li>Please wait for at least <b>1</b> confirmation </li>
                                <li>For high amounts please include high fees </li>
                                <li>Bitcoin to USD rate is  {{ $rate }} </b> (according to Blockchain) </li>
                                <li>Our bitcoin addresses are SegWit-enabled</li>
                                <li>This page will be only valid for <b>1 hour</b></li>
                                <li>Make sure that you send exactly <b> {{ $payment->amount+0.00002000 }} BTC</b></li>
                                <li>After payment an amount of <b>{{ $payment->amount_usd }}$</b> will be added to your account</li>
                                <li>If any error happened or money didn't show please <a class="label label-primary " href="{{ url('/ticket') }}"><span class="fa fa-pencil"></span> Open a Ticket</a> Fast </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
