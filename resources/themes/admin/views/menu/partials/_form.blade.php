@include('menu.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#items" data-toggle="tab">@lang('labels.tab_items')</a>
                </li>
            </ul>

            <div class="tab-content">

                <div class="active tab-pane" id="general">
                    @include('menu.tabs.general')
                </div>

                <div class="tab-pane" id="items">
                    @include('menu.tabs.items')
                </div>
            </div>
        </div>

    </div>
</div>

@include('menu.partials._buttons')