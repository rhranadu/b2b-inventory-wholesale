@extends('layouts.crud-master')
@section('title', 'Service Contracts')

@push('css')
    .table th, .table td{vertical-align:inherit;}
@endpush

@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">

                    @include('component.message')
                    @if (isset($service_contracts) && count($service_contracts) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed serviceContractsDataTable" id="serviceContractsDataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Title</th>
                                    <th class="text-center">Slug</th>
                                    <th class="text-center">Position</th>
                                    <th class="text-center">Icon</th>
                                    <th class="text-center">Details</th>
{{--                                    <th class="text-center">Created By</th>--}}
{{--                                    <th class="text-center">Updated By</th>--}}
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($service_contracts as $service_contract)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $service_contract->title }}</td>
                                        <td>{{ $service_contract->slug }}</td>
                                        <td>{{ $service_contract->position }}</td>
                                        <td>{{ $service_contract->icon }}</td>
                                        <td>{{ $service_contract->details }}</td>
{{--                                        <td>{{ isset($service_contract->createdBy->name)? $service_contract->createdBy->name : 'N/A' }}</td>--}}
{{--                                        <td>{{ isset($service_contract->updatedBy->name)? $service_contract->updatedBy->name : 'N/A' }}</td>--}}
                                        <td>{{ date('d F Y h:i:s A',strtotime($service_contract->created_at)) }}</td>
                                        <td>
                                            <div class="btn-group">

                                                <a href="{{ route('superadmin.service_contracts.edit', $service_contract->id) }}"
                                                   class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip"
                                                   data-placement="auto" title="" data-original-title="EDIT"><i
                                                        class="fa fa-edit"></i> </a>
                                                <a href="#0" class="btn btn-sm btn-danger waves-effect btn-icon"
                                                   onclick="deleteServiceContracts({{ $service_contract->id }})"
                                                   data-toggle="tooltip" data-placement="auto"
                                                   data-original-title="DELETE"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>

                                            <form style="display: none" method="POST" id="deleteServiceContractsForm-{{ $service_contract->id }}"
                                                  action="{{ route('superadmin.service_contracts.destroy', $service_contract->id) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {!! $service_contracts->links() !!}
                    @else
                        <div class="alert alert-custom alert-outline-2x alert-outline-warning fade show mb-5">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text h4 mb-0">No data found</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--begin::Container-->
    </div>
    <!--begin::Entry-->

@endsection

@push('script')

    <script>
        $(".alert").delay(5000).slideUp(300);
        // delete
        function deleteServiceContracts(id) {
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
                    event.preventDefault();
                    document.getElementById('deleteServiceContractsForm-' + id).submit();
                }
            })
        }

    </script>

@endpush
