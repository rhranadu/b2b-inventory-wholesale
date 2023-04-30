@extends('layouts.app')

@section('title', 'Warehouses Reports')

@push('css')

@endpush



@section('main_content')
    <div class="normal-table-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @include('component.message')
                            @if (isset($warehouses) && count($warehouses) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed" id="data-table-basic">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SI</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Address</th>
                                            <th class="text-center">Products</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($warehouses as $warehouse)
                                            <tr class="text-center">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $warehouse->name }}</td>
                                                <td>{{ $warehouse->type }}</td>
                                                <td>{{ $warehouse->address }}</td>
                                                <td>{{ $warehouse->products->where('status', '!=', 'die')->count() }}</td>
                                                <td>
                                                    <a href="{{ route('admin.report.warehouse.detail', $warehouse->id) }}" class="btn btn-info btn-xs">InDetails</a>
                                                    <a href="{{ route('admin.report.warehouse.sale', $warehouse->id) }}" class="btn btn-info btn-xs">Sale</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">No data found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection



@push('script')

@endpush
