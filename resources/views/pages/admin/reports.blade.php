@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/admin/reports.js') }}"></script>
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Reports
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-clock font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Pending Reports</span> <span class="badge badge-danger" id="report_cnt"> {{ count($reports) }} </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="report_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Buyer</th>
                                    <th>Seller</th>
                                    <th>Type</th>
                                    <th>Created Date</th>
                                    <th>Order ID</th>
                                    <th>Order Price</th>
                                    <th>Report State</th>
                                    <th>Last Reply</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr class="report_tr" report_id="{{ $report->id }}" style="cursor: pointer;">
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->user }}</td>
                                        <td>{{ $report->sellername }}</td>
                                        <td>{{ $report->acctype }}</td>
                                        <td>{{ $report->created_at }}</td>
                                        <td>{{ $report->order_id }}</td>
                                        <td>
                                            <label class="text-danger bold">{{ $report->price }}</label><label class="text-primary">$</label>
                                        </td>
                                        <td>
                                            @if ($report->status == 0)
                                                <label class="text-danger bold"><i class="fa fa-times-circle"></i> Closed</label>
                                            @elseif($report->status == 1 || $report->status == 2)
                                                <label class="text-primary bold"><i class="fa fa-check-circle"></i> Peding</label>
                                            @endif
                                        </td>
                                        <td>{{ $report->last_reply }}</td>
                                        <td>{{ $report->updated_at }}</td>
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
