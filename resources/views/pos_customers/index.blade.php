@extends('layouts.crud-master')

@section('title', 'Customers')

@push('css')
    <style>
        .dataTables_filter, .dataTables_length {
            padding: 20px 15px;
            padding-top: 0px;
            padding-bottom: 42px;
        !important;
        }
    </style>
@endpush


@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">POS Customer Data Table <i
                        class="mr-2"></i><small> Create a new POS customer for your site</small></h3>
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

            <div class="data-table-list">

                <div class="table-responsive">
                    <table id="data-table-basic" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Si</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th class="text-center">Vendor Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($poscustomers as $poscustomer)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $poscustomer->name }}</td>
                                <td>{{ $poscustomer->email }}</td>
                                <td>{{ $poscustomer->phone }}</td>
                                <td>{{ $poscustomer->address }}</td>
                                <td>
                                    @if($poscustomer->status === 1)
                                        <a href="#0" style="background: #0B792F" class="badge badge-success">Active</a>
                                    @else
                                        <a href="#0" style="background:#d43f3a" class="badge badge-danger">Deactive</a>
                                    @endif
                                </td>
                                    <td>{{ $poscustomer->vendor->name }}</td>
                                <td>
                                    <button type="button" class="btn" data-toggle="modal" data-target="#myModaltwo"
                                            style="background: #00c292; color: #f0f0f0">Bank
                                    </button>
                                    {{-- <button  href="{{ route('admin.poscustomer.add.bank', $poscustomer->id) }}"    class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip" data-placement="auto" title="" data-original-title="Add Bank">Bank </button>--}}
                                    {{--<a href="#0" class="btn btn-sm btn-danger waves-effect btn-icon" title="Delete product" onclick="confirm('Are you sure to delete this Product?');
                                        document.getElementById('deleteProductForm-{{ $poscustomer->id }}').submit()" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                                    <form style="display: none" method="POST" id="deleteProductForm-{{ $poscustomer->id }}" action="{{ route('admin.poscustomer.destroy', $poscustomer->id) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>--}}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table area End-->


    <div class="modals-default-cl">
        <div class="modal fade" id="myModaltwo" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="POST" action="{{ route('admin.poscustomer.store') }}" accept-charset="UTF-8"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <h1 class="text-center" style="margin-bottom: 30px;">New POS Customer Create</h1>
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" id="name" value="{{ old('name') }}" autocomplete="off"
                                       name="name" type="text">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" id="email" value="{{ old('email') }}" autocomplete="off"
                                       name="email" type="email">
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input class="form-control" id="phone" value="{{ old('phone') }}" autocomplete="off"
                                       name="phone" type="text">
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" class="form-control" id="address"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')

@endpush
