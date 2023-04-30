@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Approve Category')
@push('css')

@endpush
@section('main_content')

<!--begin::Entry-->
<div class="card card-custom min-h-500px" id="kt_card_1">

    <div class="card-body">

        @include('component.message')
            <div class="row align-items-center">
                <div class="form-group col-md-3">
                    <label for="#">Vendor Name</label>
                    <select name="vendor_id" id="vendor_id" class="selectpicker form-control vendor_id" data-live-search="true">
                        <option value="">*Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" >{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="#">Status</label>
                    <select name="status_id" id="status_id" class="form-control status_id" data-live-search="true">
                        <option value="">All</option>
                        <option value="1" >Approved And Mapped</option>
                        <option value="2" >Mapped But Not Approved</option>
                        <option value="3" >Not Mapped and not Approved</option>
                    </select>
                </div>
{{--                <div class="form-group col-md-3">--}}
{{--                    <label for="#"></label>--}}
{{--                    <button type="submit" class="btn btn-primary mt-7 " id="search">Search</button>--}}
{{--                </div>--}}
            </div>
            <div class="table-responsive ">
                <form id="exportApproveCategoriesId" method="post" action="">
                    @csrf
                    <table class="table table-hover table-bordered table-condensed childCategoryDataTable" id="childCategoryDataTable">
                        <thead>
                        <tr>
                            <th class="text-center no-sort">
                                <div class=" form-check custom_checkbox">
                                    <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-category">
                                    <label class="form-check-label" for="checkbox-category"></label>
                                </div>
                            </th>
                            <th class="text-center">SI</th>
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Parent Name</th>
                            <th class="text-center">Vendor</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Is Approved</th>
                        </tr>
                        </thead>
                        <tbody>



                        </tbody>
                    </table>
                </form>
            </div>
        <td colspan="12">
            <button type="button" style="display: none" class="btn btn-primary approveCategorySubmit" value="active"  >Approve</button>
            <button type="button" style="display: none" class="btn btn-primary disapproveCategorySubmit" value="deactive"  >Disapprove</button>
            <button type="button" style="display: none" class="btn btn-primary approveParentCategorySubmit" value="parent" >Approve As Parent</button>
            <button type="button" class="btn btn-primary mapParentCategorySubmit" value="parent" >Map Selected with Parent</button>
            <button type="button" style="display: none" class="btn btn-primary unmapParentCategorySubmit" value="parent" >UnMap from Selected Parent</button>
        </td>
    </div>
</div>

@include('super_admin.categories.modal.parent_category_list_for_map')
@include('super_admin.categories.modal.create')

@endsection

@push('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".alert").delay(5000).slideUp(300);

    var childCategoryDataTable =   $('#childCategoryDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('superadmin.product_category.child.ajax')}}',
            type: "POST",
            data: function (d) {
                d.vendor_id = $('#vendor_id').val();
                d.status_id = $('#status_id').val();
            }
        },
        dom:'Blfrtip',
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10', '25', '50', '100', 'All' ]
        ],
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                text: 'Excel',
                exportOptions: {
                    columns: ':not(.notexport)'
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                text: 'Pdf',
                exportOptions: {
                    columns: ':not(.notexport)'
                }
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-light-primary btn-md btn-clean font-weight-bold font-size-base mr-1',
                exportOptions: {
                    columns: ':not(.notexport)'
                }
            }
        ],
        columns: [
            {data: 'checkbox', name: 'checkbox'},
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'parent.name', name: 'parent.name'},
            {data: 'vendor.name', name: 'vendor.name'},
            {data: 'status', name: 'status'},
            {data: 'is_approved', name: 'is_approved'},
        ],
        columnDefs: [
            {
                targets: '_all',
                defaultContent: 'N/A'
            },
            {
                targets: 'no-sort',
                orderable: false,
            },
        ],
        order: [[1, 'asc']]
    });

    $('#search').on('click', function(e) {
        childCategoryDataTable.draw();
        e.preventDefault();
    });

    $(document).off('change', '.checkbox-all').on('change', '.checkbox-all', function () {
        if ($(this).is(':checked')) {
            $(this).closest('table').find('.checkbox-category').each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $(this).closest('table').find('.checkbox-category').each(function () {
                $(this).prop('checked', false);
            });
        }
    });
    $(document).off('click', '.approveCategorySubmit').on('click', '.approveCategorySubmit', function () {
        var  ischecked = $(".export-approve-category").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-category:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var categoriesInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.category.approval.status') }}",
                data: {
                    'categoriesInPack': categoriesInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var childCategoryDataTable = $('#childCategoryDataTable').dataTable();
                    childCategoryDataTable.fnDraw(false);
                    toastr.success('Category approve status updated success!');
                }else{
                    toastr.error(response.msg);
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Category selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });
    $(document).off('click', '.disapproveCategorySubmit').on('click', '.disapproveCategorySubmit', function () {
        var  ischecked = $(".export-approve-category").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-category:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var categoriesInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.category.disapproval.status') }}",
                data: {
                    'categoriesInPack': categoriesInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var childCategoryDataTable = $('#childCategoryDataTable').dataTable();
                    childCategoryDataTable.fnDraw(false);
                    toastr.success('Category disapprove status updated success!');
                }else{
                    toastr.error(response.msg);
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Category selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });

    $(document).off('click', '.approveParentCategorySubmit').on('click', '.approveParentCategorySubmit', function () {
        var  ischecked = $(".export-approve-category").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-category:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var categoriesInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{route('superadmin.product.category.parent.approve.new')}}",
                data: {
                    'categoriesInPack': categoriesInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var childCategoryDataTable = $('#childCategoryDataTable').dataTable();
                    childCategoryDataTable.fnDraw(false);
                    toastr.success('Category Parent Updated success!');
                }
                if (response.success == false){
                    toastr.error(response.msg);
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Category selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });

    $(document).off('click', '.mapParentCategorySubmit').on('click', '.mapParentCategorySubmit', function () {
        var  ischecked = $(".export-approve-category").is(":checked");
        if (ischecked) {
            $("#ParentCategoryModal").modal('show');
            var parentCategoryDataTable = $('#parentCategoryDataTable').dataTable();
            parentCategoryDataTable.fnDraw(false);
            {{--var url = "{{route('superadmin.product.category.parent.for.map')}}"--}}
            {{--$('#exportApproveCategoriesId').attr('action', url);--}}
            {{--$('#exportApproveCategoriesId').submit();--}}
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Category selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });
    $(document).off('click', '.unmapParentBrandSubmit').on('click', '.unmapParentBrandSubmit', function () {
        var  ischecked = $(".export-approve-brand").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-category:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var categoryInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.category.parent.unmap') }}",
                data: {
                    'categoryInPack': categoryInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var childCategoryDataTable = $('#childCategoryDataTable').dataTable();
                    childCategoryDataTable.fnDraw(false);
                    toastr.success('Parent Category Unmaped Successfully!');
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Category selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });
    $(document).off('click', '.parentCategoryCreate').on('click', '.parentCategoryCreate', function () {
            $("#ParentCategoryCreateModal").modal('show');
    });
    $(document).off('change', '.status_id').on('change', '.status_id', function (e) {
        var val = $(this).val();
        if (val == 1){
            $('.approveCategorySubmit').hide();
            $('.disapproveCategorySubmit').show();
            $('.unmapParentCategorySubmit').show();
            $('.mapParentCategorySubmit').hide();
        }else if (val == 2){
            $('.approveCategorySubmit').show();
            $('.unmapParentCategorySubmit').show();
            $('.mapParentCategorySubmit').hide();
            $('.disapproveCategorySubmit').hide();
        }else if(val == 3){
            $('.approveCategorySubmit').hide();
            $('.mapParentCategorySubmit').show();
            $('.unmapParentCategorySubmit').hide();
            $('.disapproveCategorySubmit').hide();
        }
        childCategoryDataTable.draw();
        e.preventDefault();
    });
    $(document).off('change', '.vendor_id').on('change', '.vendor_id', function (e) {
        childCategoryDataTable.draw();
        e.preventDefault();
    });

</script>
@endpush
