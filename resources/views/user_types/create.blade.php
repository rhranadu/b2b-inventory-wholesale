@extends('layouts.crud-master')
@section('main_content')
    <div class="card card-custom min-h-500px" id="kt_card_1">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">{!! $title !!} <i class="mr-2"></i><small>{!! strtolower($title) !!}</small></h3>
            </div>
            <div class="card-toolbar">
                <a href="/{!! Illuminate\Support\Str::snake($model_name) !!}" class="btn btn-sm btn-light-success">
                    <i class="fa fa-list"></i> {!! ucfirst(Illuminate\Support\Str::singular($model_name)) !!}
                </a>
            </div>
        </div>
        <div class="card-body">

            <div class="normal-table-list">
                <div class="bsc-tbl">
                    {!! Form::open(['url' => '/'.Illuminate\Support\Str::snake($model_name), 'method'=>'post', 'files'=>true]) !!}
                    @foreach($columns_fillable as $column)
                        @if($column == 'email')
                            @php($type = 'email')
                        @elseif($column == 'password')
                            @php($type = 'password')
                        @else
                            @php($type = 'text')
                        @endif

                        <div class="form-group">
                            <label
                                for="{!! $column !!}">{!! Illuminate\Support\Str::title(str_replace('_', ' ', $column)) !!}</label>
                            @if (\Schema::getColumnType($table_name, $column) == 'text')
                                {!! Form::textarea($column, null, ['class'=>"form-control date", 'id'=>$column, 'placeholder'=>Illuminate\Support\Str::title(str_replace('_', ' ', $column))]) !!}
                            @elseif (Illuminate\Support\Str::endsWith($column, '_id'))
                                <?php
                                $relational_model_name = Illuminate\Support\Str::limit($column, strlen($column) - strlen('_id'), '');
                                if ($relational_model_name == 'parent') {
                                    $relational_model = '\App\\' . $model_name;
                                } else {
                                    $relational_model = '\App\\' . ucfirst(Illuminate\Support\Str::singular(Illuminate\Support\Str::limit($column, strlen($column) - strlen('_id'), '')));
                                }
                                ?>
                                @if (class_exists($relational_model))
                                    @php($relational_data = $relational_model::pluck('name', 'id')->prepend('-- Please Select --', '0'))
                                    {!! Form::select($column, $relational_data, null, ['class'=>'form-control', 'id'=>$column]) !!}
                                @else
                                    {!! Form::text($column, null, ['class'=>"form-control", 'id'=>$column, 'autocomplete'=>'off', 'placeholder'=>Illuminate\Support\Str::title(str_replace('_', ' ', $column))]) !!}
                                @endif
                            @elseif (Illuminate\Support\Str::endsWith($column, '_date'))
                                {!! Form::text($column, null, ['class'=>"form-control date", 'id'=>$column, 'autocomplete'=>'off', 'placeholder'=>Illuminate\Support\Str::title(str_replace('_', ' ', $column))]) !!}
                            @elseif (Illuminate\Support\Str::endsWith($column, '_datetime'))
                                {!! Form::text($column, null, ['class'=>"form-control datetime", 'id'=>$column, 'autocomplete'=>'off', 'placeholder'=>Illuminate\Support\Str::title(str_replace('_', ' ', $column))]) !!}
                            @else
                                {!! Form::text($column, null, ['class'=>"form-control", 'id'=>$column, 'autocomplete'=>'off', 'placeholder'=>Illuminate\Support\Str::title(str_replace('_', ' ', $column))]) !!}
                            @endif
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-success">Save Data</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
