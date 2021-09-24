@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/admin/withdraw/history.js') }}"></script>
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Withdraw History
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-users font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Withdraw History</span> <span class="badge badge-danger" id="history_cnt"> {{ count($histories) }} </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-bordered" id="history_table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Date</th>
                                    <th>Sold</th>
                                    <th>Percentage</th>
                                    <th>BTC Rate</th>
                                    <th>BTC</th>
                                    <th>USD</th>
                                    <th>Fee</th>
                                    <th>Bitcoin Address</th>
                                    <th>Hash</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($histories as $history)
                                <tr>
                                    <td>{{ $history->username }}</td>
                                    <td>{{ $history->created_at }}</td>
                                    <td><label class="text-danger bold">{{ $history->amount }}</label><label class="text-primary">$</label></td>
                                    <td><label class="text-primary">65%</label></td>
                                    <td><label class="text-danger bold">{{ $history->rate }}</label></td>
                                    <td><label class="text-danger bold">{{ $history->amount_btc }}</label><label class="text-primary"><i class="fa fa-btc"></i></label></td>
                                    <td><label class="text-danger bold">{{ substr(($history->amount)*100/65, 0, 5) }}</label><label class="text-primary">$</label></td>
                                    <td><label class="text-danger bold">{{ substr(($history->amount)*100/35, 0, 5) }}</label><label class="text-primary">$</label></td>
                                    <td>{{ $history->btc_address }}</td>
                                    <td><a class="btn btn-sm btn-primary" href="{{ $history->url }}" target="_blank">Info</a>
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
