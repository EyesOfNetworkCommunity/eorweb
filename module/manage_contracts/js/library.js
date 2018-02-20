function message(msg, type, target){
	
	switch (type) {
		case 'critical':
			type = "danger";
			icon = "fa-exclamation-circle";
			break;
		 case 'warning':
			icon = "fa-warning";
			break;
		default:
			type = "success";
			icon = "fa-check-circle";
	}
	
    var message = ''
        +'<p class="alert alert-dismissible alert-'+type+'">'
        +   '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
        +       '<span aria-hidden="true">&times;</span>'
        +   '</button>'
        +   '<i class="fa '+icon+'"> </i> '+ msg
        +'</p>';
    $(target).html(message);
}

function DisplayPopupRemove(text, tablename, number, title,Yes,No){
	$('body').append('<div id="popup_confirmation" title="' + title + '"></div>');
	$("#popup_confirmation").html(text);
	$("#popup_confirmation").dialog({
		autoOpen: false,
		buttons: [
			{
				text: Yes,
				click: function(){
					ClickOnYes(tablename, number);
				}
			},
			{
				text: No,
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
		}
	);
	$("#popup_confirmation").dialog("close");
	$(location).attr('href',""+tablename+"_view.php");
}

function UrlParam(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results == null){
		return false;
	}
	return results[1];
}

function DisplayAlertSuccess(msg,type,target,url){
	message(msg,type,target);
	setTimeout(function(){
		$(location).attr('href', url);
		},
		2000
	);
}

function DisplayAlert(msg,type,target){
	message(msg,type,target);
	setTimeout(
		function(){
			$(target).empty();
		},5000
	);
}
