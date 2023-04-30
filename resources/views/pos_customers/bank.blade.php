@extends('layouts.crud-master')
@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Create Bank Account <i
                        class="mr-2"></i><small>Create Bank Account</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="{{route('admin.poscustomer.index')}}" class="btn btn-sm btn-light-success"
                   data-toggle="tooltip" data-placement="left"
                   title="POS Customer List">
                    <i class="fa fa-list"></i> POS Customer List
                </a>
            </div>
        </div>
        <div class="card-body">

            @include('component.message')

            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <form method="POST" action="{{ route('admin.poscustomer.bank.store',$poscustomer->id) }}"
                          accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="account_for" value="{{ $poscustomer->type }}">
                        <input type="hidden" name="account_owner_id" value="{{ $poscustomer->id }}">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input class="form-control" id="bank_name"
                                   value="{{ isset($poscustomer->bankAccount->bank_name) ? $poscustomer->bankAccount->bank_name : old('bank_name') }}"
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
                                   value="{{ isset($poscustomer->bankAccount->account_name) ? $poscustomer->bankAccount->account_name : old('account_name') }}"
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
                                   value="{{ isset($poscustomer->bankAccount->account_number) ? $poscustomer->bankAccount->account_number : old('account_number') }}"
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
                                   value="{{ isset($poscustomer->bankAccount->branch) ? $poscustomer->bankAccount->branch : old('branch') }}"
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
@endsection

@push('script')

@endpush
