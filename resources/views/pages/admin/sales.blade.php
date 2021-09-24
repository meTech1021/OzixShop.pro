@extends('layout.admin_main')

@section('page_css')
<link href="{{ asset('css/admin.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_js')
<script src="{{ asset('js/admin/sale.js') }}"></script>
@endsection

@section('page_content')

<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Last 300 Orders
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="note note-default" id="static">
            <h4 class="block bold">Sales Static</h4>
            <p>
                All Sales : <label class="text-success bold">{{ count($sales) }}</label> ( <label class="text-primary">$</label><label class="text-danger bold">{{ $total_sales }}</label> )
            </p>
        </div>
    </div>
</div>
<div class="clearfix">
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light tasks-widget">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-clock font-green-haze"></i>
                    <span class="caption-subject font-green-haze bold uppercase">Last Tickets</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered" id="sale_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Buyer</th>
                            <th>Seller</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Report ID</th>
                            <th>Report State</th>
                            <th>Date</th>
                            <th>Open</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($last_sales as $sale)
                            <tr>
                                <td>{{ $sale['id'] }}</td>
                                <td>{{ $sale['buyer'] }}</td>
                                <td>seller{{ $sale['seller_id'] }}</td>
                                <td>{{ $sale['type'] }}</td>
                                <td><label class="text-danger bold">{{ $sale['price'] }}</label><label class="text-primary">$</label></td>
                                <td>
                                    @if (empty($sale['report_id']))
                                        N/A
                                    @else
                                        <a class="btn btn-sm purple">{{ $sale['report_id'] }}</a>
                                    @endif
                                </td>
                                <td>
                                    {{ $sale['report_status'] }}
                                </td>
                                <td>{{ $sale['created_at'] }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary btn_open" sale_id="{{ $sale['id'] }}">Open</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
