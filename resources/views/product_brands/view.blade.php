@extends('layouts.crud-master')
@section('')
@section('main_content')
    <div class="card card-custom min-h-500px">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">{!! $title !!}
                    <small>{!! Illuminate\Support\Str::title($title) !!}</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="/{!! Illuminate\Support\Str::snake($model_name) !!}" class="btn btn-sm btn-light-primary"
                   data-card-tool="remove" data-toggle="tooltip" data-placement="top"
                   title="{!! ucfirst(Illuminate\Support\Str::singular($model_name)) !!} List">
                    <i class="fa fa-list"></i> {!! ucfirst(Illuminate\Support\Str::singular($model_name)) !!}
                </a>
            </div>
        </div>
        <div class="card-body">

            <div class="normal-table-list">
                <div class="bsc-tbl">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered table-condensed">
                            <tbody>
                            @foreach($columns_visible as $column)
                                <tr>
                                    <td>{!! Illuminate\Support\Str::title(str_replace('_', ' ', $column)) !!}</td>
                                    <td>{!! $data->$column !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
