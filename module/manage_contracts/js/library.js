function DisplayPopupRemove(text, tablename, number){
	console.log("uyetuhygejhegehjg");
	$('body').append('<div id="popup_confirmation" title="Suppression"></div>');
	$("#popup_confirmation").html(text);

	$("#popup_confirmation").dialog({
		autoOpen: false,
		width: 400,
		buttons: [
			{
				text: "Oui",
				click: function(){
          ClickOnYes(tablename, number);
        }
			},
			{
				text: "Non",
				click: function () {
					$(this).dialog("close");
				}
			}
		]
	}).dialog("open");
}

function ClickOnYes(tablename, number){
  $.get(
    './php/delete_entry.php',
    {
      table_name: tablename,
      id_number: number
    },
    function ReturnStatus(value){
      console.log(value);
        $("#popup_confirmation").dialog("close");
  	$(location).attr('href',""+tablename+"_view.php");
    }
  );
}

function UrlParam(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results == null){
		return false;
	}
	return results[1];
}

function DisplayAlertSuccess(message, url){
	$('#global_form').append('<div class="col-md-12 alert alert-success" id="alert_success" style="display:none"><div class="col-md-2"><span class="glyphicon glyphicon-ok" style="font-size:28px;"></span></div><div class="col-md-10"><h4>' + message + '</h4></div></div>');

	$("#alert_success").show("slow").delay(2000).hide("slow");
	setTimeout(function(){
		$(location).attr('href', url);
		},
		2000
	);
}

function DisplayAlertWarning(message){
	$('#global_form').append('<div class="col-md-12 alert alert-warning" id="alert_warning" style="display:none"><div class="col-md-2"><span class="glyphicon glyphicon-warning-sign" style="font-size:28px;"></span></div><div class="col-md-10"><h4>' + message + '</h4></div></div>');

	$("#alert_warning").show("slow").delay(2000).hide("slow", function(){ $("#alert_warning").remove(); });
}

function DisplayAlertMissing(message){
	$('body').append('<div class="form-group pad-top"><span class="input-group-addon"><br/><span class="glyphicon glyphicon-warning-sign" style="font-size:120px;color:#f11;"></span><h2><br/>' + message + '</h2></span></div>');
}
