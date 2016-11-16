###*
# Created by rome0s on 10/28/16.
###

$.mobile.ajaxEnabled = false

$(document).ready ->

  window.erase_messages()
  $('[data-toggle="tooltip"]').tooltip()

  #NEWS LOAD
  $('.load_more').on 'click', 'a', (event) ->
    $before = $(this).closest('div.load_more')
    loader_init $before, 'before'
    $this = $(this)
    $this.css 'pointer-events', 'none'
    count = $('.content-grid div.news').length
    $.ajax(
      url: window.location.pathname + count
      type: 'GET'
      data: {}).done((response) ->
      if response.status == 'success'
        $before.before response.data
      else
        error_message response.status, response.message
      loader_remove()
      $this.css 'pointer-events', ''
      return
    ).error ->
      loader_remove()
      $this.css 'pointer-events', ''
      return
    event.preventDefault()
    return
  #ENDNEWSLOAD

  #Show search//
  $('li.show_search').on 'click', 'a', ->
    catMenu = $('nav#top_menu')
    search = catMenu.find('li.search_form')
    form = search.find('form')
    input = form.find('input[type=text]')
    if search.is(':hidden')
      catMenu.find('div.right_part > li').not(':has(a.blacked)').hide()
      search.css 'display', 'inline-block'
      input.focus()
    else if input.val() == ''
      catMenu.find('div.right_part > li').not(':has(a.blacked)').show()
      search.hide()
      input.val ''
    else
      form.submit()
    return
  #End show search//

  $('div.menu-mobile-button').on 'click', 'a', ->
    top_menu = $('nav#top_menu')
    top_menu.show()

  $('nav#top_menu').on 'swipeleft', ->
    if $('div.menu-mobile-button').is(':visible')
      $(this).hide()

