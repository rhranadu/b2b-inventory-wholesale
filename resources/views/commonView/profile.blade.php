@if(Session::has('template_name'))
    @php
        $template_name =  Session::get('layouts')
    @endphp
@else
    @php
        $template_name = 'layouts.crud-master'
    @endphp
@endif
@extends($template_name)

@section('title', 'Profile')

@push('css')

    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-select/bootstrap-select.css') }}">
    <!-- datapicker CSS
       ============================================ -->
    <link rel="stylesheet" href="{{ asset('backend/css/datapicker/datepicker3.css') }}">

@endpush



@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">POS Customer Data Table <i
                        class="mr-2"></i><small> Create a new customer for your site</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('admin.poscustomer.create')}}" class="btn btn-sm btn-light-success"
                   data-toggle="tooltip" data-placement="left"
                   title="POS Customer List">
                    <i class="fa fa-plus"></i> Create POS Customer
                </a>
            </div>
        </div>
        <div class="card-body">

    <!-- Breadcomb area Start-->
    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-icon">
                                        <img style="width: 64px; height: 64px; border: 1px solid mediumspringgreen; border-radius: 50px" src="{{ asset($user->image) }}" alt="">
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>Hey, {{ $user->name }}</h2>
                                        <p>Welcome to Our Site <span class="bread-ntd"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                {{--<div class="breadcomb-report">
                                    <button data-toggle="tooltip" data-placement="left" title="Download Report" class="btn"><i class="notika-icon notika-sent"></i></button>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->
    <!-- Compose email area Start-->
    <div class="inbox-area">
        <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <div class="inbox-left-sd">
                            <div class="compose-ml">
                                <a class="btn" href="#">Profile</a>
                            </div>
                            <div class="inbox-status">
                                <ul class="inbox-st-nav inbox-ft">
                                    <li><a href="#"><i class="notika-icon notika-support"></i> Info<span class="pull-right">12</span></a></li>
                                    {{--  <li><a href="#"><i class="notika-icon notika-sent"></i> Sent</a></li>
                                      <li><a href="#"><i class="notika-icon notika-draft"></i> Draft</a></li>
                                      <li><a href="#"><i class="notika-icon notika-trash"></i> Trash</a></li>--}}
                                </ul>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">

                        @include('component.message')

                        <div class="view-mail-list sm-res-mg-t-30">
                            <div class="view-mail-hd">
                                <div class="view-mail-hrd">
                                    <h2>Your Information Here </h2>
                                </div>
                            </div>
                            <form action="{{ route('user.profile.update', $user->id) }}" method="post">
                                @csrf
                                <div class="cmp-int mg-t-20">

                                    <div class="row">
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                            <div class="cmp-int-lb cmp-int-lb1 text-right">
                                                <span>Name: </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                            <div class="form-group">
                                                <div class="nk-int-st cmp-int-in cmp-email-over">
                                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" />
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                            <div class="cmp-int-lb cmp-int-lb1 text-right">
                                                <span>Email: </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                            <div class="form-group">
                                                <div class="nk-int-st cmp-int-in cmp-email-over">
                                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="example@email.com" />
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                            <div class="cmp-int-lb cmp-int-lb1 text-right">
                                                <span>Phone: </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                            <div class="form-group">
                                                <div class="nk-int-st cmp-int-in cmp-email-over">
                                                    <input type="text" name="mobile" value="{{ $user->mobile }}" class="form-control" placeholder="" />
                                                    @error('mobile')
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                            <div class="cmp-int-lb cmp-int-lb1 text-right">
                                                <span>Pass: </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                            <div class="form-group">
                                                <div class="nk-int-st cmp-int-in cmp-email-over">
                                                    <input type="password" name="pass" class="form-control" placeholder="New Password" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vw-ml-action-ls  mg-t-20">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


        </div>
    </div>
    <!-- Compose email area End-->

@endsection



@push('script')

    <script src="{{ asset('backend/js/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('backend/js/datapicker/datepicker-active.js') }}"></script>

    <!-- bootstrap select JS
		============================================ -->
    <script src="{{ asset('backend/js/bootstrap-select/bootstrap-select.js') }}"></script>

    <!-- icheck JS
    ============================================ -->
    <script src="{{ asset('backend/js/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('backend/js/icheck/icheck-active.js') }}"></script>

@endpush


{{--  <div class="row">
                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                    <div class="cmp-int-lb cmp-int-lb1 text-right">
                                        <span>P/C: </span>
                                    </div>
                                </div>
                                <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                    <div class="form-group">
                                        <div class="nk-int-st cmp-int-in cmp-email-over">
                                            <input type="text" title="post code" name="mobile" value="{{ $user->post_code }}" class="form-control" placeholder="Post code" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                    <div class="cmp-int-lb text-right">
                                        <span>Country: </span>
                                    </div>
                                </div>
                                <div class="bootstrap-select col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                    <select name="country_id" class="selectpicker" data-live-search="true" >
                                        <option value="">Select Country</option>
                                        <option value="Cariska">Cariska</option>
                                        <option value="Cheriska">Cheriska</option>
                                        <option value="Malias">Malias</option>
                                        <option value="Kamines">Kamines</option>
                                        <option value="Austranas">Austranas</option>
                                    </select>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                    <div class="cmp-int-lb text-right">
                                        <span>State/Div: </span>
                                    </div>
                                </div>
                                <div class="bootstrap-select col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                    <select name="country_id" class="selectpicker" data-live-search="true">
                                        <option value="">Select Country</option>
                                        <option value="Cariska">Cariska</option>
                                        <option value="Cheriska">Cheriska</option>
                                        <option value="Malias">Malias</option>
                                        <option value="Kamines">Kamines</option>
                                        <option value="Austranas">Austranas</option>
                                    </select>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                    <div class="cmp-int-lb text-right">
                                        <span>City: </span>
                                    </div>
                                </div>
                                <div class="bootstrap-select col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                    <select name="country_id" class="selectpicker" data-live-search="true">
                                        <option value="">Select Country</option>
                                        <option value="Cariska">Cariska</option>
                                        <option value="Cheriska">Cheriska</option>
                                        <option value="Malias">Malias</option>
                                        <option value="Kamines">Kamines</option>
                                        <option value="Austranas">Austranas</option>
                                    </select>
                                </div>
                            </div>
                            <br>


                            <div class="row">
                                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                    <div class="cmp-int-lb text-right">
                                        <span>Birth: </span>
                                    </div>
                                </div>
                                <div class="bootstrap-select col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                    <div class="form-group nk-datapk-ctm form-elet-mg" id="data_2">
                                        <label></label>
                                        <div class="input-group date nk-int-st">
                                            <span class="input-group-addon"></span>
                                            <input type="text" name="date_of_birth" class="form-control" value="" placeholder="Date of Birth">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>--}}
