@extends('layout.main')

@section('page_css')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_js')
<script>
    var rdps = {{ $rdps }};
    var shells = {{ $shells }};
    var cpanels = {{ $cpanels }};
    var mailers = {{ $mailers }};
    var smtps = {{ $smtps }};
    var leads = {{ $leads }};
    var accounts = {{ $accounts }};
    var scams = {{ $scams }};
    var tutorials = {{ $tutorials }};
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="{{ asset('js/buyer/dashboard.js') }}"></script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Dashboard <small>statistics & reports</small></h1>
        </div>
        <!-- END PAGE TITLE -->
    </div>
</div>
<!-- END PAGE HEAD -->
<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
    <div class="container">
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <label class="panel-title bold">Hello</label> @<label class="bold">{{ Auth::user()->name }}</label>
                    </div>
                    <div class="panel-body">
                         <p>
                            If you have any Question ,Problem, Suggestion or Request Please feel free to Open a
                            <a href="{{ url('/ticket') }}" class="btn btn-sm btn-primary">New Ticket <i class="fa fa-paper-plane"></i></a>
                         </p>
                         <p>
                            if you want to report an order , just go to My Orders then click on <a class="label label-danger">Report #[Order Id]</a> button.
                         </p>
                         <p>
                            Our Domains are | ozix.pro | Please Save them!
                         </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="caption">
                            <label class="panel-title bold">Account Details</label>
                        </div>
                    </div>
                    <div class="panel-body" id="order_details">
                        <div class="row static-info">
                            <div class="col-md-12">
                                 Balance : <span class="badge badge-roundless badge-danger" id="balance_span">{{ $balance }}</span>
                                 <a class="btn btn-xs btn-primary pull-right" href="{{ url('/balance') }}"><i class="fa fa-plus"></i> Add Funds</a>
                            </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-12">
                                 Orders : <span class="badge badge-roundless badge-success">{{ $orders }}</span>
                                 <a class="btn btn-xs btn-primary pull-right" href="{{ url('/orders') }}"><i class="fa fa-eye"></i> Show</a>
                            </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-12">
                                 Tickets: <span class="badge badge-roundless badge-danger">{{ $ticket_cnt }}</span>
                                 <a class="btn btn-xs btn-primary pull-right" href="{{ url('/ticket') }}"><i class="fa fa-eye"></i> Show</a>
                            </div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-12">
                                 Reports : <span class="badge badge-roundless badge-danger">{{ $report_cnt }}</span>
                                 <a class="btn btn-xs btn-primary pull-right" href="{{ url('/report') }}"><i class="fa fa-eye"></i> Show</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="caption">
                            <label class="panel-title bold">Our Support team is here !</label>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Interested in becoming a <strong>Seller</strong> <i class="fa fa-bitcoin"></i> at Ozix Shop ?</p>
                                <button class="btn btn-sm blue" type="button" data-toggle="modal" href="#LearnModal">Become a Seller <i class="fa fa-bitcoin"></i></button>
                            </div>
                        </div>
                        <div class="row margin-top-10">
                            <div class="col-md-12">
                                <p>Available Payment Methods</p>
                                <a href="{{ url('/balance') }}">
                                    <img src="{{ asset('imgs/btc.png') }}">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row margin-top-40">
            <div class="col-md-7">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <label class="panel-title bold"><i class="icon-grid"></i> Our News</label>
                    </div>
                    <div class="panel-body" id="news">
                        @if (count($news) > 0)
                            @foreach ($news as $new)
                            <div class="note note-success">
                                <h4 class="block bold">{{ $new->title }} (<label><i class="fa fa-clock-o"></i> {{ $new->created_at }}</label>)</h4>
                                <p>
                                    {{ $new->content }}
                                </p>
                            </div>
                        @endforeach
                            @else
                            <p>There is no news.</p>
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <label class="panel-title bold"><i class="icon-pie-chart"></i> Our Stuff</label>
                    </div>
                    <div class="panel-body" id="stuff">
                        <div id="donutchart" style="width: 100%;height:350px;"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="LearnModal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h3 class="modal-title"> Interested in becoming a seller at Ozix Shop ?</h3>
                    </div>
                    <div class="modal-body" style="padding: 30px 4%!important;">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Okay then let us explain your Features and tell you some rules .</p>
                                <h4 class="bold text-danger"><i class="fa fa-globe"></i> Rules</h4>
                                <ul>
                                    <li>You get 65% of your sales via bitcoin <i class="fa fa-bitcoin"></i> </li>
                                    <li>You get paid any time you like no delay !</li>
                                    <li>You can sell any tool you like with no limit and choose your price! </li>
                                    <li>You will have your own Panel with tons of options </li>
                                </ul>
                                <h4 class="bold text-danger"><i class="fa fa-info-circle"></i> Our Rules</h4>
                                <ul>
                                    <li>All the tools have to be Fresh and not used or sold before .</li>
                                    <li>Don't try XSS/ Sql Injection /add (minus) price and buy it - it will not work</li>
                                    <li>Do not put links of other stores (e-mails) or any ads</li>
                                </ul>
                                <h4 class="bold text-danger"><i class="fa fa-list-alt"></i> How to Apply ?</h4>
                                <ul>
                                    <li><a class="label label-primary" href="{{ url('/ticket') }}" data-nsfw-filter-status="swf"><span class="glyphicon glyphicon-pencil" data-nsfw-filter-status="swf"></span> Open a Ticket</a>  with a title of <abbr title="in order to reply faster !">seller request</abbr></li>
                                    <li>Make sure to mention what tools you have and include a mass example for each.</li>
                                    <li>Make sure all of your tools work before sending them to us.</li>
                                    <li>We should reply to you as soon as we see the request !</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT -->

@endsection
