@extends('layout.main')

@section('page_js')
    <script src="{{ asset('js/buyer/account/marketing.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Accounts - Email Marketing</h1>
        </div>
        <!-- END PAGE TITLE -->
    </div>
</div>
<!-- END PAGE HEAD -->
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="note note-default">
                    <ul>
                        <li>For Any problem for account after buy just open report and seller will fix it or replace.</li>
                        <li>There is <b>{{ count($datas) }}</b> Accounts [Email Marketing] Available.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="portlet light">
            <div class="portlet-body">

                <form method="get" id="filter_form">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="bold">Hosting :</label>
                                <input type="text" class="form-control" id="infos">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="bold">Domain :</label>
                                <input type="text" class="form-control" id="domain">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="bold">Country :</label>
                                <select class="form-control" name="country" id="country">
                                    <option value=""></option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->country }}">{{ $country->country_full }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="bold">Seller :</label>
                                <select class="form-control select2me" name="seller" id="seller">
                                    <option value="all">All</option>
                                    @foreach ($sellers as $seller)
                                        <option value="{{ $seller->seller_id }}">seller {{ $seller->seller_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="bold">Min Price :</label>
                                <input type="text" class="form-control" id="min_price" name="min_price" placeholder="$ Min">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="bold">Max Price :</label>
                                <input type="text" class="form-control" id="max_price" name="max_price" placeholder="$ Max">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button type="button" class="btn green-meadow" style="margin-top: 25px;" id="btn_filter"><i class="fa fa-search"></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="account_table">
                            <thead>
                                <tr>
                                    <th width="15%">Website Domain</th>
                                    <th width="8%">Location</th>
                                    <th width="22%">Description</th>
                                    <th width="5%">Price</th>
                                    <th width="8%">Seller</th>
                                    <th width="8%">Source</th>
                                    <th width="15%">Proof (Screenshot)</th>
                                    <th width="5%">Buy</th>
                                    <th width="14%">Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $item)
                                    <tr>
                                        <td>
                                            @php
                                                $domain = parse_url($item->sitename, PHP_URL_HOST);
                                            @endphp
                                            {{ $domain }}
                                        </td>
                                        <td>
                                            <i class="flag-icon flag-icon-{{ strtolower($item->country)}}"></i>{{ $item->country }}
                                        </td>
                                        <td>{{ $item->infos }}</td>
                                        <td><label class="text-danger bold">{{ $item->price }}</label><label class="text-primary">$</label></td>
                                        <td>
                                            seller{{ $item->seller_id }}
                                        </td>
                                        <td>
                                            @if ($item->source == 'Hacked')
                                                <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                            @else
                                                <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                            @endif
                                        </td>
                                        <td><button class="btn btn-sm btn-primary btn_proof" type="button" screenshot="{{ $item->screenshot }}">View Proof</button></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn_buy" data_id="{{ $item->id }}">Buy</button>
                                        </td>
                                        <td>{{ $item->created_at }}</td>
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
