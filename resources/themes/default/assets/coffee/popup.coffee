###*
# Created by rome0s on 10/28/16.
###

status_check = (_this) ->
  status = true
  _this.find('i.error').remove()
  _this.find('.errors').html ''
  $.each _this.find('input.required'), ->
    if !$(this).val()
      $(this).after '<i class="fa fa-exclamation error" aria-hidden="true"></i>'
      status = false
    return
  status

###$(document).on 'click', 'a.auth_popup_button', (event) ->
  old_popups = $('.popup')
  parent.location.hash = 'login'
  $.get('/auth/login').done (respone) ->
    if respone.status == 'success'
      $('div.footer').after respone.html
      $('body').css 'overflow', 'hidden'
      old_popups.remove()
    else
      error_message respone.status, respone.message
    return
  event.preventDefault()
  return

$(document).on 'click', 'a.register_popup_button', (event) ->
  old_popups = $('.popup')
  parent.location.hash = 'register'
  $.get('/auth/register').done (respone) ->
    if respone.status == 'success'
      $('div.footer').after respone.html
      $('body').css 'overflow', 'hidden'
      old_popups.remove()
    else
      error_message respone.status, respone.message
    return
  event.preventDefault()
  return###

# ---
# generated by js2coffee 2.2.0

initPopup = (name) ->
  switch name
    when 'login'
      $('a.auth_popup_button').trigger 'click'
    when 'register'
      $('a.register_popup_button').trigger 'click'
    else
      window.location.hash = 'main'
      break
  return

oauth_not_my_email = (me, message_text, route) ->
  $(document).find('input[type=password]').attr('name', 'email').attr('placeholder', 'Email').attr('type', 'text').val ''
  me.remove()
  $(document).find('p.info').text message_text
  $(document).find('.popup form').attr 'action', route
  return

$(document).on 'click', '.popup > div.close_button > a', ->
  $('.popup').remove()
  $('body').css 'overflow', 'auto'
  parent.location.hash = 'main'
  return
$(document).on 'submit', '#auth_popup form', (event) ->
  status = status_check($(this))
  if status
    $.ajax(
      url: $(this).attr('action')
      type: $(this).attr('method')
      data: $(this).serialize()).done((response) ->
      if response.status == 'success'
        window.location.href = response.redirect
      else
        $('.popup p.errors').html response.message
      return
    ).error (response) ->
      if response.responseText != null
        data = JSON.parse(response.responseText)
        message = data[Object.keys(data)[0]].pop()
        $('.popup p.errors').html message
      else
        error_message 'error', 'Error!'
      return
  event.preventDefault()
  return
$(document).on 'submit', '#register_popup form, #oauth_popup form', (event) ->
  status = status_check($(this))
  if status
    $.ajax(
      url: $(this).attr('action')
      type: $(this).attr('method')
      data: $(this).serialize()).done((response) ->
      if response.status == 'success'
        if response.redirect != undefined
          window.location.href = response.redirect
        else if response.html != undefined
          _fields = $('.popup-content').find('.fields').html(response.html)
      else
        $('.popup p.errors').html response.message
      return
    ).error (response) ->
      if response.responseText != null
        data = JSON.parse(response.responseText)
        message = data[Object.keys(data)[0]].pop()
        $('.popup p.errors').html message
      else
        error_message 'error', 'Error!'
      return
  event.preventDefault()
  return

# ---
# generated by js2coffee 2.2.0