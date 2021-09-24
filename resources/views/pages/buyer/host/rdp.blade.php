@extends('layout.main')

@section('page_js')
<script src="{{ asset('js/buyer/host/rdp.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>RDP</h1>
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
                        <li>Click on check button before buy any RDP to know if it's work or not.</li>
                        <li>There is <b>{{ count($rdps) }}</b> RDP Available.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="portlet light">
            <div class="portlet-body">

                <form method="get" id="filter_form" action="{{ url('/rdp/filter') }}">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="bold">Hosting :</label>
                                <input type="text" class="form-control" id="infos">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="bold">RAM :</label>
                                <input type="text" class="form-control" id="ram">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="bold">Windows :</label>
                                <select class="form-control select2me" id="windows" name="windows">
                                    <option value="all">All</option>
                                    @foreach ($windows as $window)
                                        <option value="{{ $window->windows }}">{{ $window->windows }}</option>
                                    @endforeach
                                </select>
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
                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="bold">Type :</label>
                                <select class="form-control select2me" name="source" id="source">
                                    <option value="all">All</option>
                                    <option value="Created">Created</option>
                                    <option value="Hacked">Hacked</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="bold">Access :</label>
                                <select class="form-control select2me" name="access" id="access">
                                    <option value="all">All</option>
                                    <option value="Admin">Admin</option>
                                    <option value="User">User</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
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
                                <input type="text" class="form-control" id="min_price" name="min_price" placeholder="$ Min" value="{{ old('min_price') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="bold">Max Price :</label>
                                <input type="text" class="form-control" id="max_price" name="max_price" placeholder="$ Max" value="{{ old('max_price') }}">
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
                        <table class="table table-striped table-bordered table-hover" id="rdp_table">
                            <thead>
                                <tr>
                                    <th width="5%">Location</th>
                                    <th width="8%">Hosting</th>
                                    <th width="8%">Username</th>
                                    <th width="10%">IP</th>
                                    <th width="8%">Source</th>
                                    <th width="5%">RAM</th>
                                    <th width="8%">Windows</th>
                                    <th width="5%">Access</th>
                                    <th width="5%">Price</th>
                                    <th width="5%">Seller</th>
                                    <th width="5%">Check</th>
                                    <th width="5%">Buy</th>
                                    <th width="14%">Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rdps as $rdp)
                                @php
                                    $infos = explode('|', $rdp->url);
                                @endphp
                                    <tr>
                                        <td>
                                            <i class="flag-icon flag-icon-{{ strtolower($rdp->country)}}"></i>{{ $rdp->country }}-{{ $rdp->country_full }}-{{ $rdp->city }}
                                        </td>
                                        <td>{{ $rdp->infos }}</td>
                                        <td>
                                            {{ substr($infos[1],0 , 2) }}*****
                                        </td>
                                        <td>
                                            @php
                                                $ip_arr = explode('.', $infos[0]);
                                            @endphp
                                            {{ $ip_arr[0] }}.{{ $ip_arr[1] }}.*.*
                                        </td>
                                        <td>
                                            @if ($rdp->source == 'Hacked')
                                                <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                            @else
                                                <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                            @endif
                                        </td>
                                        <td>{{ $rdp->ram }}</td>
                                        <td>{{ $rdp->windows }}</td>
                                        <td>{{ $rdp->access }}</td>
                                        <td><label class="text-danger bold">{{ $rdp->price }}</label><label class="text-primary">$</label></td>
                                        <td>
                                            seller{{ $rdp->seller_id }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn_chk" rdp_id="{{ $rdp->id }}">Check</button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn_buy" data_id="{{ $rdp->id }}">Buy</button>
                                        </td>
                                        <td>{{ $rdp->created_at }}</td>
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
