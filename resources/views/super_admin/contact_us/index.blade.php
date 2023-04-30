@extends('layouts.crud-master')
@section('title', 'Contact Us')

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
                    @if (isset($contact_us) && count($contact_us) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed serviceContractsDataTable" id="serviceContractsDataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Address</th>
                                    <th class="text-center">Social Links</th>
                                    <th class="text-center">Created By</th>
                                    <th class="text-center">Updated By</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contact_us as $contact)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $contact->phone }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->address }}</td>
                                        <td class="text-center">
                                            @if ($contact->social_links)
                                                @foreach($contact->social_links as $social_link)
                                                        <ul>
                                                            @if(isset($social_link['icon']))
                                                                <li>
                                                                    Icon - {{$social_link['icon']}} </br>
                                                                    Link - {{$social_link['link']}} </br>
                                                                    Position - {{$social_link['position']}}
                                                                </li>
                                                            @endif
                                                        </ul>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{ isset($contact->createdBy->name)? $contact->createdBy->name : 'N/A' }}</td>
                                        <td>{{ isset($contact->updatedBy->name)? $contact->updatedBy->name : 'N/A' }}</td>
                                        <td>{{ $contact->created_at }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('superadmin.contact_us.edit', $contact->id) }}"
                                                   class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip"
                                                   data-placement="auto" title="" data-original-title="EDIT"><i
                                                        class="fa fa-edit"></i> </a>
                                                <a href="#0" class="btn btn-sm btn-danger waves-effect btn-icon"
                                                   onclick="deleteContacts({{ $contact->id }})"
                                                   data-toggle="tooltip" data-placement="auto"
                                                   data-original-title="DELETE"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                            <form style="display: none" method="POST" id="deleteContactsForm-{{ $contact->id }}"
                                                  action="{{ route('superadmin.contact_us.destroy', $contact->id) }}">
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
