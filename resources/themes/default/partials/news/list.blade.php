@foreach($list as $key => $item)
    <div class="news category-item">
        <a href="{!! route('news.show', ['slug' => $item->slug]) !!}">
            <h2>
                    {!! $item->name !!}
            </h2>
        </a>
            <p class="small-info text-right">
                @if( \Carbon\Carbon::parse($item->publish_at) >= \Carbon\Carbon::now()->addWeek(-1) )
                    {!! \Carbon\Carbon::parse($item->publish_at)->diffForHumans() !!}
                @else
                    {!! \Carbon\Carbon::parse($item->publish_at)->format('d-m-Y') !!}
                @endif
                /
                {!! isset($item->comments_count) ? $item->comments_count : 0 !!}
                <i class="fa fa-comments-o" aria-hidden="true"></i>
            </p>
            <div class="image">
            @if($item->image && is_file(public_path() . $item->image))
                <img src="{!! $item->image !!}" alt="{!! $item->name !!}" />
            @endif
            </div>
            <div class="short-content">
            {!! $item->short_content!!}
            </div>
                <a class="read-more" href="{!! route('news.show', ['slug' => $item->slug]) !!}">
                    <i class="fa fa-share" aria-hidden="true"></i>
                    {!! trans('front_labels.read more') !!}
                </a>
    </div>
@endforeach