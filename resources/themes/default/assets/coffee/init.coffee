###*
# Created by rome0s on 10/28/16.
###

id = undefined

news_block_function = ->
  news_block = $('.news-block')
  news_block.css('opacity', '0.4')
  if news_block.css('display') != 'block'
    news_block.height(news_block.width())
  clearTimeout id
  id = setTimeout((->
    $('.news-block').animate { opacity: 1 }
    return
  ), 200)

$(document).ready ->
  $('[data-toggle="tooltip"]').tooltip()

  news_block = $('.news-block')
  if news_block.css('display') != 'block'
    news_block.height(news_block.width())
  news_block.animate({
    opacity: 1
  }, 300);

  #NEWS LOAD
  $('.load_more').on 'click', 'a', (event) ->
    news_list = $('div.news_list')
    block = false
    _this = $(this)
    loader_init news_list
    _this.css 'pointer-events', 'none'
    if(news_list.children('div.news').length)
      count = news_list.children('div.news').length
    else
      count = news_list.children('div.news-block').length
      block = true
    $.ajax(
      url: $(this).attr('href') + '/' + count
      type: 'GET'
      data: {}).done((response) ->
        if response.status == 'success'
          news_list.append response.data
          if block
            news_block_function()
        else
          message.show response.message, response.status
        loader_remove news_list
        _this.css 'pointer-events', ''
        return
    ).error ->
      loader_remove news_list
      _this.css 'pointer-events', ''
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
    $('nav#top_menu').show()

  $('nav#top_menu').on 'swipeleft', ->
    if $(this).css('left') == '0px' && $('div.menu-mobile-button').is(':visible')
      $(this).hide()
      return

  $('nav#top_menu').on 'swiperight', ->
    if $(this).css('right') == '0px' && $('div.menu-mobile-button').is(':visible')
      $(this).hide()
      return

  $(window).on 'resize', ->
    news_block_function()

