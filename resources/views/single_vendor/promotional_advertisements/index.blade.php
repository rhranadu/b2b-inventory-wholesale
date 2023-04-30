@extends('layouts.crud-master')
@section('title', 'Promotional Ads')

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
                    @if (isset($promotional_ads) && count($promotional_ads) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-condensed promotionalAdsDataTable" id="promotionalAdsDataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">SI</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Slug</th>
                                    <th class="text-center">Position</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Link</th>
                                    <th class="text-center">Status</th>
{{--                                    <th class="text-center">Created By</th>--}}
{{--                                    <th class="text-center">Updated By</th>--}}
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($promotional_ads as $promotional_ad)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $promotional_ad->name }}</td>
                                        <td>{{ $promotional_ad->slug }}</td>
                                        <td>{{ $promotional_ad->position }}</td>
                                        <td>
                                            <img src="{{ asset($promotional_ad->image) }}" width="80px"
                                                 alt="">
                                        </td>
                                        <td>{{ $promotional_ad->link }}</td>
                                        <td>{!! $promotional_ad->status == 1 ? '<span class="badge badge-primary">Active</span>' : '<span class="badge badge-warning">Inactive</span>' !!}</td>
{{--                                        <td>{{ isset($promotional_ad->createdBy->name)? $promotional_ad->createdBy->name : 'N/A' }}</td>--}}
{{--                                        <td>{{ isset($promotional_ad->updatedBy->name)? $promotional_ad->updatedBy->name : 'N/A' }}</td>--}}
                                        <td>{{ date('d F Y h:i:s A',strtotime($promotional_ad->created_at)) }}</td>
                                        <td>
                                            <div class="btn-group">

                                                <a href="{{ route('admin.promotional_advertisement.edit', $promotional_ad->id) }}"
                                                   class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip"
                                                   data-placement="auto" title="" data-original-title="EDIT"><i
                                                        class="fa fa-edit"></i> </a>
                                                <a href="#0" class="btn btn-sm btn-danger waves-effect btn-icon"
                                                   onclick="deletePromotionalAds({{ $promotional_ad->id }})"
                                                   data-toggle="tooltip" data-placement="auto"
                                                   data-original-title="DELETE"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>

                                            <form style="display: none" method="POST" id="deletePromotionalAdsForm-{{ $promotional_ad->id }}"
                                                  action="{{ route('admin.promotional_advertisement.destroy', $promotional_ad->id) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {!! $promotional_ads->links() !!}
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
        // delete vendor
        function deletePromotionalAds(id) {
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
                    document.getElementById('deletePromotionalAdsForm-' + id).submit();
                }
            })
        }

    </script>

@endpush
