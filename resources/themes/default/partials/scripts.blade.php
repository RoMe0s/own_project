<script src="{!! Theme::asset('js/jquery.min.js') !!}"></script>
<script src="{!! Theme::asset('js/bootstrap.min.js') !!}"></script>
<script src="https://use.fontawesome.com/e09ece16be.js"></script>
<script>
    //LOADER START
    function loader_init($element) {
        $element.before(
                '<div class="text-center loader">' +
                        '<img src="/uploads/loaders/loader.svg" />' +
                        '</div>'
        );
    }
    function loader_remove() {
        $('.loader').remove();
    }
    //LOADER END
    //MESSAGES START
    function error_message(status, message) {
        var html = '<div id="message_popup">' +
                '<a class="pull-right">' +
                '<i class="fa fa-times-circle-o" aria-hidden="true"></i></a>' +
                '<p class="text-' + status + '">' +
                message +
                '</p></div>'
        $('div.footer').after(html);
        setTimeout(function () {
            $('#message_popup').fadeOut('slow');
        },5000);
    }
    //MESSAGES END
    $(document).ready(function(){
       $('.load_more').on('click', 'a', function (event) {
           $before = $(this).closest('div.load_more');
           loader_init($before);
           $this = $(this);
           $this.css('pointer-events', 'none');
           let count = $('.content-grid div.news').length;
            $.ajax({
                url: window.location.pathname + count,
                type: 'GET',
                data : {}
            }).done(function(response){
                if(response.status == 'success') {
                    $before.before(response.data);
                } else {
                    error_message(response.status, response.message);
                }
                loader_remove();
                $this.css('pointer-events', '');
            }).error(function(){
                loader_remove();
                $this.css('pointer-events', '');
            });
           event.preventDefault();
       });
            $(document).on('click', '#auth_popup > div.close_button > a', function () {
                $('#auth_popup').remove();
            });
            $(document).on('submit', '#auth_popup form', function () {
                var status = true;
                $(this).find('i.error').remove();
                $(this).find('.errors').html('');
                $.each($(this).find('input.required'), function () {
                    if(!$(this).val()) {
                        $(this).after('<i class="fa fa-exclamation error" aria-hidden="true"></i>');
                        return status = false;
                    }
                });
                if(status) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize()
                    }).done(function(response){
                        if(response.status == 'success') {
                            window.location.href = response.redirect;
                        } else {
                            $('#auth_popup p.errors').html(response.message);
                        }
                    });
                }
                event.preventDefault();
            });
        $(document).on('click', 'a.auth_popup_button', function (event) {
            $.get('/auth/login').done(function(respone){
              if(respone.status == 'success') {
                  $('div.footer').after(respone.html);
              } else {
                  error_message(respone.status, respone.message);
              }
            });
            event.preventDefault();
        });
        $(document).on('click', '#message_popup a', function () {
           $(this).parent('div#message_popup').remove();
        });

        $(document).on('click', 'img.joe_sounson', function () {
           error_message('success', 'Я Джо Соунсон!!! И я прикачу за тобой');
        });
    });
</script>