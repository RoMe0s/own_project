#LOADER END

###*
# Created by rome0s on 10/28/16.
###

#LOADER START

loader_init = ($element) ->
  $element.append '<div id="loader"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>'
  return

loader_remove = ($element)->
  $element.find('#loader').remove()
  return

# ---
# generated by js2coffee 2.2.0