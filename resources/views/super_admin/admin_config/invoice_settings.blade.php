@extends('layouts.crud-master')
@include('component.dataTable_resource')
@section('title', 'Invoice Settings')

@push('css')
@endpush


@section('main_content')

    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @include('component.message')
                            @if (isset($invoice_settings))
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-condensed invoiceSettingsDataTable"
                                       id="invoiceSettingsDataTable">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Value</th>
                                        <th class="text-center notexport">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <td>{{ $invoice_settings->name }}</td>
                                    <td>{{ $invoice_settings->value }}</td>
                                    <td>
                                        <div class="btn-group">

                                            <a href="{{ route('superadmin.invoice_settings.edit', $invoice_settings->id) }}"
                                               class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip"
                                               data-placement="auto" title="" data-original-title="EDIT"><i
                                                    class="fa fa-edit"></i> </a>
                                            <a href="#0" class="btn btn-sm btn-danger waves-effect btn-icon"
                                               onclick="deleteInvoiceSettings({{ $invoice_settings->id }})"
                                               data-toggle="tooltip" data-placement="auto"
                                               data-original-title="DELETE"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>

                                        <form style="display: none" method="POST" id="deleteInvoiceSettingsForm-{{ $invoice_settings->id }}"
                                              action="{{ route('superadmin.invoice_settings.destroy', $invoice_settings->id) }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text h4 mb-0">No data found</div>
                                </div>
                            @endif
                            @if (!isset($invoice_settings))
                            <hr>
                                <div class="normal-table-list">
                                    <div class="bsc-tbl">
                                        <form method="POST" action="{{ route('superadmin.invoice_settings.store') }}" accept-charset="UTF-8"
                                              enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-group">
                                                <label for="name">Name <span
                                                        style="color: red; font-size: 20px;"><sup></sup></span></label>
                                                <input class="form-control" id="name" value="invoice_prefix" autocomplete="off"
                                                       name="name" type="text" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="value">Value (Max 3 Characters) <span
                                                        style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                                <input class="form-control" id="value" value="{{ old('value') }}" autocomplete="off"
                                                       name="value" type="text" minlength="1" maxlength="3" required pattern="[/\w{1,2}[-_]{1}|\w{1,3}/i]">
                                                @error('value')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                            <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn  waves-effect">
                                                Create
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
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


        });


        // delete brand
        function deleteInvoiceSettings(id) {
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
                    // toastr.success('Brand deleted success');
                    event.preventDefault();
                    document.getElementById('deleteInvoiceSettingsForm-' + id).submit();
                }
            })
        }


    </script>
@endpush
