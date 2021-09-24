@extends('layout.admin_main')

@section('page_js')
<script src="{{ asset('js/seller/management/rdp.js') }}"></script>
@endsection

@section('page_css')
<link href="{{ asset('css/management.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    RDP Management
</h3>
<!-- END PAGE HEADER-->
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-screen-desktop font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Rdp Management</span>
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
                                RDP List
                                <span class="badge badge-danger" id="rdps_badge"> {{ count($rdps) }} </span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_static">
                            <div class="row">
                                <div class="col-md-12 rules">
                                    <h4 class="bold text-danger">Rules</h4>
                                    <ul>
                                        <li>Please only insert Working RDPs And Exactly <b>Ram</b> on it </li>
                                        <li>You can choose the price you want but the usual price Depend on Ram and Windows Type : example</li>
                                            <ul>
                                            <li>2003 User: <label class="bold text-danger">5 ~ 8</label><label class="text-primary">$</label></li>
                                            <li>2003 Admin: <label class="bold text-danger">7 ~ 10</label><label class="text-primary">$</label></li>
                                            <li>2008/2012 User: <label class="bold text-danger">6 ~ 10</label><label class="text-primary">$</label></li>
                                            <li>2008/2012 Admin: <label class="bold text-danger">10 ~ 15</label><label class="text-primary">$</label></li>
                                            </ul>
                                        <li>If you have mistaken or need to edit a tool just remove it and add it again  </li>
                                        <li><b>Deleted</b> mean that we have checked the rdp and didn't work with us so we simply deleted it </li>
                                    </ul>
                                    <h4 class="text-danger bold">Static</h4>
                                    <ul>
                                        <li>Number of RDPs : <b class="text-primary" id="rdp_cnt">{{ count($rdps) }}</b></li>
                                        <li>Unsold RDPs : <b class="text-primary" id="unsold_cnt">{{ $unsold_rdps_cnt }}</b></li>
                                        <li>Sold RDPs : <b class="text-primary">{{ $sold_rdps_cnt }}</b></li>
                                        <li>Deleted RDPs : <b class="text-primary" id="deleted_cnt">{{ $deleted_rdps_cnt }}</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_all">
                            <table class="table table-striped table-hover table-bordered" id="rdp_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th>Source</th>
                                        <th>Hosting</th>
                                        <th>RAM</th>
                                        <th>Item Information</th>
                                        <th>Price</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rdps as $rdp)
                                        <tr>
                                            <td>{{ $rdp->id }}</td>
                                            <td>{{ $rdp->acctype }}</td>
                                            <td title="{{ $rdp->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($rdp->country)}}"></i> {{ $rdp->country }}</td>
                                            <td>{{ $rdp->city }}</td>
                                            <td>
                                                @if ($rdp->source == 'Hacked')
                                                    <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                                @else
                                                    <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                                @endif
                                            </td>
                                            <td>{{ $rdp->infos }}</td>
                                            <td>{{ $rdp->ram }}</td>
                                            <td>{{ $rdp->url }}</td>
                                            <td><label class="text-danger bold">{{ $rdp->price }}</label><label class="text-primary">$</label></td>
                                            <td>{{ $rdp->created_at }}</td>
                                            <td>
                                                @if ($rdp->sold == 0)
                                                    <button type="button" class="btn btn-sm btn-danger btn_remove" rdp_id="{{ $rdp->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                @elseif($rdp->sold == 1)
                                                    <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                @elseif($rdp->sold == 2)
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
                <h4 class="modal-title bold text-danger"><i class="icon-screen-desktop font-red-sharp"></i> New RDP</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="rdp_form" class="form-horizontal" novalidate="novalidate">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Host/IP <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <input class="form-control" id="host" type="text" name="host" placeholder="0.0.0.0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Username <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <input name="username" type="text" id="username" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Password <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <input name="password" id="password" type="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Access <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <select class="form-control" name="access" id="access">
                                    <option value="Admin">ADMIN</option>
                                    <option value="User">USER</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Windows <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <select class="form-control" name="windows" id="windows">
                                    <option value="ME">ME</option>
                                    <option value="2000">2000</option>
                                    <option value="XP">XP</option>
                                    <option value="2003">2003</option>
                                    <option value="Vista">Vista</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="10">10</option>
                                    <option value="2008">2008</option>
                                    <option value="2012">2012</option>
                                    <option value="2016">2016</option>
                                    <option value="2019">2019</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">RAM <span class="required" aria-required="true">
                                * </span></label>
                            <div class="col-md-8">
                                <input name="ram" id="ram" type="text" class="form-control" placeholder="512MB/1GB/2GB">
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
                <h4 class="modal-title bold text-danger"><i class="icon-screen-desktop font-red-sharp"></i> New RDP</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="rdp_mass_form" class="form-horizontal" novalidate="novalidate">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Host/IP <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="mass_host" name="mass_host" placeholder="0.0.0.0|username|password"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Access <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <select class="form-control" name="mass_access" id="mass_access">
                                    <option value="Admin">ADMIN</option>
                                    <option value="User">USER</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Windows <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <select class="form-control" name="mass_windows" id="mass_windows">
                                    <option value="ME">ME</option>
                                    <option value="2000">2000</option>
                                    <option value="XP">XP</option>
                                    <option value="2003">2003</option>
                                    <option value="Vista">Vista</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="10">10</option>
                                    <option value="2008">2008</option>
                                    <option value="2012">2012</option>
                                    <option value="2016">2016</option>
                                    <option value="2019">2019</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">RAM <span class="required" aria-required="true">
                                * </span></label>
                            <div class="col-md-8">
                                <input name="mass_ram" id="mass_ram" type="text" class="form-control" placeholder="512MB/1GB/2GB">
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
