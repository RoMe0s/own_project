@if(sizeof($oauths))
<div class="social">
    @foreach($oauths as $oauth)
        <a class="{!! $oauth->name !!}" href="{!! route('auth.oauth', ['provider' => $oauth->name]) !!}" title="{!! trans('front_messages.use oauth') !!}" data-toggle="tooltip" data-placement="bottom">
            <i class="fa fa-{!! $oauth->name !!}" aria-hidden="true"></i>
        </a>
    @endforeach
</div>
@endif