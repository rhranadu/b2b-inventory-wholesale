@extends('layouts.crud-master')
@section('title', 'Manufacturer Details')
@section('main_content')
    <?php $data_array = $manufacturer;?>
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Manufacturer Data Table</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">Manufacturer details</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    data-toggle="tooltip"
                    title="Add Manufacturer"
                    href="{{route('admin.manufacturer.create')}}"
                    class="btn btn-light-success btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-plus"></i>Add Manufacturer
                </a>
                <a
                    data-toggle="tooltip"
                    title="Manufacturer List"
                    href="{{route('admin.manufacturer.index')}}"
                    class="btn btn-light-primary btn-sm btn-clean font-weight-bold font-size-base mr-1">
                    <i class="fa fa-list"></i>Manufacturer List
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
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <!--begin::Details-->
                        <div class="d-flex mb-9">
                            <!--begin: Pic-->
                            <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                                <div class="image_square pop_img" data-img="{{ asset($data_array->image) }}">
                                    <img
                                        src="{{ asset($data_array->image) }}"
                                        alt="image">
                                </div>
                            </div>
                            <!--end::Pic-->
                            <!--begin::Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between flex-wrap mt-1">
                                    <div class="d-flex mr-3">
                                        <a
                                            href="#"
                                            class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $data_array->name }}</a>
                                        <a href="#">
                                            <i class="flaticon2-correct text-success font-size-h5"></i>
                                        </a>
                                    </div>
                                    <div class="my-lg-0 my-3">
                                        <a
                                            href="{{ route('admin.manufacturer.edit',$data_array->id) }}"
                                            class="btn btn-sm btn-light-success font-weight-bolder text-uppercase">
                                            <i class="fa fa-edit"></i>
                                            Edit
                                        </a>
                                    </div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Content-->
                                <div class="d-flex flex-wrap justify-content-between mt-1">
                                    <div class="d-flex flex-column flex-grow-1 pr-8">
                                        <div class="d-flex flex-wrap">
                                            <a
                                                href="mailto:{{ $data_array->email }}"
                                                class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                                <i class="flaticon2-new-email mr-2 font-size-lg"></i>{{ $data_array->email }}
                                            </a>
                                            <a
                                                href="#"
                                                class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                                <i class="flaticon2-calendar-3 mr-2 font-size-lg"></i>{{ $data_array->type }}
                                            </a>
                                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold">
                                                <i class="flaticon2-placeholder mr-2 font-size-lg"></i>{{ $data_array->address }}
                                            </a>
                                        </div>
                                        <div class="font-weight-bold text-dark-50 mb-3">
                                            <a
                                                target="_blank"
                                                href="{{ $data_array->website?:'www.website.com' }}">{{ $data_array->website?:'www.website.com' }}</a>
                                        </div>
                                        <span
                                            class="font-weight-bold text-dark-50">{{ $data_array->detail?:'This demo detail will replace if detail added...!' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center w-25 flex-fill float-right mt-lg-12 mt-8">
                                        <span class="font-weight-bold text-dark-75">Progress</span>
                                        <div class="progress progress-xs mx-3 w-100">
                                            <div
                                                class="progress-bar bg-success" role="progressbar" style="width: 63%;"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="font-weight-bolder text-dark">78%</span>
                                    </div>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                        <div class="separator separator-solid"></div>
                        <!--begin::Items-->
                        <div class="d-flex align-items-center flex-wrap mt-8">
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                                <span class="mr-4">
                                    <i class="flaticon-piggy-bank display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="d-flex flex-column text-dark-75">
                                    <span class="font-weight-bolder font-size-sm">Earnings</span>
                                    <span class="font-weight-bolder font-size-h5">
                                        <span class="text-dark-50 font-weight-bold">$</span>
                                        249,500
                                    </span>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                                <span class="mr-4">
                                    <i class="flaticon-confetti display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="d-flex flex-column text-dark-75">
                                    <span class="font-weight-bolder font-size-sm">Vendor Expenses</span>
                                    <span class="font-weight-bolder font-size-h5">
                                        <span class="text-dark-50 font-weight-bold">$</span>
                                        164,700
                                    </span>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                                <span class="mr-4">
                                    <i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="d-flex flex-column text-dark-75">
                                    <span class="font-weight-bolder font-size-sm">Net</span>
                                    <span class="font-weight-bolder font-size-h5">
                                        <span class="text-dark-50 font-weight-bold">$</span>
                                        782,300
                                    </span>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                                <span class="mr-4">
                                    <i class="flaticon-file-2 display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="d-flex flex-column flex-lg-fill">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">73 Tasks</span>
                                    <a href="#" class="text-primary font-weight-bolder">View</a>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                                <span class="mr-4">
                                    <i class="flaticon-chat-1 display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">648 Comments</span>
                                    <a href="#" class="text-primary font-weight-bolder">View</a>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mb-2 float-left">
                                <span class="mr-4">
                                    <i class="flaticon-network display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="symbol-group symbol-hover">
                                    <div
                                        class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title=""
                                        data-original-title="Mark Stone">
                                        <img alt="Pic" src="{{asset('assets/media/users/300_25.jpg')}}">
                                    </div>
                                    <div
                                        class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title=""
                                        data-original-title="Charlie Stone">
                                        <img alt="Pic" src="{{asset('assets/media/users/300_19.jpg')}}">
                                    </div>
                                    <div
                                        class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title=""
                                        data-original-title="Luca Doncic">
                                        <img alt="Pic" src="{{asset('assets/media/users/300_22.jpg')}}">
                                    </div>
                                    <div
                                        class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title=""
                                        data-original-title="Nick Mana">
                                        <img alt="Pic" src="{{asset('assets/media/users/300_23.jpg')}}">
                                    </div>
                                    <div
                                        class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title=""
                                        data-original-title="Teresa Fox">
                                        <img alt="Pic" src="{{asset('assets/media/users/300_18.jpg')}}">
                                    </div>
                                    <div class="symbol symbol-30 symbol-circle symbol-light">
                                        <span class="symbol-label font-weight-bold">5+</span>
                                    </div>
                                </div>
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--begin::Items-->


                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <!--begin::Charts Widget 4-->
                        <div class="card card-custom bg-radial-gradient-primary card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0 py-5">
                                <h3 class="card-title font-weight-bolder text-white">Sales Progress</h3>
                                <div class="card-toolbar">
                                    <div class="dropdown dropdown-inline">
                                        <a
                                            href="#" class="btn btn-text-white btn-hover-white btn-sm btn-icon border-0"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header font-weight-bold py-4">
                                                    <span class="font-size-lg">Choose Label:</span>
                                                    <i
                                                        class="flaticon2-information icon-md text-muted"
                                                        data-toggle="tooltip" data-placement="right"
                                                        title="Click to learn more..."></i>
                                                </li>
                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-success">
                                                                POS Customer
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-danger">
                                                                Partner
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-warning">
                                                                Suplier
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-primary">
                                                                Member
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span class="label label-xl label-inline label-light-dark">
                                                                Staff
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-separator mt-3 opacity-70"></li>
                                                <li class="navi-footer py-4">
                                                    <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                        <i class="ki ki-plus icon-sm"></i>
                                                        Add new
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column p-0">
                                <!--begin::Chart-->
                                <div id="kt_mixed_widget_5_chart" style="height: 200px"></div>
                                <!--end::Chart-->
                                <!--begin::Stats-->
                                <div class="card-spacer bg-white card-rounded flex-grow-1">
                                    <!--begin::Row-->
                                    <div class="row m-0">
                                        <div class="col px-8 py-6 mr-8">
                                            <div class="font-size-sm text-muted font-weight-bold">Average Sale</div>
                                            <div class="font-size-h4 font-weight-bolder">$650</div>
                                        </div>
                                        <div class="col px-8 py-6">
                                            <div class="font-size-sm text-muted font-weight-bold">Commission</div>
                                            <div class="font-size-h4 font-weight-bolder">$233,600</div>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                    <!--begin::Row-->
                                    <div class="row m-0">
                                        <div class="col px-8 py-6 mr-8">
                                            <div class="font-size-sm text-muted font-weight-bold">Annual Taxes</div>
                                            <div class="font-size-h4 font-weight-bolder">$29,004</div>
                                        </div>
                                        <div class="col px-8 py-6">
                                            <div class="font-size-sm text-muted font-weight-bold">Annual Income</div>
                                            <div class="font-size-h4 font-weight-bolder">$1,480,00</div>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Charts Widget 4-->
                    </div>
                    <div class="col-lg-6">
                        <!--begin::List Widget 11-->
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0">
                                <h3 class="card-title font-weight-bolder text-dark">Notifications</h3>
                                <div class="card-toolbar">
                                    <div class="dropdown dropdown-inline">
                                        <a
                                            href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-ver"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                            <!--begin::Naviigation-->
                                            <ul class="navi">
                                                <li class="navi-header font-weight-bold py-5">
                                                    <span class="font-size-lg">Add New:</span>
                                                    <i
                                                        class="flaticon2-information icon-md text-muted"
                                                        data-toggle="tooltip" data-placement="right"
                                                        title="Click to learn more..."></i>
                                                </li>
                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="flaticon2-shopping-cart-1"></i>
                                                        </span>
                                                        <span class="navi-text">Order</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="navi-icon flaticon2-calendar-8"></i>
                                                        </span>
                                                        <span class="navi-text">Members</span>
                                                        <span class="navi-label">
                                                            <span
                                                                class="label label-light-danger label-rounded font-weight-bold">
                                                                3
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="navi-icon flaticon2-telegram-logo"></i>
                                                        </span>
                                                        <span class="navi-text">Project</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="navi-icon flaticon2-new-email"></i>
                                                        </span>
                                                        <span class="navi-text">Record</span>
                                                        <span class="navi-label">
                                                            <span
                                                                class="label label-light-success label-rounded font-weight-bold">
                                                                5
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-separator mt-3 opacity-70"></li>
                                                <li class="navi-footer pt-5 pb-4">
                                                    <a class="btn btn-light-primary font-weight-bolder btn-sm" href="#">
                                                        More
                                                        options
                                                    </a>
                                                    <a
                                                        class="btn btn-clean font-weight-bold btn-sm d-none" href="#"
                                                        data-toggle="tooltip" data-placement="right"
                                                        title="Click to learn more...">Learn more
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Naviigation-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-0">
                                <!--begin::Item-->
                                <div class="mb-6">
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <!--begin::Checkbox-->
                                        <label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
                                            <input type="checkbox" value="1"/>
                                            <span></span>
                                        </label>
                                        <!--end::Checkbox-->
                                        <!--begin::Section-->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between w-100">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column align-items-cente py-2 w-75">
                                                <!--begin::Title-->
                                                <a
                                                    href="#"
                                                    class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">
                                                    Daily Standup Meeting
                                                </a>
                                                <!--end::Title-->
                                                <!--begin::Data-->
                                                <span class="text-muted font-weight-bold">Due in 2 Days</span>
                                                <!--end::Data-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Label-->
                                            <span
                                                class="label label-lg label-light-primary label-inline font-weight-bold py-4">
                                                Approved
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="mb-6">
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <!--begin::Checkbox-->
                                        <label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
                                            <input type="checkbox" value="1"/>
                                            <span></span>
                                        </label>
                                        <!--end::Checkbox-->
                                        <!--begin::Section-->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between w-100">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column align-items-cente py-2 w-75">
                                                <!--begin::Title-->
                                                <a
                                                    href="#"
                                                    class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">
                                                    Group Town Hall Meet-up with showcase
                                                </a>
                                                <!--end::Title-->
                                                <!--begin::Data-->
                                                <span class="text-muted font-weight-bold">Due in 2 Days</span>
                                                <!--end::Data-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Label-->
                                            <span
                                                class="label label-lg label-light-warning label-inline font-weight-bold py-4">
                                                In Progress
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="mb-6">
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <!--begin::Checkbox-->
                                        <label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
                                            <input type="checkbox" value="1"/>
                                            <span></span>
                                        </label>
                                        <!--end::Checkbox-->
                                        <!--begin::Section-->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between w-100">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column align-items-cente py-2 w-75">
                                                <!--begin::Title-->
                                                <a
                                                    href="#"
                                                    class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">
                                                    Next sprint planning and estimations
                                                </a>
                                                <!--end::Title-->
                                                <!--begin::Data-->
                                                <span class="text-muted font-weight-bold">Due in 2 Days</span>
                                                <!--end::Data-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Label-->
                                            <span
                                                class="label label-lg label-light-success label-inline font-weight-bold py-4">
                                                Success
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="mb-6">
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <!--begin::Checkbox-->
                                        <label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
                                            <input type="checkbox" value="1"/>
                                            <span></span>
                                        </label>
                                        <!--end::Checkbox-->
                                        <!--begin::Section-->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between w-100">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column align-items-cente py-2 w-75">
                                                <!--begin::Title-->
                                                <a
                                                    href="#"
                                                    class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">
                                                    Sprint delivery and project deployment
                                                </a>
                                                <!--end::Title-->
                                                <!--begin::Data-->
                                                <span class="text-muted font-weight-bold">Due in 2 Days</span>
                                                <!--end::Data-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Label-->
                                            <span
                                                class="label label-lg label-light-danger label-inline font-weight-bold py-4">
                                                Rejected
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end: Item-->
                                <!--begin: Item-->
                                <div class="">
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <!--begin::Checkbox-->
                                        <label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
                                            <input type="checkbox" value="1"/>
                                            <span></span>
                                        </label>
                                        <!--end::Checkbox-->
                                        <!--begin::Section-->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between w-100">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column align-items-cente py-2 w-75">
                                                <!--begin::Title-->
                                                <a
                                                    href="#"
                                                    class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">
                                                    Data analytics research showcase
                                                </a>
                                                <!--end::Title-->
                                                <!--begin::Data-->
                                                <span class="text-muted font-weight-bold">Due in 2 Days</span>
                                                <!--end::Data-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Label-->
                                            <span
                                                class="label label-lg label-light-warning label-inline font-weight-bold py-4">
                                                In Progress
                                            </span>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end: Item-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                        <!--end::List Widget 11-->
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>


    </div>
@endsection

@push('script')

@endpush
