<h3>
    {!! trans('front_labels.popular news') !!}
</h3>
<div class="popular-news">
    @foreach($news as $item)
        @if(url()->current() != route('news.show', ['slug' => $item->slug]))
                <div class="thumbnail">
                    @if($item->image && is_file(public_path() . $item->image))
                    <img src="{!! $item->image !!}" alt="{!! $item->name !!}">
                    @endif
                    <div class="caption">
                        <h3>
                            {!! $item->name !!}
                        </h3>
                        <div class="short-content">
                            {!! $item->short_content !!}
                        </div>
                            <a href="{!! route('news.show', ['slug' => $item->slug]) !!}" class="read-more">
                                        <i class="fa fa-share" aria-hidden="true"></i>
                                        {!! trans('front_labels.read more') !!}
                            </a>
                    </div>
                </div>
    @endif
    @endforeach
</div>