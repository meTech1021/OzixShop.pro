@extends('layout.admin_main')

@section('page_js')
<script src="{{ asset('js/seller/management/mailer.js') }}"></script>
@endsection

@section('page_css')
<link href="{{ asset('css/management.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    PHP Mailer Management
</h3>
<!-- END PAGE HEADER-->
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-leaf font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">PHP Mailer Management</span>
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
                                Mailer List
                                <span class="badge badge-danger" id="mailers_badge"> {{ count($mailers) }} </span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_static">
                            <div class="row">
                                <div class="col-md-12 rules">
                                    <h4 class="bold text-danger">Rules</h4>
                                    <ul>
                                        <li>We only accept <b>Leaf PHPMailer</b> script <a href="http://www.leafmailer.pw/">Download Here</a></li>
                                        <li>NEW !! <b>Please add password to your mailer before adding  / then add it as http://domain.com/mailer.php?pass=YOURPASSWORD</b> </li>

                                        <li>Please check your mailer before add it / and make sure it's 100% sending !!</li>
                                        <li>if your mailer have a password add it as http://domain.com/mailer.php?<b>pass=YOURPASSWORD</b></li>
                                        <li>You can choose the price you want but the usual price between <label class="text-danger bold">2</label><label class="text-primary">$</label> ~ <label class="bold text-danger">6</label><label class="text-primary">$</label></li>
                                        <li>If you have mistaken or need to edit a tool just remove it and add it again  </li>
                                        <li><b>Deleted</b> mean that the tools is not working !</li>
                                    </ul>
                                    <h4 class="text-danger bold">Static</h4>
                                    <ul>
                                        <li>Number of Mailers : <b class="text-primary" id="mailer_cnt">{{ count($mailers) }}</b></li>
                                        <li>Unsold Mailers : <b class="text-primary" id="unsold_cnt">{{ $unsold_cnt }}</b></li>
                                        <li>Sold Mailers : <b class="text-primary">{{ $sold_cnt }}</b></li>
                                        <li>Deleted Mailers : <b class="text-primary" id="deleted_cnt">{{ $deleted_cnt }}</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_all">
                            <table class="table table-striped table-hover table-bordered" id="mailer_table">
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
                                    @foreach ($mailers as $mailer)
                                        <tr>
                                            <td>{{ $mailer->id }}</td>
                                            <td>{{ $mailer->acctype }}</td>
                                            <td title="{{ $mailer->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($mailer->country) }}"></i> {{ $mailer->country }}</td>
                                            <td>
                                                @if ($mailer->source == 'Hacked')
                                                    <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                                @else
                                                    <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($mailer->ssl_status == 'HTTPS')
                                                    <label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>
                                                @else
                                                    <label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>
                                                @endif
                                            </td>
                                            <td>{{ $mailer->url }}</td>
                                            <td><label class="text-danger bold">{{ $mailer->price }}</label><label class="text-primary">$</label></td>
                                            <td>{{ $mailer->created_at }}</td>
                                            <td>
                                                @if ($mailer->sold == 0)
                                                    <button type="button" class="btn btn-sm btn-danger btn_remove" mailer_id="{{ $mailer->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                @elseif($mailer->sold == 1)
                                                    <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                @elseif($mailer->sold == 2)
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
                <h4 class="modal-title bold text-danger"><i class="fa fa-leaf font-red-sharp"></i> New PHP Mailer</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="mailer_form" class="form-horizontal" novalidate="novalidate">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Mailer List <span class="required" aria-required="true">
                            * </span>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="mailer_host" id="mailer_host" rows="3" placeholder="http://domain.com/LeafMailer.php"></textarea>
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
