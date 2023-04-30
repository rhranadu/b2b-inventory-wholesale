@extends('layouts.crud-master')
@section('title', 'External Pages')

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
                    @if (isset($external_pages) && count($external_pages) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed serviceContractsDataTable" id="serviceContractsDataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Title</th>
                                    <th class="text-center">Slug</th>
{{--                                    <th class="text-center">Description</th>--}}
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Created By</th>
                                    <th class="text-center">Updated By</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($external_pages as $external_page)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $external_page->title }}</td>
                                        <td>{{ $external_page->slug }}</td>
{{--                                        <td>{{ $external_page->descriptions }}</td>--}}
                                        <td>{{ $external_page->status == 1 ? 'Active' : 'InActive' }}</td>
                                        <td>{{ isset($external_page->createdBy->name)? $external_page->createdBy->name : 'N/A' }}</td>
                                        <td>{{ isset($external_page->updatedBy->name)? $external_page->updatedBy->name : 'N/A' }}</td>
                                        <td>{{ $external_page->created_at }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('superadmin.external_pages.edit', $external_page->id) }}"
                                                   class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip"
                                                   data-placement="auto" title="" data-original-title="EDIT"><i
                                                        class="fa fa-edit"></i> </a>
                                                <a href="#0" class="btn btn-sm btn-danger waves-effect btn-icon"
                                                   onclick="deleteContacts({{ $external_page->id }})"
                                                   data-toggle="tooltip" data-placement="auto"
                                                   data-original-title="DELETE"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                            <form style="display: none" method="POST" id="deleteContactsForm-{{ $external_page->id }}"
                                                  action="{{ route('superadmin.external_pages.destroy', $external_page->id) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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
        function deleteContacts(id) {
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
                    document.getElementById('deleteContactsForm-' + id).submit();
                }
            })
        }

    </script>

@endpush
