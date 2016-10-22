<ol class="breadcrumb">
    @if(count($breadcrumbs) > 1)
        @foreach($breadcrumbs as $key => $value)
            @if($value['url'] != url()->current())
            <li>
                <a href="{!! $value['url'] !!}">
                    <span>
                        {!! $value['name'] !!}
                    </span>
                </a>
            </li>
            @else
            <li class="active">
                <a href="{!! $value['url'] !!}">
                    <span>
                        {!! $value['name'] !!}
                    </span>
                </a>
            </li>
            @endif
        @endforeach
    @endif
</ol>