@foreach($list as $key => $item)
    <div class="news-block">
        @if(isset($item->image) && is_file(public_path() . $item->image))
        <div class="image">
                    <img src="{!! url($item->image) !!}" alt="{!! $item->name !!}"/>
            <div class="small-info">
                @if( \Carbon\Carbon::parse($item->publish_at) >= \Carbon\Carbon::now()->addWeek(-1) )
                    {!! \Carbon\Carbon::parse($item->publish_at)->diffForHumans() !!}
                @else
                    {!! \Carbon\Carbon::parse($item->publish_at)->format('d-m-Y') !!}
                @endif
                /
                {!! isset($item->comments_count) ? $item->comments_count : 0 !!}
                <i class="fa fa-comments-o" aria-hidden="true"></i>
            </div>
        </div>
        @endif
        <div class="bottom-part">
                <h2>
                    {!! $item->name !!}
                </h2>
            <div class="short-content"
            @if(isset($item->image) && is_file(public_path() . $item->image))
                style="display: none"
            @endif
            >
                    {!! $item->short_content !!}
                </div>
        </div>
                <a class="read-more" href="{!! route('news.show', ['slug' => $item->slug]) !!}">
                    <i class="fa fa-share" aria-hidden="true"></i>
                    {!! trans('front_labels.read more') !!}
                </a>
    </div>
@endforeach