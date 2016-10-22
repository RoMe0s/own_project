@if(sizeof($news))
<div class="recent">
    <h3>
        {!! trans('front_labels.popular news') !!}
    </h3>
    <ul>
        @foreach($news as $item)
            @if(url()->current() != route('news.show', ['slug' => $item->slug]))
                <li>
                    <a href="{!! route('news.show', ['slug' => $item->slug]) !!}">
                        {!! $item->name !!}
                    </a>
                </li>
            @else
                <li class="active">
                    <a href="{!! route('news.show', ['slug' => $item->slug]) !!}">
                        {!! $item->name !!}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
@endif
@if(sizeof($archive))
<div class="recent">
    <h3>{!! trans('front_labels.archives') !!}</h3>
    <ul>
        @foreach($archive as $item)
        <li>
            <a href="#">{!! $item['name'] !!}</a>
        </li>
        @endforeach
    </ul>
</div>
@endif