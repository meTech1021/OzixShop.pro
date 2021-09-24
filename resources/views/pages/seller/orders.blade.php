@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/seller/sales.js') }}"></script>
@endsection

@section('page_content')
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    My Orders
    </h3>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <h4 class="text-primary">Please understand that each tool you sell will be pending for <strong>24 hours</strong> before we pay you in order to give the buyer a chance to report it if it was bad.</h4>
                <p>
                    <h4 class="bold">Your sales statics.</h4>
                    <ul>
                        <li><label>All Sales : </label> <label class="bold text-primary">{{ count($sales) }}</label> <label class="text-danger bold">( {{ $total_sales }}$ )</label></li>
                    </ul>
                </p>
            </div>
        </div>
    </div>
    <div class="clearfix">
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <label class="panel-title bold">My Orders</label>
                </div>
                <div class="panel-body">
                    <table class="table table-stripped table-bordered table-hover" id="order_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Item</th>
                                <th>Open</th>
                                <th>Price</th>
                                <th>Report ID</th>
                                <th>Report State</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sale['id'] }}</td>
                                    <td>{{ $sale['type'] }}</td>
                                    <td>{{ $sale['url'] }}</td>
                                    <td><button class="btn btn-sm btn-primary" type="button" sale_id="{{ $sale['id'] }}">Open</button></td>
                                    <td><label class="text-danger bold">{{ $sale['price'] }}</label><label class="text-primary">$</label></td>
                                    <td>
                                        @if (empty($sale['report_id']))
                                            <label>N/A</label>
                                        @else
                                            <button type="button" class="btn btn-sm btn-success" report_id="{{ $sale['report_id'] }}">Report {{ $sale['report_id'] }}</button>
                                        @endif
                                    </td>
                                    <td>
                                        <label class="badge badge-danger">{{ $sale['report_state'] }}</label>
                                    </td>
                                    <td>{{ $sale['created_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
