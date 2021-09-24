@extends('layout.main')

@section('page_js')
<script src="{{ asset('js/buyer/send/mailer.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Mailers</h1>
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
                        <li>To Check The Mailer ( Deliver or not ) , Click on Send ( For Free ) Then Check Your Testing E-mail.</li>
                        <li>There is <b>{{ count($mailers) }}</b> Mailer Available.</li>
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
                                <label class="bold">Source :</label>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="bold">Test Email :</label>
                                <input type="text" class="form-control" id="test_email" name="test_email" value="{{ Auth::user()->test_email }}">

                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button class="btn green-meadow" style="margin-top: 25px;" id="btn_save" type="button">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="mailer_table">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="8%">Location</th>
                                    <th width="8%">SSL</th>
                                    <th width="8%">Source</th>
                                    <th width="22%">Hosting</th>
                                    <th width="5%">Price</th>
                                    <th width="8%">Seller</th>
                                    <th width="15%">Test Send <span class="badge badge-primary" style="text-transform: none;" id="test_email_span">{{ Auth::user()->test_email }}</span></th>
                                    <th width="5%">Buy</th>
                                    <th width="15%">Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mailers as $mailer)
                                    <tr>
                                        <td>{{ $mailer->id }}</td>
                                        <td>
                                            <i class="flag-icon flag-icon-{{ strtolower($mailer->country)}}"></i>{{ $mailer->country }}
                                        </td>
                                        <td>
                                            @if ($mailer->ssl_status == 'HTTPS')
                                                <label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>
                                            @else
                                                <label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($mailer->source == 'Hacked')
                                                <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                            @else
                                                <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                            @endif
                                        </td>
                                        <td>{{ $mailer->infos }}</td>
                                        <td><label class="text-danger bold">{{ $mailer->price }}</label><label class="text-primary">$</label></td>
                                        <td>
                                            seller{{ $mailer->seller_id }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn_chk" mailer_id="{{ $mailer->id }}">Check</button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn_buy" data_id="{{ $mailer->id }}">Buy</button>
                                        </td>
                                        <td>{{ $mailer->created_at }}</td>
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
