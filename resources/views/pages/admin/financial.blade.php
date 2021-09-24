@extends('layout.admin_main')

@section('page_content')
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
        Financial Status
    </h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN DASHBOARD STATS -->
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    <i class="fa fa-usd"></i>
                </div>
                <div class="details">
                    <div class="number">
                            {{ $todayTotal }}$
                    </div>
                    <div class="desc">
                        <label class="bold">Total Deposit ( Today )</label>
                    </div>
                </div>
                <a class="more" href="javascript:;">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    <i class="fa fa-usd"></i>
                </div>
                <div class="details">
                    <div class="number">
                            {{ $monthTotal }}$
                    </div>
                    <div class="desc">
                        <label class="bold">Total Deposit ( Month )</label>
                    </div>
                </div>
                <a class="more" href="javascript:;">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    <i class="fa fa-usd"></i>
                </div>
                <div class="details">
                    <div class="number">
                            {{ $total }}$
                    </div>
                    <div class="desc">
                        <label class="bold">Total Deposit ( Beginning )</label>
                    </div>
                </div>
                <a class="more" href="javascript:;">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple-plum">
                <div class="visual">
                    <i class="fa fa-btc"></i>
                </div>
                <div class="details">
                    <div class="number">
                        0
                    </div>
                    <div class="desc">
                        <label class="bold">BTC</label>
                    </div>
                </div>
                <a class="more" href="javascript:;">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- END DASHBOARD STATS -->
@endsection
