@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/admin/news.js') }}"></script>
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    News Management
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <label class="panel-title bold"><i class="icon-grid"></i> Buyer News</label>
                <button class="btn btn-sm blue pull-right" type="button" id="btn_buyer_new"><i class="fa fa-plus"></i> Add</button>
            </div>
            <div class="panel-body" id="news">
                <table class="table table-striped table-hover table-bordered" id="bnew_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($buyer_news as $bnew)
                            <tr>
                                <td>{{ $bnew->id }}</td>
                                <td>{{ $bnew->created_at }}</td>
                                <td>{{ $bnew->title }}</td>
                                <td>{{ substr($bnew->content, 0, 50) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger btn_delete" news_id="{{ $bnew->id }}">
                                        <i class="fa fa-trash"></i> delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <label class="panel-title bold"><i class="icon-grid"></i> Seller News</label>
                <button class="btn btn-sm btn-warning pull-right" type="button" id="btn_seller_new"><i class="fa fa-plus"></i> Add</button>
            </div>
            <div class="panel-body" id="news">
                <table class="table table-striped table-hover table-bordered" id="snew_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($seller_news as $snew)
                            <tr>
                                <td>{{ $snew->id }}</td>
                                <td>{{ $snew->created_at }}</td>
                                <td>{{ $snew->title }}</td>
                                <td>{{ substr($snew->content, 0, 50) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger btn_delete" news_id="{{ $snew->id }}">
                                        <i class="fa fa-trash"></i> delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="NewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="fa fa-edit font-red-sharp"></i> Edit News</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="news_form" novalidate="novalidate">
                    <input type="hidden" id="type">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">News Title <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <input type="text" class="form-control" name="title" id="title">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Content <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <textarea class="form-control" name="content" id="content" rows="5"></textarea>
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
