@extends('layout.admin_main')

@section('page_js')
<script src="{{ asset('js/seller/management/cpanel.js') }}"></script>
@endsection

@section('page_css')
<link href="{{ asset('css/management.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    cPanel Management
</h3>
<!-- END PAGE HEADER-->
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-tasks font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">cPanel Management</span>
                </div>
                <div class="tools">
                    <button type="button" class="btn btn-primary" id="btn_new"><i class="fa fa-plus"></i> New Add</button>
                    <button type="button" class="btn btn-primary" id="btn_mass_new"><i class="fa fa-plus"></i> New Mass Add</button>
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
                                cPanel List
                                <span class="badge badge-danger" id="cpanels_badge"> {{ count($cpanels) }} </span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_static">
                            <div class="row">
                                <div class="col-md-12 rules">
                                    <h4 class="bold text-danger">Rules</h4>
                                    <ul>
                                        <li>Please choose a source of your cpanels</li>
                                        <ul>
                                          <li><b>Carded or Created</b>: Created by you with a random empty domain and etc (even if the server is hacked it's still created)</li>
                                          <li><b>Hacked</b>: hacked cpanel.</li>
                                        </ul>

                                        <li>You can choose the price you want but the usual price between <label class="text-danger bold">2</label><label class="text-primary">$</label> ~ <label class="bold text-danger">6</label><label class="text-primary">$</label></li>
                                        <li>If you have mistaken or need to edit a tool just remove it and add it again  </li>
                                        <li><b>Deleted</b> mean that the tools is not working !</li>
                                      </ul>
                                    <h4 class="text-danger bold">Static</h4>
                                    <ul>
                                        <li>Number of cPanels : <b class="text-primary" id="cpanel_cnt">{{ count($cpanels) }}</b></li>
                                        <li>Unsold cPanels : <b class="text-primary" id="unsold_cnt">{{ $unsold_cnt }}</b></li>
                                        <li>Sold cPanels : <b class="text-primary">{{ $sold_cnt }}</b></li>
                                        <li>Deleted cPanels : <b class="text-primary" id="deleted_cnt">{{ $deleted_cnt }}</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_all">
                            <table class="table table-striped table-hover table-bordered" id="cpanel_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Country</th>
                                        <th>Source</th>
                                        <th>SSL</th>
                                        <th>Information</th>
                                        <th>Price</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cpanels as $cpanel)
                                        <tr>
                                            <td>{{ $cpanel->id }}</td>
                                            <td>{{ $cpanel->acctype }}</td>
                                            <td title="{{ $cpanel->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($cpanel->country) }}"></i> {{ $cpanel->country }}</td>
                                            <td>
                                                @if ($cpanel->source == 'Hacked')
                                                    <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                                @else
                                                    <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($cpanel->ssl_status == 'HTTPS')
                                                    <label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>
                                                @else
                                                    <label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>
                                                @endif
                                            </td>
                                            <td>{{ $cpanel->url }}</td>
                                            <td><label class="text-danger bold">{{ $cpanel->price }}</label><label class="text-primary">$</label></td>
                                            <td>{{ $cpanel->created_at }}</td>
                                            <td>
                                                @if ($cpanel->sold == 0)
                                                    <button type="button" class="btn btn-sm btn-danger btn_remove" cpanel_id="{{ $cpanel->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                @elseif($cpanel->sold == 1)
                                                    <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                @elseif($cpanel->sold == 2)
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
                <h4 class="modal-title bold text-danger"><i class="fa fa-tasks font-red-sharp"></i> New cPanel</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="cpanel_form" class="form-horizontal" novalidate="novalidate">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Host/IP <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <input class="form-control" id="cpanel_host" type="text" name="cpanel_host" placeholder="https://website.com:2083" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Username <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <input name="cpanel_username" type="text" id="cpanel_username" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Password <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <input name="cpanel_password" id="cpanel_password" type="password" class="form-control">
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

<div class="modal fade" id="NewMassModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-tasks font-red-sharp"></i> New cPanel</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="cpanel_mass_form" class="form-horizontal" novalidate="novalidate">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">cPanel List <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="cpanel_mass_host" id="cpanel_mass_host" placeholder="http://domain.com:2020/|username|password" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Source <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <select class="form-control" name="mass_source" id="mass_source">
                                    <option value="Hacked">Hacked</option>
                                    <option value="Created">Created</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Price <span class="required" aria-required="true">
                                * </span></label>
                            <div class="col-md-8">
                                <input name="mass_price" id="mass_price" type="number" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_mass_save"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
