@if(sizeof($list))
        @foreach($list as $key => $item)
        <div class="news">
            @if($item->image && is_file(public_path() . $item->image))
            <img src="{!! $item->image !!}" alt="{!! $item->name !!}" style="max-width: 670px; width:100%;"/>
            @endif
            <div>
                <h4>
                    <a href="{!! route('news.show', ['slug' => $item->slug]) !!}"><!--
                        -->{!! $item->name !!}<!--
                    --></a>
                </h4>
                {!! $item->publish_at !!} /
                {!! isset($item->comments_count) ? $item->comments_count : 0 !!}
                <i class="fa fa-comments-o" aria-hidden="true"></i>
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
@endif