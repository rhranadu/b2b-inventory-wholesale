@extends('layouts.app')
@include('component.dataTable_resource')
@section('title', 'Featured Products')

@push('css')
@endpush


@section('main_content')
    <!--Start View content-->

    <div class="card card-custom min-h-500px" id="kt_card_1">

        <div class="card-body">

            @include('component.message')
                <div class="table-responsive ">
                    <form id="exportFeaturedProductsId" method="post" action="">
                        @csrf
                        <input type="hidden" value="" id="active_deactive_status" name="active_deactive_status">
                    <table class="table table-hover table-bordered table-condensed featuredProductDataTable" id="featuredProductDataTable">
                        <thead>
                        <tr>
                            <th class="text-center no-sort">
                                <div class="form-check custom_checkbox text-center">
                                    <input class="form-check-input checkbox-all" type="checkbox" >
                                    <label class="form-check-label" for="checkbox-featured-product"></label>
                                </div>
                            </th>
                            <th class="text-center">SI</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Model</th>
                            <th class="text-center">Featured</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>



                        </tbody>
                    </table>
                    </form>
                </div>
            <td colspan="12">
                <button type="button" class="btn btn-primary featuredProductSubmit" value="active"  >Active</button>
                <button type="button" class="btn btn-primary featuredProductSubmit" value="deactive" >Deactive</button>
            </td>
        </div>
    </div>


@endsection

@push('script')
    <script>
        $(document).ready(function () {
            // set csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".alert").delay(5000).slideUp(300);

            $('.checkbox-all').change(function () {
                if ($(this).is(':checked')) {
                    $(this).closest('table').find('.checkbox-featured-product').each(function () {
                        $(this).prop('checked', true);
                    });
                } else {
                    $(this).closest('table').find('.checkbox-featured-product').each(function () {
                        $(this).prop('checked', false);
                    });
                }
            });


            var featuredProductDataTable =   $('#featuredProductDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('superadmin.product.featured.ajax')}}',
                    type: "POST",
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
                    {data: 'product_category.name', name: 'product_category.name'},
                    {data: 'product_model', name: 'product_model'},
                    {data: 'is_featured', name: 'is_featured'},
                    {data: 'status', name: 'status'},
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


            $(document).off('click', '.featuredProductSubmit').on('click', '.featuredProductSubmit', function () {
                var val = $(this).val();
                var  ischecked = $(".export-featured-product").is(":checked");
                if (ischecked) {
                    var val_arr = [];
                    $('.export-featured-product:checked').each(function(i){
                        val_arr.push($(this).val());
                    });
                    var featuredInPack = val_arr;
                    $.ajax({
                        method: "POST",
                        url: "{{ route('superadmin.product.featured.map') }}",
                        data: {
                            'featuredInPack': featuredInPack,
                            'val': val,
                        },
                    }).done(function(response) {
                        if (response.success == true){
                            var featuredProductDataTable = $('#featuredProductDataTable').dataTable();
                            featuredProductDataTable.fnDraw(false);
                            toastr.success('Featured product status updated success!');
                        }
                    });
                }
                if (!ischecked) {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'No Featured Product Selected',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    })
                    return;
                }
            });



    });


    </script>
@endpush
