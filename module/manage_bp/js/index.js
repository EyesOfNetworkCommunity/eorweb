/*
#########################################
#
# Copyright (C) 2018 EyesOfNetwork Team
# DEV NAME : Jean-Philippe LEVY
# VERSION : 2.0
# APPLICATION : eorweb for eyesofreport project
#
# LICENCE :
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
#########################################
*/

$(document).ready(function () {
	
	hasBeenClicked = false;
	
	// Edit BP
	$('div.tree-line a.btn_presentation').click(function () {
		hasBeenClicked = true;
	});
	
	// Delete BP
	$('button[name=delete-bp]').click(function () {
		bpname = $(this).parent().children("input[name=bp-name]").val();
		bpsource = $(this).parent().children("input[name=bp-source]").val();
		ShowModalDeleteBP(bpname,bpsource);
		hasBeenClicked = true;
	});
	
	// Toggle line
	$('.tree li.son').toggle();
	$('.tree li.end').toggle();	
	$('.tree-line').click(function () {
		if(!hasBeenClicked) {
			$(this).parent().parent().parent().children('.tree li.end').toggle();
			$(this).parent().parent().parent().children('.tree li.son').toggle();
		}
		hasBeenClicked = false;
	});
	$("[data-toggle=tooltip]").tooltip();
	
	// display bps
	$("#body").show();
	
	// find bp
	$(document).keypress(function press_enter(e){
    	if(e.which == 13){
			e.preventDefault();
			findIt();
    	}
	});

	// show all bps
	$('#show-all').on('click', function(){
		ShowAll();
	});
	
	// hide all bps
	$('#hide-all').on('click', function(){
		HideAll();
	});
	
	// event when we confirm the modal
	$('#modal-confirmation-apply-conf').on('click', function(){
		ApplyConfiguration();
	});
	$('#modal-confirmation-del-bp').on('click', function(){
		DeleteBP(bpname,bpsource);
	});
});

$('#FindIt').on('click', function(){
	findIt();
});

function findIt(){
	HideAll();
	$search_text = $('#SearchFor').val();
	$('.tree-toggle').unmark();
    $('.tree-toggle').mark($search_text,{ "className": "highlight" });
	ShowHight($search_text);
	
	var offset = $("ul:contains('" + $search_text +"')").offset();
	if(offset) {
		offset.left -= 20;
		offset.top -= 20;

		$('html, body').animate({
			scrollTop: offset.top,
			scrollLeft: offset.left
		});
	}
}

function ShowHight($search_text){
	$("ul:contains('" + $search_text +"') li.son").show();
	$("ul:contains('" + $search_text +"') li.end").show();
}

function ShowAll(){
	$('.tree li.son').show();
	$('.tree li.end').show();
}

function HideAll(){
	$('.tree li.son').hide();
	$('.tree li.end').hide();
}

function ShowModalDeleteBP(bp,source){
	var nickname_parts = source.split('_nagiosbp');
	var nickname = nickname_parts[0];
	
	$("#popup_confirmation .modal-title").html(dictionnary["action.delete"]);
	$("#popup_confirmation .modal-body").html(dictionnary["action.delete"]+'  ' + bp + "  " + dictionnary["label.manage_bp.from_source"] +'  ' + nickname + "  ?");
	$("#popup_confirmation button").hide();
	$("#modal-confirmation-del-bp").show();
	$("#action-cancel").show();
	$("#popup_confirmation").modal('show');
}

function DeleteBP(bpname,bpsource){

	$.get(
		'./php/function_bp.php',
		{
			action: 'delete_bp',
			source_name: bpsource,
			bp_name: bpname
		},
		function ReturnAction(){
			location.reload();
		}
	);

	// and close the modal
	$("#popup_confirmation").modal('hide');
}
