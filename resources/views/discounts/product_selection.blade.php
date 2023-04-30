
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-body">
                <div class="normal-table-list">
                    <div class="bsc-tbl">
                        <div class="row category-parent-element">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select id="category-1" class="form-control category-dropdown">
                                        <option value=""> Select Category </option>
                                        @foreach($parentCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" style="display:none">
                                <div class="form-group">
                                    <select id="category-2" class="form-control category-dropdown">
        
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" style="display:none">
                                <div class="form-group">
                                    <select id="category-3" class="form-control category-dropdown">
        
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" style="display:none">
                                <div class="form-group">
                                    <select id="category-4" class="form-control category-dropdown">
        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <ul class="list-inline selected-category-helper">
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-search icon-lg"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="input-product-search"
                                                placeholder="Search a product by title">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="btn-product-search">
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                    <span class="form-text text-muted">Search a product by title</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="d-flex align-items-center">
                                    <a data-toggle="tooltip" title="Map Selected with Parent" href="#"
                                        class="btn btn-light-info btn-sm btn-clean font-weight-bold font-size-base mr-1" style="display:none" id="btn_map_parent">
                                        <i class="fa fa-arrow-down"></i>Select For Discount
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card card-custom gutter-b" id="div-card-product-list" style="display:none">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="div-product-list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ModalChildProduct" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"
        aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Product Details</h4>
            </div>
            <div class="modal-body child_product_detail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>