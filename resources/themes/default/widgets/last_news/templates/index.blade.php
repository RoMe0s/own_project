@if(sizeof($list))
    <div class="content-grid">
        @foreach($list as $key => $item)
        <div class="content-grid-info news">
            @if($item->image && is_file(public_path() . $item->image))
            <img src="{!! $item->image !!}" alt="{!! $item->name !!}" style="max-width: 670px; width:100%;"/>
            @endif
            <div class="post-info">
                <h4>
                    <a href="{!! route('news.show', ['slug' => $item->slug]) !!}"><!--
                        -->{!! $item->name !!}<!--
                    --></a><!--
                    -->{!! $item->publish_at !!} /
                    {!! isset($item->comments_count) ? $item->comments_count : 0 !!}
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                </h4>
                    {!! $item->short_content!!}
                <div class="text-center">
                    <a href="{!! route('news.show', ['slug' => $item->slug]) !!}">
                        <span></span>
                        {!! trans('front_labels.read more') !!}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif