@extends('layout.admin_main')

@section('page_js')
    <script src="{{ asset('js/admin/sellers.js') }}"></script>
@endsection

@section('page_content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
    Sellers
</h3>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-users font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Total Sellers</span> <span class="badge badge-danger" id="user_cnt"> {{ count($sellers) }} </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="seller_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Whole Sales</th>
                                    <th>Current Sales</th>
                                    <th>BTC Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sellers as $seller)
                                <tr>
                                    <td>{{ $seller->id }}</td>
                                    <td><label class="bold">{{ $seller->sellername }} (Seller{{ $seller->id }})</label></td>
                                    <td><label class="text-primary">$</label><label class="text-danger bold">{{ $seller->all_sales }}</label></td>
                                    <td><label class="text-primary">$</label><label class="text-danger bold">{{ $seller->sold_btc }}</label></td>
                                    <td>
                                        @if (empty($seller->btc_address))
                                            N/A
                                        @else
                                        <label class="text-muted bold">{{ $seller->btc_address }}</label></td>
                                        @endif

                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm btn_edit" seller_id="{{ $seller->id }}"><i class="fa fa-pencil"></i> Edit</button>
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

<div class="modal fade" id="NewModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold text-danger"><i class="icon-user font-red-sharp"></i> Profile</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="profile_form" novalidate="novalidate">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Username <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <input type="text" class="form-control" name="username" id="username" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Sold Balance <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <input type="number" class="form-control" name="sold_balance" id="sold_balance">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Unsold Balance <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <input type="number" class="form-control" name="unsold_balance" id="unsold_balance">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Items Sold <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <input type="number" class="form-control" name="items_sold" id="items_sold">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Items Unsold <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <input type="number" class="form-control" name="items_unsold" id="items_unsold">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Bitcoin Address <span class="required" aria-required="true">
                                    * </span>
                                    </label>
                                    <input type="text" class="form-control" name="btc_address" id="btc_address" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_save"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger" id="btn_delete"><i class="fa fa-trash"></i> Delete</button>
                <button type="button" class="btn purple" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
