@extends('layouts.crud-master')
@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">{!! $title !!} <i
                        class="mr-2"></i><small>List of {!! strtolower($title) !!}</small></h3>
            </div>
            <div class="card-toolbar">
                @if (in_array('add', $permissions))
                    <a href="/{!! Illuminate\Support\Str::snake($model_name) !!}/create" class="btn btn-sm btn-light-success" data-toggle="tooltip" data-placement="left" title="Add New">
                        <i class="fa fa-plus"></i> User type List
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">

                    <div class="normal-table-list">
                        <div class="bsc-tbl">
                            @if (isset($data) && count($data) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            @foreach($columns_visible as $column)
                                                <th>{!! Illuminate\Support\Str::title(str_replace('_', ' ', $column)) !!}</th>
                                            @endforeach
                                            @if (in_array('edit', $permissions) || in_array('delete', $permissions) || in_array('view', $permissions))
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($serial = $data->perPage() * ($data->currentPage() - 1))
                                        @foreach($data as $data_item)
                                            <tr>
                                                <td>{!! ++$serial !!}</td>
                                                @foreach($columns_visible as $column)
                                                    <td>{!! $data_item->$column !!}</td>
                                                @endforeach
                                                @if (in_array('edit', $permissions) || in_array('delete', $permissions) || in_array('view', $permissions))
                                                    <td>
                                                        <div class="btn-group" style="min-width: 100px;">
                                                            @if (in_array('view', $permissions))
                                                                <a href="/{!! Illuminate\Support\Str::snake($model_name) !!}/{!! $data_item->id !!}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="auto" title="VIEW"><i class="fa fa-eye"></i> </a>
                                                            @endif
                                                            @if (in_array('edit', $permissions))
                                                                <a href="/{!! Illuminate\Support\Str::snake($model_name) !!}/{!! $data_item->id !!}/edit" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT"><i class="fas fa-pencil-alt"></i> </a>
                                                            @endif
                                                            @if (in_array('delete', $permissions))
                                                                {!! Form::open(array('method'=>'DELETE', 'route'=>array(Illuminate\Support\Str::snake($model_name).'.destroy',$data_item->id), 'class'=>'btn-group'))!!}
                                                                {!! Form::button('<i class="fa fa-trash"></i>', array('type' => 'submit', 'class'=>'btn btn-sm btn-danger',  'data-toggle'=>'tooltip', 'data-placement'=>'auto', 'title'=>'DELETE','onclick' => 'return confirm("Are you sure want to Delete?");'))!!}
                                                                {!! Form::close()!!}
                                                            @endif
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {!! $data->links() !!}
                            @else
                                <div class="alert alert-info">No data found</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    @endsection

    @push('script')

    @endpush
