message = {}
message.delay = 5000
message.containeer = ".messages"


message.info = (text) ->
  message.show text, "info"
  return

message.success = (text) ->
  message.show text, "success"
  return

message.warning = (text) ->
  message.show text, "warning"
  return

message.error = (text) ->
  message.show text, "error"
  return

message.show = (text, type) ->
  $("<div class='message_popup " + type + "'/>").html('<i class="fa fa-exclamation-circle" aria-hidden="true"></i><p>' + text + '</p>').appendTo(message.containeer).delay(message.delay).fadeOut 500, ->
    $(this).remove()
  return


$(document).on 'click', '.message_popup', ->
    $(this).remove()
