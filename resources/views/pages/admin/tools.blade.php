@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/admin/tools.js') }}"></script>
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Visualize Tools
</h3>
<!-- END PAGE HEADER-->
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_rdp" data-toggle="tab">
                            RDPs <span class="badge badge-danger"> {{ count($rdps) }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab_shell" data-toggle="tab">
                                Shells <span class="badge badge-danger"> {{ count($shells) }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab_cpanel" data-toggle="tab">
                            cPanels <span class="badge badge-danger"> {{ count($cpanels) }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab_mailer" data-toggle="tab">
                                Mailers <span class="badge badge-danger"> {{ count($mailers) }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab_smtp" data-toggle="tab">
                            SMTPs <span class="badge badge-danger"> {{ count($smtps) }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab_lead" data-toggle="tab">
                                Leads <span class="badge badge-danger"> {{ count($leads) }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab_account" data-toggle="tab">
                                Accounts <span class="badge badge-danger"> {{ count($accounts) }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab_scam" data-toggle="tab">
                            Scampages <span class="badge badge-danger"> {{ count($scams) }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab_tutorial" data-toggle="tab">
                                Tutorials <span class="badge badge-danger"> {{ count($tutorials) }} </span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_rdp">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" id="rdp_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Country</th>
                                                <th>City</th>
                                                <th>Source</th>
                                                <th>Hosting</th>
                                                <th>RAM</th>
                                                <th>Seller</th>
                                                <th>Item Information</th>
                                                <th>Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rdps as $rdp)
                                            <tr>
                                                <td>{{ $rdp->id }}</td>
                                                <td title="{{ $rdp->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($rdp->country) }}"></i> {{ $rdp->country }}</td>
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
                                                <td>{{ $rdp->sellername }}</td>
                                                <td>{{ $rdp->url }}</td>
                                                <td><label class="text-danger bold">{{ $rdp->price }}</label><label class="text-primary">$</label></td>
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
                        <div class="tab-pane" id="tab_shell">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="shell_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Country</th>
                                                <th>Source</th>
                                                <th>SSL</th>
                                                <th>URL</th>
                                                <th>Seller</th>
                                                <th>Price</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shells as $shell)
                                                <tr>
                                                    <td>{{ $shell->id }}</td>
                                                    <td title="{{ $shell->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($shell->country) }}"></i> {{ $shell->country }}</td>
                                                    <td>
                                                        @if ($shell->source == 'Hacked')
                                                            <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                                        @else
                                                            <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($shell->ssl_status == 'HTTPS')
                                                            <label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>
                                                        @else
                                                            <label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>
                                                        @endif
                                                    </td>
                                                    <td>{{ $shell->url }}</td>
                                                    <td>{{ $shell->sellername }}</td>
                                                    <td><label class="text-danger bold">{{ $shell->price }}</label><label class="text-primary">$</label></td>
                                                    <td>{{ $shell->created_at }}</td>
                                                    <td>
                                                        @if ($shell->sold == 0)
                                                            <button type="button" class="btn btn-sm btn-danger btn_remove" shell_id="{{ $shell->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                        @elseif($shell->sold == 1)
                                                            <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                        @elseif($shell->sold == 2)
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
                        <div class="tab-pane" id="tab_cpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="cpanel_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Type</th>
                                                <th>Country</th>
                                                <th>Source</th>
                                                <th>SSL</th>
                                                <th>Information</th>
                                                <th>Seller</th>
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
                                                    <td>{{ $cpanel->sellername }}</td>
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
                        <div class="tab-pane" id="tab_mailer">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="mailer_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Country</th>
                                                <th>Source</th>
                                                <th>SSL</th>
                                                <th>Information</th>
                                                <th>Seller</th>
                                                <th>Price</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mailers as $mailer)
                                                <tr>
                                                    <td>{{ $mailer->id }}</td>
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
                                                    <td>{{ $mailer->sellername }}</td>
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
                        <div class="tab-pane" id="tab_smtp">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="smtp_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Type</th>
                                                <th>Country</th>
                                                <th>Source</th>
                                                <th>Item Information</th>
                                                <th>Seller</th>
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
                                                    <td>{{ $smtp->sellername }}</td>
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
                        <div class="tab-pane" id="tab_lead">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="lead_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Type</th>
                                                <th>Country</th>
                                                <th>Description</th>
                                                <th>Email Number</th>
                                                <th>Price</th>
                                                <th>Seller</th>
                                                <th>Proof</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leads as $lead)
                                                <tr>
                                                    <td>{{ $lead->id }}</td>
                                                    <td><label>{{ $lead->acctype }}</label></td>
                                                    <td title="{{ $lead->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($lead->country) }}"></i> {{ $lead->country }}</td>
                                                    <td><label>{{ $lead->infos }}</label></td>
                                                    <td>{{ $lead->number }}</td>
                                                    <td><label class="text-danger bold">{{ $lead->price }}</label><label class="text-primary">$</label></td>
                                                    <td>{{ $lead->sellername }}</td>
                                                    <td><button class="btn btn-sm btn-primary btn_proof" type="button" screenshot="{{ $lead->screenshot }}">Proof</button></td>
                                                    <td>{{ $lead->created_at }}</td>
                                                    <td>
                                                        @if ($lead->sold == 0)
                                                            <button type="button" class="btn btn-sm btn-danger btn_remove" lead_id="{{ $lead->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                        @elseif($lead->sold == 1)
                                                            <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                        @elseif($lead->sold == 2)
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
                        <div class="tab-pane" id="tab_account">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="account_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Site Name</th>
                                                <th>Country</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Source</th>
                                                <th>Seller</th>
                                                <th>Proof</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accounts as $account)
                                                <tr>
                                                    <td>{{ $account->id }}</td>
                                                    <td>{{ $account->sitename }}</td>
                                                    <td title="{{ $account->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($account->country) }}"></i> {{ $account->country }}</td>
                                                    <td><label>{{ $account->infos }}</label></td>
                                                    <td><label class="text-danger bold">{{ $account->price }}</label><label class="text-primary">$</label></td>
                                                    <td>
                                                        @if ($account->source == 'Hacked')
                                                            <label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>
                                                        @else
                                                            <label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>
                                                        @endif
                                                    </td>
                                                    <td>{{ $account->sellername }}</td>
                                                    <td><button class="btn btn-sm btn-primary btn_proof" type="button" screenshot="{{ $account->screenshot }}">Proof</button></td>
                                                    <td>{{ $account->created_at }}</td>
                                                    <td>
                                                        @if ($account->sold == 0)
                                                            <button type="button" class="btn btn-sm btn-danger btn_remove" account_id="{{ $account->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                        @elseif($account->sold == 1)
                                                            <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                        @elseif($account->sold == 2)
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
                        <div class="tab-pane" id="tab_scam">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="scam_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Country</th>
                                                <th>Scam Name</th>
                                                <th>Link</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Seller</th>
                                                <th>Proof</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($scams as $scam)
                                                <tr>
                                                    <td>{{ $scam->id }}</td>
                                                    <td title="{{ $scam->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($scam->country) }}"></i> {{ $scam->country }}</td>
                                                    <td>{{ $scam->scam_name }}</td>
                                                    <td>{{ $scam->url }}</td>
                                                    <td><label>{{ $scam->infos }}</label></td>
                                                    <td><label class="text-danger bold">{{ $scam->price }}</label><label class="text-primary">$</label></td>
                                                    <td>{{ $scam->sellername }}</td>
                                                    <td><button class="btn btn-sm btn-primary btn_proof" type="button" screenshot="{{ $scam->screenshot }}">Proof</button></td>
                                                    <td>{{ $scam->created_at }}</td>
                                                    <td>
                                                        @if ($scam->sold == 0)
                                                            <button type="button" class="btn btn-sm btn-danger btn_remove" scam_id="{{ $scam->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                        @elseif($scam->sold == 1)
                                                            <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                        @elseif($scam->sold == 2)
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
                        <div class="tab-pane" id="tab_tutorial">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-bordered" id="tutorial_table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Country</th>
                                                <th>Tutorial Name</th>
                                                <th>Link</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Seller</th>
                                                <th>Proof</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tutorials as $tutorial)
                                                <tr>
                                                    <td>{{ $tutorial->id }}</td>
                                                    <td title="{{ $tutorial->country_full }}"><i class="flag-icon flag-icon-{{ strtolower($tutorial->country) }}"></i> {{ $tutorial->country }}</td>
                                                    <td>{{ $tutorial->tutorial_name }}</td>
                                                    <td>{{ $tutorial->url }}</td>
                                                    <td><label>{{ $tutorial->infos }}</label></td>
                                                    <td><label class="text-danger bold">{{ $tutorial->price }}</label><label class="text-primary">$</label></td>
                                                    <td>{{ $tutorial->sellername }}</td>
                                                    <td><button class="btn btn-sm btn-primary btn_proof" type="button" screenshot="{{ $tutorial->screenshot }}">Proof</button></td>
                                                    <td>{{ $tutorial->created_at }}</td>
                                                    <td>
                                                        @if ($tutorial->sold == 0)
                                                            <button type="button" class="btn btn-sm btn-danger btn_remove" tutorial_id="{{ $tutorial->id }}"><i class="fa fa-trash"></i> Remove</button>
                                                        @elseif($tutorial->sold == 1)
                                                            <button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sold</button>
                                                        @elseif($tutorial->sold == 2)
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
    </div>
</div>

@endsection
