@extends('layouts.app')
@section('title', 'Contact Us Edit')

@section('main_content')

    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-body">

            @include('component.message')

            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <form method="POST" action="{{ route('admin.contact_us.update', $contactUs->id) }}"
                          accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="phone">Phone <span
                                    style="color: red; font-size: 20px;"><sup>*</sup></span></label>
                            <input class="form-control" id="phone" value="{{ $contactUs->phone }}" autocomplete="off"
                                   name="phone" type="text">
                            @error('phone')
                            <strong class="text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </strong>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span style="color: red; font-size: 18px"><sup>*</sup></span></label>
                            <input class="form-control" id="email" value="{{ $contactUs->email }}" autocomplete="off"
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
                            <textarea class="form-control" id="address" autocomplete="off" title="address" name="address" type="text">{{isset($contactUs->address) ? $contactUs->address : 'N/A'}}</textarea>
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
                                                    <input type="text" class="form-control icon_name" id="icon_name">
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
                        <button type="submit" class="btn btn-success waves-effect">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
        var check_socials = '{{$contactUs->social_links}}';
        if(!isEmpty(check_socials)){
            $('#social_links_chkbox').trigger('click');
        }
        });

        $(document).off('click', '#social_links_chkbox').on('click', '#social_links_chkbox', function () {

            var  ischecked = $("#social_links_chkbox").is(":checked");
            if (ischecked) {
                $.post("{{ route('admin.contact_us.social.ajax') }}", {
                    id: '{{$contactUs->id}}',
                }, function (response) {
                    if (response.success == true){
                        var socials = response.socials;
                        var tbl = '';
                        $.each(socials, function (index, social) {
                             tbl += '\n' +
                                '<tr id="removeThisItem" class="everyNewSingleSocialSection">\n' +
                                '     <td class="editableColumnIcon">\n' +
                                '         <span for="" class="social_icon">' + social.icon + '</span>\n' +
                                '         <input type="hidden" class="uniqueSocial_id" data-addedSocial_id="' + social.icon + '" name="store_icon_name[]" value="' + social.icon + '">\n' +
                                '     </td>\n' +
                                '     <td class="editableColumnLink">\n' +
                                '         <span for="" class="social_link">' + social.link + '</span>\n' +
                                '          <input type="hidden" name="store_link[]" value="' + social.link + '">\n' +
                                '     </td>\n' +
                                '     <td class="editableColumnPosition">\n' +
                                '         <span for="" class="social_position">' + social.position + '</span>\n' +
                                '          <input type="hidden" name="store_position[]" value="' + social.position + '">\n' +
                                '     </td>\n' +
                                '     <td style="padding-top: 9px;">\n' +
                                '     <div class="btn-group">\n' +
                                '         <a href="#" title="Save" class="btn btn-sm btn-success waves-effect btn-icon btnSaveExpense"><i class="fas fa-check"></i></a>\n' +
                                '         <a href="#" title="Edit" class="btn btn-sm btn-warning waves-effect btn-icon btnEditExpense"><i class="fas fa-pencil-alt"></i></a>\n' +
                                '         <a href="#" title="Remove" id="removeThis" class="btn btn-sm btn-danger waves-effect btn-icon"><i class="fa fa-minus-circle"></i></a>\n' +
                                '     </div>\n' +
                                '     </td>\n' +
                                '</tr>';

                        });
                        $("#appendNewSocialSection").append(tbl);
                        $('.add_social_links_section').show();
                        $('.btnSaveExpense').hide();

                    }
                });
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
                    '     <td class="editableColumnIcon">\n' +
                    '         <span for="" class="social_icon">' + icon_name + '</span>\n' +
                    '         <input type="hidden" class="uniqueSocial_id" data-addedSocial_id="' + icon_name + '" name="store_icon_name[]" value="' + icon_name + '">\n' +
                    '     </td>\n' +
                    '     <td class="editableColumnLink">\n' +
                    '         <span for="" class="social_link">' + link + '</span>\n' +
                    '          <input type="hidden" name="store_link[]" value="' + link + '">\n' +
                    '     </td>\n' +
                    '     <td class="editableColumnPosition">\n' +
                    '         <span for="" class="social_position">' + position + '</span>\n' +
                    '          <input type="hidden" name="store_position[]" value="' + position + '">\n' +
                    '     </td>\n' +
                    '     <td style="padding-top: 9px;">\n' +
                    '     <div class="btn-group">\n' +
                    '         <a href="#" title="Save" class="btn btn-sm btn-success waves-effect btn-icon btnSaveExpense"><i class="fas fa-check"></i></a>\n' +
                    '         <a href="#" title="Edit" class="btn btn-sm btn-warning waves-effect btn-icon btnEditExpense"><i class="fas fa-pencil-alt"></i></a>\n' +
                    '         <a href="#" title="Remove" id="removeThis" class="btn btn-sm btn-danger waves-effect btn-icon"><i class="fa fa-minus-circle"></i></a>\n' +
                    '     </div>\n' +
                    '     </td>\n' +
                    '</tr>';
                $("#appendNewSocialSection").append(tbl);
                $('.icon_section').find('.remove_this_appended').remove();
                $('.link_section').find('.remove_this_appended').remove();
                $('.position_section').find('.remove_this_appended').remove();
                $("#icon_name").val('');
                $("#link").val('');
                $("#position").val('');
                $('.btnSaveExpense').hide();

            } else {
                toastr.error('Please Fill Up all field with valid value')
            }

        });
        $(document).off('click', '.btnEditExpense').on('click', '.btnEditExpense', function (e) {
            var currentRow=$(this).closest("tr");
            currentRow.find('.btnEditExpense').hide();
            currentRow.find('.btnSaveExpense').show();
            currentRow.find('td.editableColumnIcon').each(function() {
                var html = $(this).find('.social_icon').html();
                var input = $('<input class="edit_icon form-control" type="text" />');
                input.val(html);
                $(this).html(input);
            });
            currentRow.find('td.editableColumnLink').each(function() {
                var html = $(this).find('.social_link').html();
                var input = $('<input class="edit_link form-control" type="text" />');
                input.val(html);
                $(this).html(input);
            });
            currentRow.find('td.editableColumnPosition').each(function() {
                var html = $(this).find('.social_position').html();
                var input = $('<input class="edit_position form-control" type="text" />');
                input.val(html);
                $(this).html(input);
            });
        });

        $(document).off('click', '.btnSaveExpense').on('click', '.btnSaveExpense', function (e) {
            var currentRow=$(this).closest("tr");
            currentRow.find('.btnSaveExpense').hide();
            currentRow.find('.btnEditExpense').show();
            currentRow.find('td.editableColumnIcon').each(function() {
                var html = $(this).find('.edit_icon').val();
                var input = $('<span for="">' + html + '</span><input type="hidden" name="store_icon_name[]" value="' + html + '">');
                $(this).html(input);
            });
            currentRow.find('td.editableColumnLink').each(function() {
                var html = $(this).find('.edit_link').val();
                var input = $('<span for="">' + html + '</span><input type="hidden" name="store_link[]" value="' + html + '">');
                $(this).html(input);
            });
            currentRow.find('td.editableColumnPosition').each(function() {
                var html = $(this).find('.edit_position').val();
                var input = $('<span for="">' + html + '</span><input type="hidden" name="store_position[]" value="' + html + '">');
                $(this).html(input);
            });
        });

        // remove item with calculation
        $(document).off('click', '#removeThis').on('click', '#removeThis', function (e) {
            $(this).parents('#removeThisItem').remove();
        });
    </script>
@endpush
