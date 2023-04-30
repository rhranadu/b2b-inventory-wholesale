@extends('layouts.app')
@include('component.dataTable_resource')
@section('title', 'Vendor User')

@push('css')
@endpush



@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">

        <div class="card-body">

            <div class="table-responsive">
                <table id="vendorUserDataTable" class="table table-striped vendorUserDataTable">
                    <thead>
                    <tr>
                        <th class="text-center">SI</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Vendor Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Country</th>
                        <th class="text-center">State/Division</th>
                        <th class="text-center">City</th>
                        <th class="text-center notexport">Image</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <!-- Data Table area End-->

@endsection

@push('script')
<script>
    $().ready(function () {

        $(".alert").delay(5000).slideUp(300);

        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        var vendorUserDataTable =   $('#vendorUserDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('superadmin.user.vendor.ajax')}}',
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
                {data: 'name', name: 'name'},
                {data: 'vendor.name', name: 'vendor.name'},
                {data: 'email', name: 'email'},
                {data: 'country.name', name: 'country.name'},
                {data: 'state_or_division.name', name: 'state_or_division.name'},
                {data: 'city.name', name: 'city.name'},
                {data: 'image', name: 'image'},
                {data: 'status', name: 'status'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],

        });

        $("table").on('click', '#ActiveUnactive', function () {
            var id = $(this).attr('data_id');
            var getStatus = $(this).attr('statusCode');
            var setStatus = (getStatus > 0) ? 0 : 1;
            $.ajax({
                url: "{{ route('superadmin.user.vendor.statusActiveUnactive') }}",
                type: "get",
                data: {setStatus: setStatus, id: id},
                success: function (res) {
                    if (res === 'true') {
                        // $(".userTable").load(location.href + " .userTable");
                        var vendorUserDataTable = $('#vendorUserDataTable').dataTable();
                        vendorUserDataTable.fnDraw(false);
                        toastr.success('User status changed!');
                    } else {
                        toastr.success('Not found !');
                    }
                }
            })
        })
    });
</script>
@endpush
