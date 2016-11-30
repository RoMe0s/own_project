@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, array('enctype'=>'multipart/form-data', 'role' => 'form', 'method' => 'put', 'class' => 'form-horizontal', 'route' => array('admin.categories.update', $model->id))) !!}

            @include('category.partials._form')

            {!! Form::close() !!}
        </div>
    </div>

@stop