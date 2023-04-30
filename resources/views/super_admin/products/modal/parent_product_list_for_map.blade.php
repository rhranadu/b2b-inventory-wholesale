
<div class="modal fade " id="ParentProductModal" role="dialog">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Parent Products list</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="d-flex align-items-center">
                    <a
                        data-toggle="tooltip"
                        title="Add Parent Product"
                        target="_blank"
                        href="{{ route('superadmin.parent_product.create') }}"
                        class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1 parentProductCreate">
                        <i class="fa fa-plus"></i>Add Parent Product
                    </a>
                </div>
            </div>
            
            <div class="modal-body">

                <div class="table-responsive parentProductTable">
                    <form id="" method="" action="">
                        @csrf
                        <table class="table table-hover table-bordered table-condensed parentProductDataTable" id="parentProductDataTable">
                            <thead>
                            <tr>
                                <th class="text-center">SI</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </form>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    // set csrf token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var parentProductDataTable =   $('#parentProductDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route('superadmin.product.parent.ajax')}}',
            type: "POST",
        },
        dom:'Blfrtip',
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
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ],
        columnDefs: [
            {
                targets: '_all',
                defaultContent: 'N/A'
            }
        ],
    });

    $(document).off("click", ".parentProductSelect").on("click", ".parentProductSelect", function (){
        var val_product_id = $(this).data('parent_product_id');
        var val_arr = [];
        $('.export-approve-product:checked').each(function(i){
            val_arr.push($(this).val());
        });
        var productsInPack = val_arr;
        $.ajax({
            method: "POST",
            url: "{{ route('superadmin.product.parent.map') }}",
            data: {
                'parent_product_id': val_product_id,
                'productsInPack': productsInPack,
            },
        }).done(function(response) {
            if (response.success == true){
                $('#ParentProductModal').modal('hide');
                var childProductDataTable = $('#childProductDataTable').dataTable();
                childProductDataTable.fnDraw(false);
                toastr.success('Parent Product selected Successfully!');
                product_table.draw()
            }
        });
    })

</script>
@endpush
