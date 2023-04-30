<!-- Search Engine End-->
<div class="modal fade" id="CategoryModal" role="dialog">
    <div class="modal-dialog modals-default">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h2 style="padding: 10px; border-bottom: 2px solid #dddd;">Category list</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered table-condensed">
                        <tbody id="modelTableBody">
                            <ul id="tree1">
                                <li>
                                    <a href="#" class="select-category" data-id=""> {{ "No Parent" }}</a>
                                </li>
                                @foreach($parents_categories as $parent_category)
                                    <li>
                                        <a href="#" class="select-category" data-id="{{ $parent_category->id }}"> {{ $parent_category->name }}</a>
                                        @if($parent_category->childes->count())
                                            @if(isset($productCategory))
                                                @include('product_categories.nested_category',['childes' => $parent_category->childes, 'productCategory' => $productCategory])                                        
                                            @else
                                                @include('product_categories.nested_category',['childes' => $parent_category->childes,])                                        
                                            @endif
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
