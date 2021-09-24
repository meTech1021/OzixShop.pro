@extends('layout.admin_main')

@section('page_css')
<link href="{{ asset('css/myreports.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_js')
<script src="{{ asset('js/seller/myreports.js') }}"></script>
@endsection

@section('page_content')
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
        Reports
    </h3>
    <!-- END PAGE HEADER-->
    <div class="rwow">
        <div class="col-md-12">
            <div class="tabbable-custom ">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a href="#tab_static" data-toggle="tab">
                        Static </a>
                    </li>
                    <li>
                        <a href="#tab_pending" data-toggle="tab">
                        Pending </a>
                    </li>
                    <li>
                        <a href="#tab_all" data-toggle="tab">
                        All Reports </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_static">
                        <div class="portlet light">
                            <div class="portlet-body" id="rules">
                                <h3 class="bold text-danger">Rules</h3>
                                <ul>
                                    <li><b>Main Rules</b></li>
                                        <ul>
                                            <li>Always be nice with the buyer , no matter what happen never use bad language.</li>
                                            <li>Try to understand and solve buyer issue.</li>
                                            <li>You can replace any order , unless buyer asks for a refund.</li>
                                            <li>For lessons or tutorials you can use anydesk or teamviewer.</li>
                                            <li>Be careful with wired links and wired files / Use VPN.</li>
                                            <li>If the report was fake/incorrect , please add a reply and explain why.</li>
                                            <li>You are not allowed to force the buyer to close the report, you have to ask him politely to do that.</li>
                                            <li>Support/Admin is always here to help you , please contact us on tickets if you have any issue.</li>
                                        </ul>
                                    <li><b>Replacing Rules</b></li>
                                        <ul>
                                            <li><b>Always</b> include screenshot of the item</li>
                                            <li><b>Never</b> replace with already sold item / or replace multiple orders with the same item.</li>
                                            <li>if the replace is already added to your account make sure to <b class="text-danger">remove</b>  it.</li>
                                        </ul>
                                    <li>There are <b class="text-primary">{{ count($pending_reports) }}</b> Pending Report</li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_pending">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-hover" id="pending_report_table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Created Date</th>
                                            <th>Order ID</th>
                                            <th>Order Price </th>
                                            <th>Last Reply</th>
                                            <th>Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pending_reports as $pending_report)
                                            <tr class="report_tr" report_id="{{ $pending_report->id }}" style="cursor: pointer;">
                                                <td>{{ $pending_report->id }}</td>
                                                <td>{{ $pending_report->acctype }}</td>
                                                <td>{{ $pending_report->created_at }}</td>
                                                <td>
                                                    @if (empty($pending_report->order_id))
                                                        @php
                                                            $order_id = 'N/A';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $order_id = $pending_report->order_id;
                                                        @endphp
                                                    @endif
                                                    {{ $order_id }}
                                                </td>
                                                <td>
                                                    <label class="text-primary">$</label><label class="text-danger bold">{{ $pending_report->price }}</label>
                                                </td>
                                                <td>
                                                    {{ $pending_report->last_reply }}
                                                </td>
                                                <td>
                                                    @if (empty($pending_report->updated_at))
                                                        @php
                                                            $last_updated = 'N/A';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $last_updated = $pending_report->updated_at;
                                                        @endphp
                                                    @endif
                                                    {{ $last_updated }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_all">
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-striped table-hover" id="report_table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Created Date</th>
                                            <th>Order ID</th>
                                            <th>Order Price </th>
                                            <th>Report State</th>
                                            <th>Last Reply</th>
                                            <th>Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reports as $report)
                                            <tr class="report_tr" report_id="{{ $report->id }}" style="cursor: pointer;">
                                                <td>{{ $report->id }}</td>
                                                <td>{{ $report->acctype }}</td>
                                                <td>{{ $report->created_at }}</td>
                                                <td>
                                                    @if (empty($report->order_id))
                                                        @php
                                                            $order_id = 'N/A';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $order_id = $report->order_id;
                                                        @endphp
                                                    @endif
                                                    {{ $order_id }}
                                                </td>
                                                <td>
                                                    <label class="text-primary">$</label><label class="text-danger bold">{{ $report->price }}</label>
                                                </td>
                                                <td>
                                                    @php
                                                        switch ($report->status){
                                                            case "0" :
                                                            $st = "Closed";
                                                            $text_color = 'text-danger';
                                                            break;
                                                            case "1" :
                                                            $st = "Pending";
                                                            $text_color = 'text-primary';
                                                            break;
                                                            case "2":
                                                            $st = "Pending";
                                                            $text_color = 'text-success';
                                                            break;
                                                        }
                                                    @endphp
                                                    <label class="{{ $text_color }}">{{ $st }}</label>
                                                </td>
                                                <td>
                                                    {{ $report->last_reply }}
                                                </td>
                                                <td>
                                                    @if (empty($report->updated_at))
                                                        @php
                                                            $last_updated = 'N/A';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $last_updated = $report->updated_at;
                                                        @endphp
                                                    @endif
                                                    {{ $last_updated }}
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
@endsection
