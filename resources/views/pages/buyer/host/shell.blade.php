@extends('layout.main')

@section('page_js')
<script src="{{ asset('js/buyer/host/shell.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Shell</h1>
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
                        <li>Click on check button before buy any shell to know if it's work or not.</li>
                        <li>There is <b>{{ count($shells) }}</b> shell Available.</li>
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
                                <label class="bold">TLD :</label>
                                <input type="text" class="form-control" id="tld">
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
                                <label class="bold">SSL :</label>
                                <select class="form-control select2me" name="ssl" id="ssl">
                                    <option value="all">All</option>
                                    <option value="HTTPS">HTTPS</option>
                                    <option value="HTTP">HTTP</option>
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
                        <table class="table table-striped table-bordered table-hover" id="shell_table">
                            <thead>
                                <tr>
                                    <th width="5%">Location</th>
                                    <th width="8%">SSL</th>
                                    <th width="8%">Source</th>
                                    <th width="5%">TLD</th>
                                    <th width="30%">Host Info</th>
                                    <th width="10%">Detect Hosting</th>
                                    <th width="5%">Price</th>
                                    <th width="5%">Seller</th>
                                    <th width="5%">Check</th>
                                    <th width="5%">Buy</th>
                                    <th width="14%">Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shells as $shell)
                                    <tr>
                                        <td>
                                            <i class="flag-icon flag-icon-{{ strtolower($shell->country)}}"></i>{{ $shell->country }}
                                        </td>
                                        <td>
                                            @if ($shell->ssl_status == 'HTTPS')
                                                <label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>
                                            @else
                                                <label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($shell->source == 'Hacked')
                                                <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                            @else
                                                <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $url = $shell->domain;
                                                $tld = explode(".", $url);
                                                $tld = $tld[count($tld)-1];
                                            @endphp
                                            .{{ $tld }}
                                        </td>
                                        <td>{!! $shell->infos !!}</td>
                                        <td>
                                            @if (empty($shell->hosting_detect))
                                                N/A
                                            @else
                                                {{ $shell->hosting_detect }}
                                            @endif
                                        </td>
                                        <td><label class="text-danger bold">{{ $shell->price }}</label><label class="text-primary">$</label></td>
                                        <td>
                                            seller{{ $shell->seller_id }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn_chk" shell_id="{{ $shell->id }}">Check</button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn_buy" data_id="{{ $shell->id }}">Buy</button>
                                        </td>
                                        <td>{{ $shell->created_at }}</td>
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
