@extends('layouts.crud-master')
@section('title', 'Supplier Bank Coount Add')
@section('main_content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div
            class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Supplier Data Table</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4">Create a Supplier for your site</span>
                <a href="{{route('admin.supplier.create')}}" class="btn btn-light-warning font-weight-bolder btn-sm">Add
                    Supplier
                </a>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a
                    href="{{route('admin.supplier.index')}}"
                    class="btn btn-clean btn-light-primary btn-sm font-weight-bold font-size-base mr-1">
                    <i class="fa fa-list"></i>
                    Supplier List
                </a>
                <!--end::Actions-->
                <!--begin::Dropdowns-->
                <div
                    class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions"
                    data-placement="left">
                    <a
                        href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="svg-icon svg-icon-success svg-icon-lg">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Files/File-plus.svg-->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path
                                        d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z"
                                        fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    <path
                                        d="M11,14 L9,14 C8.44771525,14 8,13.5522847 8,13 C8,12.4477153 8.44771525,12 9,12 L11,12 L11,10 C11,9.44771525 11.4477153,9 12,9 C12.5522847,9 13,9.44771525 13,10 L13,12 L15,12 C15.5522847,12 16,12.4477153 16,13 C16,13.5522847 15.5522847,14 15,14 L13,14 L13,16 C13,16.5522847 12.5522847,17 12,17 C11.4477153,17 11,16.5522847 11,16 L11,14 Z"
                                        fill="#000000"/>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                    </a>
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right py-3">
                        <!--begin::Navigation-->
                        <ul class="navi navi-hover py-5">
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="flaticon2-drop"></i>
                                    </span>
                                    <span class="navi-text">New Group</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="flaticon2-list-3"></i>
                                    </span>
                                    <span class="navi-text">Contacts</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="flaticon2-rocket-1"></i>
                                    </span>
                                    <span class="navi-text">Groups</span>
                                    <span class="navi-link-badge">
                                        <span
                                            class="label label-light-primary label-inline font-weight-bold">new
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="flaticon2-bell-2"></i>
                                    </span>
                                    <span class="navi-text">Calls</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="flaticon2-gear"></i>
                                    </span>
                                    <span class="navi-text">Settings</span>
                                </a>
                            </li>
                            <li class="navi-separator my-3"></li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="flaticon2-magnifier-tool"></i>
                                    </span>
                                    <span class="navi-text">Help</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="flaticon2-bell-2"></i>
                                    </span>
                                    <span class="navi-text">Privacy</span>
                                    <span class="navi-link-badge">
                                        <span
                                            class="label label-light-danger label-rounded font-weight-bold">5
                                        </span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <!--end::Navigation-->
                    </div>
                </div>
                <!--end::Dropdowns-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Create Bank Account <i
                        class="mr-2"></i><small>Create Bank Account</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('admin.supplier.index')}}" class="btn btn-sm btn-light-success"
                   data-toggle="tooltip" data-placement="left"
                   title="user List">
                    <i class="fa fa-list"></i> Supplier List
                </a>
            </div>
        </div>
        <div class="card-body">

            @include('component.message')

            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <form method="POST" action="{{ route('admin.supplier.bank.store',$supplier->id) }}"
                          accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="account_for" value="{{ $supplier->type }}">
                        <input type="hidden" name="account_owner_id" value="{{ $supplier->id }}">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input class="form-control" id="bank_name"
                                   value="{{ isset($supplier->bankAccount->bank_name) ? $supplier->bankAccount->bank_name : old('bank_name') }}"
                                   autocomplete="off" name="bank_name" type="text">
                            @error('bank_name')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="account_name">Account Name</label>
                            <input class="form-control" id="account_name"
                                   value="{{ isset($supplier->bankAccount->account_name) ? $supplier->bankAccount->account_name : old('account_name') }}"
                                   autocomplete="off" name="account_name" type="text">
                            @error('account_name')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="account_number">Account Number</label>
                            <input class="form-control" id="account_number"
                                   value="{{ isset($supplier->bankAccount->account_number) ? $supplier->bankAccount->account_number : old('account_number') }}"
                                   autocomplete="off" name="account_number" type="text">
                            @error('account_number')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="branch">branch</label>
                            <input class="form-control" id="branch"
                                   value="{{ isset($supplier->bankAccount->branch) ? $supplier->bankAccount->branch : old('branch') }}"
                                   autocomplete="off" name="branch" type="text">
                            @error('branch')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success waves-effect">Save Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@push('script')

@endpush
