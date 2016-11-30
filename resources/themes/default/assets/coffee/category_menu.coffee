categories_init = () ->
	categories_menu = $('.categories-menu')
	left_side = categories_menu.find('.left-side')
	right_side = categories_menu.find('.right-side')
	categories_dropdown_menu = categories_menu.find('.dropdown-menu')

	left_side.append(categories_dropdown_menu.children('li'))

	left_side.children('li').each(()->
		if $(this).position().top > 0
			categories_dropdown_menu.append($(this))
	)

	if !categories_dropdown_menu.children('li').length
		right_side.hide()
	else
		right_side.show()

$(window).on 'resize', ->
	categories_init()
	return


$(document).ready ->
	categories_init()
	$('.categories-menu').find('ul').animate { opacity: 1 }

	$('.categories-menu').find('.left-side').on 'mouseover', 'li', ->
		if !$(this).hasClass('active')
			$(this).closest('.left-side').find('.active').css("border-color", "transparent")

	$('.categories-menu').find('.left-side').on 'mouseleave', 'li', ->
		$(this).closest('.left-side').find('.active').css("border-color", "#55a890")


	
	