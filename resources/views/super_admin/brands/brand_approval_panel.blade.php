@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Approve Brand')
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
                <form id="exportApproveBrnadsId" method="post" action="">
                    @csrf
                    <table class="table table-hover table-bordered table-condensed childBrandDataTable" id="childBrandDataTable">
                        <thead>
                        <tr>
                            <th class="text-center no-sort">
                                <div class=" form-check custom_checkbox">
                                    <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-brand">
                                    <label class="form-check-label" for="checkbox-brand"></label>
                                </div>
                            </th>
                            <th class="text-center">SI</th>
                            <th class="text-center">Brand Name</th>
                            <th class="text-center">Parent</th>
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
            <button type="button" style="display: none" class="btn btn-primary approveBrandSubmit" value="active"  >Approve</button>
            <button type="button" style="display: none" class="btn btn-primary disapproveBrandSubmit" value="deactive"  >Disapprove</button>
            <button type="button" style="display: none" class="btn btn-primary approveParentBrandSubmit" value="parent" >Approve As Parent</button>
            <button type="button" class="btn btn-primary mapParentBrandSubmit" value="parent" >Map Selected with Parent</button>
            <button type="button" style="display: none" class="btn btn-primary unmapParentBrandSubmit" value="parent" >UnMap from Selected Parent</button>
        </td>
    </div>
</div>

@include('super_admin.brands.modal.parent_brand_list_for_map')
@include('super_admin.brands.modal.create')

@endsection

@push('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".alert").delay(5000).slideUp(300);

    var childBrandDataTable =   $('#childBrandDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('superadmin.product_brand.child.ajax')}}',
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
            {data: 'parent_name', name: 'parent_name'},
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

    // $('#search').on('click', function(e) {
    //     childBrandDataTable.draw();
    //     e.preventDefault();
    // });

    $(document).off('change', '.checkbox-all').on('change', '.checkbox-all', function () {
        if ($(this).is(':checked')) {
            $(this).closest('table').find('.checkbox-brand').each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $(this).closest('table').find('.checkbox-brand').each(function () {
                $(this).prop('checked', false);
            });
        }
    });
    $(document).off('click', '.approveBrandSubmit').on('click', '.approveBrandSubmit', function () {
        var  ischecked = $(".export-approve-brand").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-brand:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var brandsInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.brand.approval.status') }}",
                data: {
                    'brandsInPack': brandsInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var childBrandDataTable = $('#childBrandDataTable').dataTable();
                    childBrandDataTable.fnDraw(false);
                    toastr.success('Brand approve status updated success!');
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Brand Selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });
    $(document).off('click', '.disapproveBrandSubmit').on('click', '.disapproveBrandSubmit', function () {
        var  ischecked = $(".export-approve-brand").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-brand:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var brandsInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.brand.disapproval.status') }}",
                data: {
                    'brandsInPack': brandsInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var childBrandDataTable = $('#childBrandDataTable').dataTable();
                    childBrandDataTable.fnDraw(false);
                    toastr.success('Brand Disapprove status updated success!');
                }else{
                    toastr.error(response.msg);
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Brand Selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });

    $(document).off('click', '.approveParentBrandSubmit').on('click', '.approveParentBrandSubmit', function () {
        var  ischecked = $(".export-approve-brand").is(":checked");
        if (ischecked) {
            var val_arr = [];
            $('.export-approve-brand:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var brandsInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.brand.parent.approve.new') }}",
                data: {
                    'brandsInPack': brandsInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var childBrandDataTable = $('#childBrandDataTable').dataTable();
                    childBrandDataTable.fnDraw(false);
                    toastr.success('Brand Parent Updated success!');
                }
            });
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Brand selected',
                icon: 'warning',
                confirmButtonText: 'OK'
            })
            return;
        }
    });

    $(document).off('click', '.mapParentBrandSubmit').on('click', '.mapParentBrandSubmit', function () {
        var  ischecked = $(".export-approve-brand").is(":checked");
        if (ischecked) {
            $("#ParentBrandModal").modal('show');
            var parentBrandDataTable = $('#parentBrandDataTable').dataTable();
            parentBrandDataTable.fnDraw(false);
        }
        if (!ischecked) {
            Swal.fire({
                title: 'Warning!',
                text: 'No Brand selected',
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
            $('.export-approve-brand:checked').each(function(i){
                val_arr.push($(this).val());
            });
            var brandsInPack = val_arr;
            $.ajax({
                method: "POST",
                url: "{{ route('superadmin.product.brand.parent.unmap') }}",
                data: {
                    'brandsInPack': brandsInPack,
                },
            }).done(function(response) {
                if (response.success == true){
                    var childBrandDataTable = $('#childBrandDataTable').dataTable();
                    childBrandDataTable.fnDraw(false);
                    toastr.success('Parent Brand Unmaped Successfully!');
                }
            });
        }
            if (!ischecked) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'No Brand selected',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                })
                return;
            }
    });
    $(document).off('click', '.parentBrandCreate').on('click', '.parentBrandCreate', function () {
        $("#ParentBrandCreateModal").modal('show');
    });

    $(document).off('change', '.status_id').on('change', '.status_id', function (e) {
        var val = $(this).val();
        if (val == 1){
            $('.approveBrandSubmit').hide();
            $('.disapproveBrandSubmit').show();
            $('.unmapParentBrandSubmit').show();
            $('.mapParentBrandSubmit').hide();
        }else if (val == 2){
            $('.approveBrandSubmit').show();
            $('.unmapParentBrandSubmit').show();
            $('.mapParentBrandSubmit').hide();
            $('.disapproveBrandSubmit').hide();
        }else if(val == 3){
            $('.approveBrandSubmit').hide();
            $('.mapParentBrandSubmit').show();
            $('.unmapParentBrandSubmit').hide();
            $('.disapproveBrandSubmit').hide();
        }
        childBrandDataTable.draw();
        e.preventDefault();
    });
    $(document).off('click', '.vendor_id').on('click', '.vendor_id', function (e) {
        childBrandDataTable.draw();
        e.preventDefault();
    });

</script>
@endpush
