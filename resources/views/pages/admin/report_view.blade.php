@extends('layout.admin_main')

@section('page_js')
    <script>
        var memo = `{!! $report->memo !!}`;
    </script>
    <script src="{{ asset('js/admin/report_view.js') }}"></script>
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Report # {{ $report->id }}
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-5">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-clock font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Report Reply</span>
                </div>
                @php
                    if($report->refunded == 'Refunded') {
                        $hide_class = '';
                    } else {
                        $hide_class = 'hide';
                    }
                @endphp
                <div class="actions {{ $hide_class }}" id="refund_div">
                    <span class="badge badge-danger badge-roundless">refunded</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row" style="padding-bottom: 10px;">
                    <div class="col-md-12 ">
                        <div class="margin-bottom-10" id="report_history"  style="max-height:600px; overflow-y: auto;">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form id="reply_form">
                            <div class="input-group">
                                <textarea type="password" class="form-control" name="reply" id="reply" rows="1"></textarea>
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-primary" id="btn_send" report_id="{{ $report->id }}"><i class="fa fa-paper-plane"></i> Reply</button>
                                </span>

                            </div>
                            <span class="help-block">( Please press <b>"Enter"</b> to send message and press <b class="bold">"Shift+Enter"</b> to next line)</span>
                        </form>
                    </div>
                </div>
            </div>
            @if ($report->refunded == 'Not Yet !')
            <hr>
            <div class="portlet-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger pull-right" id="btn_refund" report_id="{{ $report->id }}"><i class="fa fa-reply"></i> Close & Refund</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-7">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-clock font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Item Information</span>
                </div>
            </div>
            <div class="portlet-body">
                <form class="form-horizontal">
                    @if ($report->acctype == 'RDP')
                        @php
                            $host_info = explode('|', $rdp->url);
                        @endphp
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($rdp->country) }}"></i> {{ $rdp->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">City : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $rdp->city }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Host/IP : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $host_info[0] }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Username : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $host_info[1] }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Password : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $host_info[2] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Windows : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $rdp->windows }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Access : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $rdp->access }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">RAM : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $rdp->ram }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Detect Hosting : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $rdp->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Price : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static text-danger">{{ $rdp->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Shell')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($shell->country) }}"></i> {{ $shell->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Shell : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $shell->url }}" target="_blank">{{ $shell->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $shell->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'cPanel')
                        @php
                            $host_info = explode('|', $cpanel->url);
                        @endphp
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($cpanel->country) }}"></i> {{ $cpanel->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Detect Hosting : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">
                                                {{ $cpanel->infos }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">URL : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $host_info[0] }}" target="_blank">{{ $host_info[0] }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Username : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $host_info[1] }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Password : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static">{{ $host_info[2] }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-5 bold">Price : </h4>
                                        <div class="col-md-7">
                                            <h4 class="form-control-static text-danger">{{ $cpanel->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Mailer')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($mailer->country) }}"></i> {{ $mailer->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Mailer : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $mailer->url }}" target="_blank">{{ $mailer->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $mailer->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'SMTP')
                        @php
                            $host_info = explode('|', $smtp->url);
                        @endphp
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($smtp->country) }}"></i> {{ $smtp->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Host/IP : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $host_info[0] }}" target="_blank">{{ $host_info[0] }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Port : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $host_info[1] }}" target="_blank">{{ $host_info[1] }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Username : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $host_info[2] }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Password : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $host_info[3] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Email : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $host_info[2] }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $smtp->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Combo List')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($combo_list->country) }}"></i> {{ $combo_list->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Number : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $combo_list->number }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $combo_list->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Download : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $combo_list->screenshot }}" target="_blank">{{ $combo_list->screenshot }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $combo_list->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->type == '100% Email Checked List')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($checked_list->country) }}"></i> {{ $checked_list->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Number : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $checked_list->number }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $checked_list->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Download : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $checked_list->screenshot }}" target="_blank">{{ $checked_list->screenshot }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $checked_list->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Email List')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($email_list->country) }}"></i> {{ $email_list->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Number : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $email_list->number }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $email_list->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Download : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $email_list->screenshot }}" target="_blank">{{ $email_list->screenshot }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $email_list->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Hosting/Domain')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($hosting->country) }}"></i> {{ $hosting->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $hosting->sitename }}" target="_blank">{{ $hosting->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $hosting->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $hosting->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $hosting->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Games')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($game->country) }}"></i> {{ $game->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $game->sitename }}" target="_blank">{{ $game->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $game->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $game->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $game->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'VPN/Socks Proxy')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($vpn->country) }}"></i> {{ $vpn->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $vpn->sitename }}" target="_blank">{{ $vpn->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $vpn->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $vpn->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $vpn->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Shopping {Amazon, eBay .... etc }')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($shopping->country) }}"></i> {{ $shopping->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $shopping->sitename }}" target="_blank">{{ $shopping->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $shopping->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $shopping->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $shopping->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Stream { Music, Netflix, iptv, HBO, bein sport, WWE ...etc }')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($stream->country) }}"></i> {{ $stream->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $stream->sitename }}" target="_blank">{{ $stream->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $stream->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $stream->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $stream->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Dating')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($dating->country) }}"></i> {{ $dating->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $dating->sitename }}" target="_blank">{{ $dating->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $dating->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $dating->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $dating->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Marketing')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($marketing->country) }}"></i> {{ $marketing->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $marketing->sitename }}" target="_blank">{{ $marketing->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $marketing->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $marketing->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $marketing->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Learning { udemy, lynda, .... etc. }')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($learning->country) }}"></i> {{ $learning->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $learning->sitename }}" target="_blank">{{ $learning->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $learning->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $learning->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $learning->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Voip/Sip')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($voip->country) }}"></i> {{ $voip->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Website : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">
                                                <a href="{{ $voip->sitename }}" target="_blank">{{ $voip->sitename }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $voip->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Account info : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">{{ $voip->url }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $voip->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Exploit/Script/ScamPage')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($scam->country) }}"></i> {{ $scam->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Name : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $scam->scam_name }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $scam->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Download : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">
                                                <a href="{{ $scam->screenshot }}" target="_blank">{{ $scam->screenshot }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $scam->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($report->acctype == 'Tutorial / Method')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Country : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static"><i class="flag-icon flag-icon-{{ strtolower($tutorial->country) }}"></i> {{ $tutorial->country_full }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Name : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $tutorial->tutorial_name }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">About : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static">{{ $tutorial->infos }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-4 bold">Download : </h4>
                                        <div class="col-md-8">
                                            <h4 class="form-control-static">
                                                <a href="{{ $tutorial->screenshot }}" target="_blank">{{ $tutorial->screenshot }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="control-label col-md-3 bold">Price : </h4>
                                        <div class="col-md-9">
                                            <h4 class="form-control-static text-danger">{{ $tutorial->price }}<font class="text-primary">$</font></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
