@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Brand Wise Due Report')
@push('css')
@endpush
@section('main_content')
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    @include('component.message')
                    <div class="row align-items-center">
                        <div class="form-group col-md-4">
                            <label for="#">Brand</label>
                            <select name="brand" id="brand" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col-md-1">
                            <button class="btn btn-danger mt-7" id="reset_btn" type="reset">Reset</button>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary mt-7" id="search">Search</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-hover table-bordered table-condensed"
                            id="datatable">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Sale Amount</th>
                                <th class="text-center">Total Payment</th>
                                <th class="text-center">Total Due</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).off('click', '#reset_btn').on('click', '#reset_btn', function() {
            $("select[name=brand]").val("").trigger("change");
        });
        $(".alert").delay(5000).slideUp(300);
        $.ajaxSetup({
            headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
        });
        $("#brand").select2({
            width: '100%',
            allowClear: true,
            ajax: {
                url: "{{ route("admin.brand.dropdown.list") }}",
                dataType: 'json',
                type: 'GET',
                data: function (params) {
                    return {
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                id: item.id,
                                text: item.name,
                            }
                        }),
                        pagination: {
                            more: (params.page * 10) < data.total
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search for a brand',
        });

        var datatable =   $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{route('admin.report.brand_wise.due.ajax')}}',
                type: "POST",
                data: function (d) {
                    d.brand_id = $('#brand :selected').val();
                },
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
                {data: 'brand', name: 'brand'},
                {data: 'final_price', name: 'final_price'},
                {data: 'total_payment', name: 'total_payment'},
                {data: 'due_amount', name: 'due_amount'},
            ],
            columnDefs: [{
                targets: '_all',
                defaultContent: 'N/A'
            }],
        });

        $('#search').on('click', function(e) {
            datatable.draw();
            e.preventDefault();
        });

    </script>
@endpush
