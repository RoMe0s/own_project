<style media="all">
    .footer .subscribe_border {
        border: 2px solid black;
        padding: 3px;
    }
    .footer .subscribe_border:last-child{
        background-color: black;
        color: white;
    }
    .footer .subscribe_border:last-child:hover{
            
    }
</style>
<div class="footer">
    <div class="container">
        <div class="form-group col-md-4">
            <label for="email">{!! trans('front_messages.subscribe for news') !!}</label>
            <div class="input-group">
                <input name="email" type="text" class="form-control subscribe_border" placeholder="{!! trans('front_labels.email') !!}" aria-describedby="basic-addon2">
                <a href="/" class="input-group-addon subscribe_border" id="basic-addon2">{!! trans('front_labels.subscribe') !!}</a>
            </div>
        </div>
        <div class="col-md-4 text-center">
            1
        </div>
        <div class="col-md-4 text-center">
            <ul>
                <li>
                    <a href="{!! route('home') !!}">{!! trans('front_labels.main page') !!}</a>
                </li>
            </ul>
        </div>
    </div>
</div>    