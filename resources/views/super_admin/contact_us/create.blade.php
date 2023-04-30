@extends('layouts.crud-master')
@section('title', 'Contact Us Create')
@push('css')
{{--    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-85082661-5"></script>--}}

{{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">--}}
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">

    <link href="{{ asset('assets/js/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
@endpush
@section('main_content')

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="card card-custom min-h-500px" id="kt_card_1">
                <div class="card-body">

                    @include('component.message')

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            <form method="POST" action="{{ route('superadmin.contact_us.store') }}" accept-charset="UTF-8"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="phone">Phone<span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="phone" value="{{ old('phone') }}" autocomplete="off"
                                           name="phone" type="text">
                                    @error('phone')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email <span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <input class="form-control" id="email" value="{{ old('email') }}" autocomplete="off"
                                           name="email" type="email">
                                    @error('email')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address">Address<span
                                            style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                                    <textarea class="form-control" id="address" autocomplete="off" title="address" name="address" type="text">{{ old('address') }}</textarea>
                                    @error('address')
                                    <strong class="text-danger" role="alert">
                                        <span>{{ $message }}</span>
                                    </strong>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-success">
                                            <input
                                                value="" type="checkbox" id="social_links_chkbox" name=""
                                                class="i-checks social_links">
                                            <span></span>
                                            Social Links
                                        </label>
                                    </div>
                                </div>

                                <div class="row add_social_links_section" style="display: none">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-highlight">
                                            <thead>
                                            <tr>
                                                <th width="100">Icon</th>
                                                <th width="50">Link</th>
                                                <th width="50">Position</th>
                                                <th width="50"></th>
                                            </tr>
                                            </thead>
                                            <tbody id="appendNewSocialSection">
                                            <tr>
                                                <td class="icon_section">
                                                    <div class="form-group">
                                                        <div class="input-group">
{{--                                                            <input data-placement="topRight" class="form-control icon_name icon-picker" id="icon_name"--}}
{{--                                                                   type="text"/>--}}
{{--                                                            <span class="input-group-addon"></span>--}}
                                                            <input type="text" class="form-control icon_name icon-picker" id="icon_name">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="link_section">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control link" id="link" >
                                                        </div>
                                                    </div>
                                                </td><td class="position_section">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control position" id="position">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" id="addnewSocialBtn" class="btn btn-success font-weight-bold"><i
                                                            class="fa fa-plus-circle"></i>Add Into List</button>
                                                </td>

                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <button type="submit" style="background: #00c292; color: #f0f0f0" class="btn  waves-effect">
                                    Create
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Container-->
    </div>
    <!--begin::Entry-->

@endsection

@push('script')
{{--    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>--}}
{{--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>--}}

    <script src="{{ asset('assets/js/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.icon-picker').iconpicker({});
        });
        $(document).off('click', '#social_links_chkbox').on('click', '#social_links_chkbox', function () {

            var  ischecked = $("#social_links_chkbox").is(":checked");
            if (ischecked) {
                $('.add_social_links_section').show();
            }
            if (!ischecked) {
                $('.add_social_links_section').hide();
                $('.everyNewSingleSocialSection').remove();

            }
        });

            // start===> finaly add new item in our collection
            $(document).off('click', '#addnewSocialBtn').on('click', '#addnewSocialBtn', function (e) {
                e.preventDefault();
                var icon_name = $('#icon_name').val();
                var link = $('#link').val();
                var position = $('#position').val();
                if (icon_name && link && position ) {
                    var tbl = '\n' +
                        '<tr id="removeThisItem" class="everyNewSingleSocialSection">\n' +
                        '     <td>\n' +
                        '         <span for="">' + icon_name + '</span>\n' +
                        '         <input type="hidden" class="uniqueSocial_id" data-addedSocial_id="' + icon_name + '" name="store_icon_name[]" value="' + icon_name + '">\n' +
                        '     </td>\n' +
                        '     <td>\n' +
                        '         <span for="">' + link + '</span>\n' +
                        '          <input type="hidden" name="store_link[]" value="' + link + '">\n' +
                        '     </td>\n' +
                        '     <td>\n' +
                        '         <span for="">' + position + '</span>\n' +
                        '          <input type="hidden" name="store_position[]" value="' + position + '">\n' +
                        '     </td>\n' +
                        '     <td style="padding-top: 9px;">\n' +
                        '         <a href="#0" id="removeThis" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>\n' +
                        '     </td>\n' +
                        '</tr>';
                    $("#appendNewSocialSection").append(tbl);
                    $('.icon_section').find('.remove_this_appended').remove();
                    $('.link_section').find('.remove_this_appended').remove();
                    $('.position_section').find('.remove_this_appended').remove();
                    $("#icon_name").val('');
                    $("#link").val('');
                    $("#position").val('');

                } else {
                    toastr.error('Please Fill Up all field with valid value')
                }

            });


            // remove item with calculation
            $(document).on("click", "#removeThis", function () {
                $(this).parents('#removeThisItem').remove();
            });




    </script>

@endpush
