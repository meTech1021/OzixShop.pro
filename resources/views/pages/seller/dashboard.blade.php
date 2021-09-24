@extends('layout.admin_main')

@section('page_js')
<script src="{{ asset('js/seller/dashboard.js') }}"></script>
@endsection

@section('page_css')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_content')
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    Dashboard <small>reports & statistics</small>
    </h3>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-5 col-sm-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <label class="panel-title bold">Hello</label> @<label class="bold">{{ Auth::user()->name }}</label>
                </div>
                <div class="panel-body">
                     <p>
                        If you have any Question ,Problem, Suggestion or Request Please feel free to Open a
                        <button type="button" class="btn btn-sm btn-primary" id="btn_new_ticket">New Ticket <i class="fa fa-paper-plane"></i></button>
                     </p>
                     <p>
                        Your Information <span class="label label-lg label-warning">{{ Auth::user()->name }}</span>
                     </p>
                     <p>
                         <ul>
                             <li> Your selling nickname in this shop is <label class="text-primary bold"> seller{{ $seller->id }}</label></li>
                             <li>You get paid any time you like using Withdraw section</li>
                             <li>You get 65% of your sales</li>
                             <li>You can change your bitcoin address from withdrawal section</li>
                             <li>Your bitcoin address is:
                                @if (empty($seller->btc_address))
                                    <label class="text-danger bold"> N/A</label>
                                @else
                                    <label class="text-danger bold"> {{ $seller->btc_address }}</label>
                                @endif
                             </li>
                             <li> Your Warnings <label class="text-danger bold">0</label> </li>
                         </ul>
                     </p>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-7">
            <div class="portlet light tasks-widget">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-green-haze hide"></i>
                        <span class="caption-subject font-green-haze bold uppercase">Top Sellers</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="top_seller_table">
                        <thead>
                            <tr>
                                <th>
                                    <i class="fa fa-sort-amount-asc"></i> ID
                                </th>
                                <th>
                                    <i class="fa fa-user"></i> Seller
                                </th>
                                <th class="hidden-xs">
                                    <i class="fa fa-money"></i> Sales
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($top_sellers as $seller)
                                <tr>
                                    <td>{{ $seller->id }}</td>
                                    <td>{{ $seller->name }}</td>
                                    <td><label class="text-danger bold"> {{ $seller->sold_btc }} </label> <label class="text-primary">$</label></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix">
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <label class="panel-title bold"><i class="icon-grid"></i> Our News</label>
                </div>
                <div class="panel-body" id="news">
                    @if (count($news) > 0)
                        @foreach ($news as $new)
                        <div class="note note-primary">
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
    </div>

    <div class="modal fade" id="NewModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title bold text-danger"><i class="fa fa-edit font-red-sharp"></i> Insert Ticket</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:;" id="ticket_form" novalidate="novalidate">
                        <input type="hidden" id="type">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Ticket Title <span class="required" aria-required="true">
                                        * </span>
                                        </label>
                                        <input type="text" class="form-control" name="title" id="title">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Username <span class="required" aria-required="true">
                                        * </span>
                                        </label>
                                        <input type="text" class="form-control" name="username" id="username">
                                    </div>
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
