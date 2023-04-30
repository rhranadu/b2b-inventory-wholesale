@include('component.dataTable_resource')
<table class="table table-bordered table-hover" id="dueDataTable">
    <thead>
    <tr>
        <th>#</th>
        <th>Invoice No</th>
        <th>Customer name</th>
        <th>Time</th>
        <th>Item</th>
        <th>Total</th>
        <th>Paid</th>
        <th>Due</th>
        <th>Payment</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
    <tr>
        <th class="text-right" colspan="5">Total</th>
        <th class="text-right"></th>
        <th class="text-right"></th>
        <th class="text-right"></th>
    </tr>
    </tfoot>
</table>

<script>
    $().ready(function () {
        $(".alert").delay(5000).slideUp(300);
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        var dueDataTable =   $('#dueDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{url('admin/retail_sell/due_orders')}}',
                type: "POST",
            },
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                // Total over this page
                grandPageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                paidPageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                duePageTotal = api
                    .column( 7, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 5 ).footer() ).html(
                    '৳'+grandPageTotal +' '
                );
                $( api.column( 6 ).footer() ).html(
                    '৳'+paidPageTotal +' '
                );
                $( api.column( 7 ).footer() ).html(
                    '৳'+duePageTotal +' '
                );
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
                    download: 'open',
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
                {data: 'invoice_no', name: 'invoice_no'},
                {data: 'name', name: 'name'},
                {data: 'created_at', name: 'created_at'},
                {data: 'items', name: 'items'},
                {data: 'final_total', name: 'final_total'},
                {data: 'total_payment', name: 'total_payment'},
                {data: 'due_payment', name: 'due_payment'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [
                {defaultContent: 'N/A', targets: '_all',},
            ],
        });
    });

    $(document).on('click', '.btnPaymentDue', function() {
        $(this).closest('.modal').modal('hide');
        openPaymentModal($(this).data('id'));
    });
</script>
