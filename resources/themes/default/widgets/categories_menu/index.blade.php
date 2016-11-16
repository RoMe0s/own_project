@if(sizeof($categories))
<nav class="categories_menu">
    <ul>
        <div class="pull-left left_part">
        @foreach($categories as $key => $value)
            @if($value->slug == Request::path())
            <li class="active">
                @else
                    <li>
                    @endif
                <a href="#">
                    {!! $value->name !!}
                </a>
            </li>
        @endforeach
        </div>
            <div class="right_part pull-right text-center">
                <li class="search_form">
                    {!! Form::open(['url' => route('search.index'), 'method' => 'GET']) !!}
                    {!! Form::text('search_text', null, ['placeholder' => trans('front_labels.search'), 'required']) !!}
                    {!! Form::close() !!}
                </li>
                <li class="show_search">
                    <a href="#">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="#" title="{!! trans('front_messages.more categories') !!}" data-toggle="tooltip" data-placement="bottom">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </a>
                </li>
            </div>
    </ul>
</nav>
    <div class="clearfix"></div>
@endif