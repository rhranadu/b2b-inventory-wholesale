@extends('layouts.crud-master')
@section('title', 'Warehouse Edit')
@section('main_content')

    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">

                <div class="card-body">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form
                                method="POST" action="{{ route('admin.warehouse_detail.update', $warehouseDetail->id) }}"
                                accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-element-list">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label>Warehouse Name</label>
                                                    <input name="warehouse_id" type="hidden" value="{{ $warehouseDetail->warehouse_id }}">
                                                    <input
                                                        type="text" name="warehouse_name" value="{{ $warehouseDetail->warehouse_name }}"
                                                        class="form-control" placeholder="Warehouse Name" readonly>
                                                    @error('warehouse_name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label>Section Code</label>
                                                    <input
                                                        type="text" name="section_code" value="{{ $warehouseDetail->section_code }}" class="form-control"
                                                        placeholder="*Section Code" >
                                                    @error('section_code')
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
                                                    <label>Section Name</label>
                                                    <input
                                                        type="text" name="section_name" value="{{ $warehouseDetail->section_name }}" class="form-control"
                                                        placeholder="*Section Name" >
                                                    @error('section_name')
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
                                                    <label>Parent Section Name</label>
                                                    <select name="parent_section_id" id="parent_section_id" class=" form-control" >
                                                        <option value="">Please select Parent Section</option>

                                                        @foreach($parentSections as $parentSection)
                                                        <option value="{{$parentSection->id}}" {{$warehouseDetail->parent_section_id == $parentSection->id ? 'selected' : ''}}>{{$parentSection->section_name}}</option>
                                                        @endforeach


                                                    </select><br/>
                                                    @error('parent_section_id')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <div class="checkbox-inline">
                                                    <label class="checkbox checkbox-outline checkbox-success">
                                                        <input
                                                            value="1" {{ $warehouseDetail->status == 1 ? 'checked' : '' }} type="checkbox"
                                                            id="addedItemCheckbox" name="status"
                                                            class="i-checks">
                                                        <span></span>
                                                        Status
                                                    </label>
                                                </div>

                                                @error('status')
                                                <strong class="text-danger" role="alert">
                                                    <span>{{ $message }}</span>
                                                </strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success waves-effect">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
