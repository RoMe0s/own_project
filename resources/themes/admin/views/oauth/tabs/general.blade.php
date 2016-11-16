<div class="form-group required @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('labels.name'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('name', config('oauth.names'), null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('client_id')) has-error @endif">
    {!! Form::label('client_id', trans('labels.client_id'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('client_id', null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'placeholder' => trans('labels.client_id'))) !!}

        {!! $errors->first('client_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('client_secret')) has-error @endif">
    {!! Form::label('client_secret', trans('labels.client_secret'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('client_secret', null, ['placeholder' => trans('labels.client_secret'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('client_secret', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('redirect_uri')) has-error @endif">
    {!! Form::label('redirect_uri', trans('labels.redirect_uri'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('redirect_uri', null, ['placeholder' => trans('labels.redirect_uri'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('redirect_uri', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('display')) has-error @endif">
    {!! Form::label('display', trans('labels.display'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('display', null, ['placeholder' => trans('labels.display'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('display', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('response_type')) has-error @endif">
    {!! Form::label('response_type', trans('labels.response_type'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('response_type', null, ['placeholder' => trans('labels.response_type'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('response_type', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
