<div class="table-responsive">
    <table class="table table-bordered table-hover" id="today_sales_datatable">
        <thead>
        <tr>
            <th>#</th>
            <th>Invoice No</th>
            <th>Time</th>
            <th>Item</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @php($grand_total = 0)
        @foreach($sales as $key => $sale)
            @php($grand_total += $sale->final_total)
            <tr>
                <td>{!! ($key+1) !!}</td>
                <td>{!! $sale->invoice_no !!}</td>
                <td>{!! date('h:i:s A', strtotime($sale->created_at)) !!}</td>
                <td>{!! $sale->items !!}</td>
                <td class="text-right">{!! number_format($sale->final_total, 2) !!}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th class="text-right" colspan="4">Total</th>
            <th class="text-right">{!! number_format($grand_total, 2) !!}</th>
        </tr>
        </tfoot>
    </table>
</div>
<script>
    var productReturnDataTable = $('#today_sales_datatable').DataTable({
        serverSide: false,
        dom: 'Blfrtip',
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
    });
</script>
