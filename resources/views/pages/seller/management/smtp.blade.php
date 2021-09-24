@extends('layout.admin_main')

@section('page_js')
<script src="{{ asset('js/seller/management/smtp.js') }}"></script>
@endsection

@section('page_css')
<link href="{{ asset('css/management.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    SMTP Management
</h3>
<!-- END PAGE HEADER-->
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-mail-forward (alias) font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">SMTP Management</span>
                </div>
                <div class="tools">
                    <button type="button" class="btn btn-primary" id="btn_new"><i class="fa fa-plus"></i> New Add</button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_static" data-toggle="tab">
                            Static </a>
                        </li>
                        <li>
                            <a href="#tab_all" data-toggle="tab">
                                SMTP List
                                <span class="badge badge-danger" id="smtps_badge"> {{ count($smtps) }} </span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_static">
                            <div class="row">
                                <div class="col-md-12 rules">
                                    <h4 class="bold text-danger">Rules</h4>
                                    <ul>
                                        <li>Just insert Webmails normally and our checker will detect it </li>
                                        <li>You can choose the price you want but the usual price between <label class="text-danger bold">2</label><label class="text-primary">$</label> ~ <label class="bold text-danger">6</label><label class="text-primary">$</label></li>
                                        <li>If you have mistaken or need to edit a tool just remove it and add it again  </li>
                                        <li><b>Deleted</b> mean that the tools is not working !</li>
                                    </ul>
                                    <h4 class="text-danger bold">Static</h4>
                                    <ul>
                                        <li>Number of SMTPs : <b class="text-primary" id="smtp_cnt">{{ count($smtps) }}</b></li>
                                        <li>Unsold SMTPs : <b class="text-primary" id="unsold_cnt">{{ $unsold_cnt }}</b></li>
                                        <li>Sold SMTPs : <b class="text-primary">{{ $sold_cnt }}</b></li>
                                        <li>Deleted SMTPs : <b class="text-primary" id="deleted_cnt">{{ $deleted_cnt }}</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_all">
                            <table class="table table-striped table-hover table-bordered" id="smtp_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Country</th>
                                        <th>Source</th>
                                        <th>Item Information</th>
                                        <th>Price</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($smtps as $smtp)
                                        <tr>
                                            <td>{{ $smtp->id }}</td>
                                            <td>{{ $smtp->acctype }}</td>
                                            <td title="{{ $smtp->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($smtp->country) }}"></i> {{ $smtp->country }}</td>
                                            <td>
                                                @if ($smtp->source == 'Hacked')
                                                    <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                                @else
                                                    <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                                @endif
                                            </td>
                                            <td>{{ $smtp->url }}</td>
                                            <td><label class="text-danger bold">{{ $smtp->price }}</label><label class="text-primary">$</label></td>
                                            <td>{{ $smtp->created_at }}</td>
                                            <td>
                                                @if ($smtp->sold == 0)
                                                    <button type="button" class="btn btn-sm btn-danger btn_remove" smtp_id="{{ $smtp->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                @elseif($smtp->sold == 1)
                                                    <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                @elseif($smtp->sold == 2)
                                                    <button type="button" class="btn btn-sm purple">Deleted</button>
                                                @endif
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

<div class="modal fade" id="NewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-mail-forward (alias) font-red-sharp"></i> New SMTP</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="smtp_form" class="form-horizontal" novalidate="novalidate">

                    <div class="form-body">
                        <div class="alert alert-danger">
                            <label>Please put your SMTP's like this <b>[ Host|Port|Username|Password ]</b><br> Otherwise, none of them will work.</label>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">SMTP List <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="smtp_host" id="smtp_host" rows="3" placeholder="http://domain.com/Leafsmtp.php"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Source <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <select class="form-control" name="source" id="source">
                                    <option value="Hacked">Hacked</option>
                                    <option value="Created">Created</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Price <span class="required" aria-required="true">
                                * </span></label>
                            <div class="col-md-8">
                                <input name="price" id="price" type="number" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_save"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
