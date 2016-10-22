@if(sizeof($categories))
    <style media="all">
        nav.categories_menu{
            width: 100%;
            position: relative;
            border-bottom: 1px solid #d2d2d2;
            height: 50px;
        }
        nav.categories_menu ul{
            list-style: none;
            font-size: 0;
        }
        nav.categories_menu ul li{
            display: inline-block;
            font-size: 16px;
            padding: 0px 10px;
            line-height: 50px;
            border-right: 1px solid #d2d2d2;
            height: 50px;
        }
        nav.categories_menu ul li:first-child{
            border-left: 1px solid #d2d2d2;
        }
    </style>
<nav class="categories_menu text-center" id="categories_menu">
    <ul>
        @foreach($categories as $key => $value)
            <li class="categories_menu__item">
                <a>
                    {!! $value->name !!}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
    <div class="clearfix"></div>
@endif