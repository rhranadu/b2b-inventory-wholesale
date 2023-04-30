@extends('layouts.crud-master')
@section('title', 'Country Details')

@section('main_content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Country Data Table</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">Country Details</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    data-toggle="tooltip"
                    title="Add Country"
                    href="{{route('admin.country.create')}}"
                    class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-plus"></i>Add Country
                </a>
                <a
                    data-toggle="tooltip"
                    title="Country List"
                    href="{{route('admin.country.index')}}"
                    class="btn btn-light-primary btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-list"></i>Country List
                </a>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="flex-column-fluid">
        <!--begin::Container-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container-fluid">
                <div class="card card-custom min-h-500px" id="kt_card_1">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered table-condensed">
                                <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $country->name }}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{ $country->createdBy->name }}</td>
                                </tr>
                                <tr>
                                    <td>Updated By</td>
                                    <td>{{ $country->updatedBy->name }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{ $country->created_at->diffForHumans() }}</td>
                                </tr>
                                <tr>
                                    <td>Updated At</td>
                                    <td>{{ $country->updated_at->diffForHumans() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('script')

@endpush
