<div class="form-group required @if ($errors->has('category_id')) has-error @endif">
    {!! Form::label(isset($array_name) ? $array_name : 'categories', trans('labels.categories'), ['class' => "control-label col-xs-4 col-sm-3 col-md-2"]) !!}

    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
        {!! Form::select((isset($array_name) ? $array_name : 'category_id'), isset($categories_array) ? $categories_array : [], isset($model->category) ? $model->category->id : null, ['class' => 'select2']) !!}

        {!! $errors->first((isset($array_name) ? $array_name : 'category_id').'[]', '<span class="error">:message</span>') !!}
    </div>
</div>