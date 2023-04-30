@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Vendor Expenses')
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">

                <div class="card-body">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" action="{{ route('admin.vendorexpenses.store') }}"
                                  accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                <div class="form-element-list">
                                    <div class="row">

                                        <hr>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label for="particulars">Particulars<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                                    <input class="form-control" id="particulars"
                                                           value=""
                                                           autocomplete="off" name="particulars" type="text">
                                                    @error('particulars')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class=" ic-cmp-int">
                                                    <label for="pay_amount">Amount<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                                    <input class="form-control" id="pay_amount"
                                                           value=""
                                                           autocomplete="off" name="pay_amount" type="number" min="0">
                                                    @error('pay_amount')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-mail"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label for="#">Expense Date<span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                                                    <input required name="expense_date" data-date="" data-date-format="DD MMMM YYYY"
                                                           type="date" class="form-control"
                                                           id="expense_date">
                                                    @error('expense_date')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                    </div>

                                </div>

                                <button type="submit" class="btn btn-success waves-effect">Submit</button>
                            </form>
                            <hr>
                                <div class="table-responsive" >
                                    <table class="table table-hover table-bordered table-condensed vendorExpenseDataTable"
                                           id="vendorExpenseDataTable">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Particulars</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center notexport">Action</th>
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
        </div>
    </div>
@endsection

@push('script')
    <script>
        $().ready(function () {

            $(".alert").delay(5000).slideUp(300);
            $.ajaxSetup({
                headers: {'X-CSRF-Token': '{{ csrf_token() }}'}
            });
            var vendorExpenseDataTable =   $('#vendorExpenseDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('admin.vendorexpenses.ajax')}}',
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
                    {data: 'expense_date', name: 'expense_date'},
                    {data: 'particulars', name: 'particulars'},
                    {data: 'pay_amount', name: 'pay_amount'},
                    {data: 'action', name: 'action'},
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: 'N/A'
                }],
            });

            $('#search').on('click', function(e) {
                vendorExpenseDataTable.draw();
                e.preventDefault();
            });

        });


        {{--$('body').on('click', '#payment_method_delete', function () {--}}
        {{--    var val_id = $(this).data('id');--}}
        {{--    if(val_id != ''){--}}
        {{--        var request = $.ajax({--}}
        {{--            type: 'post',--}}
        {{--            url:  '{{url('admin/payment_method/destroy')}}',--}}
        {{--            data: {--}}
        {{--                'val_id': val_id,--}}
        {{--            },--}}
        {{--            dataType: "json"--}}
        {{--        });--}}
        {{--        request.done(function (response) {--}}
        {{--            if (response.success == true){--}}
        {{--                $("#refreshbody").load(location.href + " #refreshbody>*", "");--}}
        {{--                toastr.success('Payment Method deleted success');--}}
        {{--                location.reload();--}}
        {{--            }--}}
        {{--        });--}}
        {{--    }--}}
        {{--});--}}

        function deleteVendorExpense(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButtonColor: '#00c292',
                    cancelButton: 'btn btn-danger mt-0'
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! ⚠️",
                type: 'warning',
                cancelButtonColor: "#AF0000",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    // toastr.success('Warehouse deleted success');
                    event.preventDefault();
                    document.getElementById('deleteVendorExpenseForm-' + id).submit();
                }
            })
        }
    </script>
@endpush
