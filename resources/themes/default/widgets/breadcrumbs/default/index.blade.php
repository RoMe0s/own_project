<ol class="breadcrumb">
    @if(count($breadcrumbs) > 1)
        @foreach($breadcrumbs as $key => $value)
            @if($value['url'] != url()->current())
            <li>
                <a href="{!! url($value['url']) !!}">
            @else
                <li class="active">
                    <a>
                    @endif
                    <span>
                        {!! $value['name'] !!}
                    </span>
                </a>
            </li>
        @endforeach
    @endif
</ol>