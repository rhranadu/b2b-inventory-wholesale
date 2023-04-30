@extends('layouts.crud-master')
@section('title', 'Warehouse Create')
@section('main_content')


    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px">
                <div class="card-body">
                    @include('component.message')
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form
                                method="POST" action="{{ route('admin.warehouse_detail.store') }}" accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-element-list">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-map"></i>
                                                </div>
                                                <div class="bootstrap-select ic-cmp-int">
                                                    <label>Warehouse Name<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup>*</sup>
                                                    </span>
                                                    </label>
                                                    <select name="warehouse_id" id="warehouse_id" class="selectpicker form-control" data-live-search="true">
                                                        <option value="">*Select warehouse</option>
                                                        @foreach($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}" data-type="{{ $warehouse->type }}">{{ $warehouse->name }}</option>
                                                        @endforeach
                                                    </select><br/>
                                                    @error('warehouse_id')
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
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label>Warehouse Type</label>
                                                    <input type="text" name="warehouse_type_name" id="warehouse_type_name" value="{{ old('name') }}" class="form-control" placeholder="Warehouse Type Name" readonly>
                                                    @error('warehouse_type_name')
                                                    <strong class="text-danger" role="alert">
                                                        <span>{{ $message }}</span>
                                                    </strong>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                        <hr>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group ic-cmp-int">
                                                <div class="form-ic-cmp">
                                                    <i class="notika-icon notika-support"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <label>Section Code</label>
                                                    <input
                                                        type="text" name="section_code" value="{{ old('section_code') }}" class="form-control"
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
                                                    <label>Section Name<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup>   </sup>
                                                    </span></label>
                                                    <input
                                                        type="text" name="section_name" value="{{ old('section_name') }}" class="form-control"
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
                                                    <label>Parent Section Name<span
                                                            style="color: red; font-size: 10px;">
                                                    <sup></sup></label>
                                                    <select name="parent_section_id" id="parent_section_id" class=" form-control" >
                                                        <option value="">Please Select Parent</option>
                                                    </select>
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
                                                        <input value="1" type="checkbox" id="addedItemCheckbox" name="status"
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
                                <button type="submit" class="btn btn-success waves-effect">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

    <script>
        $("#warehouse_id").change(function () {
            var id = $(this).val();
            if(id != ''){
                var request = $.ajax({
                    url: 'warehouse_type/'+id,
                    dataType: 'json',
                    type: 'GET',
                    // success: function (response) {
                    //     console.log(response)
                    //     $("#warehouse_type_name").val(response)
                    // }
                });
                request.done(function (response) {

                    $("#warehouse_type_name").val(response.warehouse)
                    var parent_sections = response.parent_sections
                    $("#parent_section_id").empty();
                    var output = '<option value="">Please Select Parent</option>';
                    $.each(parent_sections, function (index, parent_section) {
                        var id = parent_section['id'];
                        var name = parent_section['section_name'];
                        output += '<option value="'+id+'">'+name+'</option>';
                     });
                    $("#parent_section_id").append(output);
                });
            }
        });
    </script>
@endpush
