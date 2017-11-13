//--------------------------------------- Global
function state(objet,order,val) {
	//alert("state");
	if (order) {
		$(objet).removeAttr('disabled');
		$(objet).val(val);
	}
	else {
		$(objet).attr('disabled','true');
		$(objet).val("disabled");
	}
}

//--------------------------------------- add_mod_bp.php
action = isBP = upordel = 0 ;
Uname = nbr = delElem = -1;
deletedProc = Array(); indProc = 0;
deletedServices = Array();
tableMetier = timer = null ;
//db_source = null;

function selectValue(option,valeur) {
	//alert ("selectValue");
	action = option;
	//alert(action +" --- "+ valeur);
	var source_name = $("#definitionSource").html();
	var actual_host = $("#select").val();
	var choosen_source = $("#selectsource").val();
	//alert(actual_host);
	params = "valeur="+valeur+"&option="+option+"&definedSource="+source_name+"&actualHost="+actual_host+"&choosenSource="+choosen_source;
	$.post("file.php", params, result2, "html");
}

function selectValueSource(option,valeur) {
	//alert ("selectValueSource");
	//alert("blablablablabla");
	action = option;
	//console.log(action); console.log(valeur);
	var source_name = $("#definitionSource").html();
	var choosen_source = $("#selectsource").val();
	params = "valeur="+valeur+"&option="+option+"&definedSource="+source_name+"&choosenSource="+choosen_source;
	//alert(params);
	$.post("file.php", params, resultSource, "html");
}

function resultSource(data) {
	//alert ("ResultSource");
	//console.log(data);
	if (action == "selectsource") {
		if ( data == "error"){
			//alert ("Result2: Select error");
			state("#add",0,"add");
			$("input[name=sourcedb]").css("background","yellow");
			response = data;
		}
		else {
			//alert ("Result2: Select NOT error");
			//alert("HIHIHIHIHIHI");
			state("#add",1,"add");
			objet = $("input[name=sourcedb]")[0];
			if (objet) objet.style.background="white";

			options = data.split(";");
			name = options[options.length -1];
			if (objet) {
				if ( name != objet.value) objet.value = name +" - "+objet.value;
				else objet.value = name ;
			}
			//alert('HOHOHOHOHOHO');
			for (key in deletedServices){
				if( key == name) {
					taille = options.length-1;
					for (index = options.length-1; index >= 0; index--){
						value = options[index].substring(options[index].indexOf("<option "),options[index].indexOf("</option>"));
						value = value.substring(value.indexOf("'")+1,value.lastIndexOf("'"));
						for (ind = 0; ind < deletedServices[key].length; ind++){
							if ( deletedServices[key][ind][0].value == value) options = $.merge( options.slice(0,index), options.slice(index+1,options.length));
						}
					}
				}
			}
			response = "";
			for (ind = 0 ; ind < options.length ; ind++) response += options[ind];
		}
		//alert("Invoke Change");
		$("#select").html(response);
		hasChild("#change","#add");
	}
	else {
		//alert ("Result2: Not Select Source");
		name = data.substring(data.indexOf("<option "),data.indexOf("</option>"));
		name = name.substring(name.indexOf("'")+1,name.lastIndexOf("'"));
		$("#sourcedb").html(data);
		if (name != "") selectValueSource("selectsource",name);	//This break the process.
		state("#add",0,"add");
	}
}

function setValues(uname,table,source, nbr,nomb) {	//Get PHP values.
	//alert("SetValues");
	Uname = uname; tableMetier = table; nbrSer = nbr; nbr = nomb; db_source = source;
	//alert("uname : "+Uname);
}


function result2(data) {
	if (action == "select") {
		if ( data == "error"){
			//alert ("Result2: Select error");
			state("#add",0,"add");
			$("input[name=equip]").css("background","yellow");
			response = data;
		}
		else {
			//alert ("Result2: Select NOT error");
			state("#add",1,"add");
			objet = $("input[name=equip]")[0];
			if (objet) objet.style.background="white";

			options = data.split(";");
			name = options[options.length -1];
			if (objet) {
				if ( name != objet.value) objet.value = name +" - "+objet.value;
				else objet.value = name ;
			}

			for (key in deletedServices){
				if( key == name) {
					taille = options.length-1;
					for (index = options.length-1; index >= 0; index--){
						value = options[index].substring(options[index].indexOf("<option "),options[index].indexOf("</option>"));
						value = value.substring(value.indexOf("'")+1,value.lastIndexOf("'"));
						for (ind = 0; ind < deletedServices[key].length; ind++){
							if ( deletedServices[key][ind][0].value == value) options = $.merge( options.slice(0,index), options.slice(index+1,options.length));
						}
					}
				}
			}
			response = "";
			for (ind = 0 ; ind < options.length ; ind++) response += options[ind];
		}
		//alert("Invoke Change");
		$("#change").html(response);
		hasChild("#change","#add");
	}
	else {
		//alert ("Result2: Not Select Equip");
		name = data.substring(data.indexOf("<option "),data.indexOf("</option>"));
		name = name.substring(name.indexOf("'")+1,name.lastIndexOf("'"));
		$("#td").html(data);
		if (name != "") selectValue("select",name);	//This break the process.
		state("#add",0,"add");
	}
}

/*
function setValues(uname,table,source, nbr,nomb) {	//Get PHP values.
	//alert("SetValues");
	Uname = uname; tableMetier = table; nbrSer = nbr; nbr = nomb; db_source = source;
	//alert(nbrSer);
}
*/

function setServ(tabServ){
	//alert("setServ");
	for (var i = tabServ.length - 1; i >= 0; i--) {
		source = tabServ[i].source;
		val = tabServ[i].service;
		key = tabServ[i].host;
		name = source+"::"+key+";"+val;
		$("#change").append("<option value='"+val+"'>"+val+"</option>");	//Append a true fake option. To detach it as an objet.
		if(!deletedServices[key]) deletedServices[key] = Array();
		deletedServices[key][deletedServices[key].length] = $('#change option[value="'+val+'"]:first').detach();
		$('#sum').append("<tr><td>"+name+"</td><td><center><input name='del[]' type='checkbox' class='checkbox'/></center></td></tr>");
	};
}

function setProc(tabProc){
	//alert("setProc");
	for (var i = tabProc.length - 1; i >= 0; i--) {
		name = tabProc[i].bp_source+"::"+tabProc[i].bp_link;
		$("#proc").append("<option value='"+name+"'>"+name+"</option>");	//Append a true fake option. To detach it as an objet.
		deletedProc[indProc++] = $('#proc option[value="'+name+'"]').detach();
		//alert(name);
		$('#sum').append("<tr><td>"+name+"</td><td><center><input name='del[]' type='checkbox' class='checkbox'/></center></td></tr>");
	}
}

function addValue(option) {
	//alert("addValue");
	objets = document.getElementsByTagName("select");
	isBP = option; upordel = "update"; 
	
	if (option == "bp"){
		name = objets.proc.value;
		var choosen_source = $("#selectsource").val();
		//alert(name);
		params="option=update&val=proc&name="+name+"&bp="+Uname+"&source="+db_source+"&choosenSource="+choosen_source;
		$.post("file.php", params, checkUpdate, "html");		
	}
	else {
		if ( objets.select) {key = objets.select.value; }
		else {
			key = $('input[name=equip]')[0].value;
			key = $.trim(key.split(" - ")[0]);
		}
		
		//alert(key);
		name = key+";"+objets.change.value;
		//alert(name);
		params="option=update&val=serv&name="+name+"&bp="+Uname+"&source="+db_source;
		$.post("file.php", params, checkUpdate, "html");
	}
}


function checkUpdate(data){
	//alert("checkUpdate");
	$("#output").html(data);
	clearTimeout(timer);
	if ( data.indexOf("Operation successful") != -1 ){
		timer = setTimeout(function(){$("#output").html("");},3000);
		if (upordel == "update"){
			if ( nbr != -1) {	
		   		if (nbrSer == 0) state("#finish",0,"add")
		   		else if ( nbrSer == nbr) state("#finish",1,"finish");
			}
			nbrSer++;

			if (isBP == "bp"){
				name = $("#selectsource").val()+"::"+objets.proc.value;
				deletedProc[indProc++] = $('#proc option[value="'+name+'"]:first').detach();
				hasChild("#proc","#addProc");
				$('#sum').append("<tr><td>"+name+"</td><td><center><input name='del[]' type='checkbox' class='checkbox'/></center></td></tr>");
			}
			else {
				if ( objets.select)	key = objets.select.value;
				else {
					key = $('input[name=equip]')[0].value;
					key = $.trim(key.split(" - ")[0]);
				}

				name = $("#selectsource").val()+"::"+key+";"+objets.change.value;
				if(!deletedServices[key]) deletedServices[key] = Array();
				deletedServices[key][deletedServices[key].length] = $('#change option[value="'+objets.change.value+'"]:first').detach();
				hasChild("#change","#add");
				$('#sum').append("<tr><td>"+name+"</td><td><center><input name='del[]' type='checkbox'/></center></td></tr>");
			}
		}
		else {
			//Center, td, tr, first td, HTML in the td
			tr = $(delElem).parent().parent().parent();
			name = tr.children()[0].innerHTML;
			$(tr).remove();	

			if ( isBP == "bp"){
				tableNom = Array();
				for ( j = 0 ; j < tableMetier.length ; j++) {
					tableNom[j] = tableMetier[j].nom ;
				}
				for ( index = indProc-1 ; index >= 0 ; index--){
					if ( deletedProc[index][0].value == name){
						if ($("select[id=prio]").val() == "all") $("#proc").append(deletedProc[index]);
						else {
							ind = $.inArray(deletedProc[index][0].value,tableNom);
							if (tableMetier[ind].prio == $("select[id=prio]").val()) $("#proc").append(deletedProc[index]);
						}
						deletedProc = $.merge( deletedProc.slice(0,index), deletedProc.slice(index+1,deletedProc.length));
						indProc--;
					}
				}
				hasChild("#proc","#addProc");
			}
			else {
				vals = name.split(";");
				for ( key in deletedServices){	//For each key of the table
					if ( key == vals[0]){
						for (index = deletedServices[key].length-1; index >= 0 ; index--){
							if ( deletedServices[key][index][0].value == vals[1]){
								if ( objets.select ) {if ( key == objets.select.value) $("#change").append(deletedServices[key][index]);}
								else {
									if ( key == $.trim($("input[name=equip]")[0].value.split(" - ")[0])) $("#change").append(deletedServices[key][index]);
								}
								deletedServices[key] = $.merge( deletedServices[key].slice(0,index), deletedServices[key].slice(index+1,deletedServices[key].length));
							}
						}
					}
				}
				hasChild("#change","#add");
			}

			nbrSer--;
			if ( nbr != -1) {
	   			if (nbrSer == 0) state("#finish",1,"finish");
	   			else if ( nbrSer <= nbr) state("#finish",0,"add");
			}
			delValue();
		}
	}
}

function delValue() {
	//alert("delValue");
	objets = document.getElementsByTagName("select");
	upordel = "delete";
	if ( nbrSer > 0){
		toDel = document.getElementsByName("del[]");

		for ( i = toDel.length-1; i >= 0; i--) {
			if(toDel[i].checked){
				//Center, td, tr, first td, HTML in the td
				tr = $(toDel[i]).parent().parent().parent();
				name = tr.children()[0].innerHTML;
				delElem = toDel[i];

				if(name.indexOf(";") != -1){	// equipment;services
					isBP = "nobp";					
					params="option=delete&val=serv&name="+name+"&bp="+Uname+"&source="+db_source;
					$.post("file.php", params, checkUpdate, "html");
				}
				else {
					isBP = "bp";
					params="option=delete&val=proc&name="+name+"&bp="+Uname+"&source="+db_source;
					$.post("file.php", params, checkUpdate, "html");
				}
				break;	//Allow multiple delete without bug. If not use, for 5 deleted values, the for is displayed 5+4+3+2+1 times. Making nbrSer negative.
			}
		}
	}
}

function hasChild(name,button) {
	//alert("hasChild");
	childrens = $(name).children("option");
	if(childrens[0] == undefined) state(button,0,"add");
	else state(button,1,"add");
}

function chooseDisplay(display)
{
	var choosen_source = $("#selectsource").val();
	
	$.ajax({
		url: "file.php",
		type: "POST",
		data: {
			option: "getBP",
			choosenSource: choosen_source,
			definedSource: choosen_source, // on évite un bug la ...
			display: display
		},
		success: function(response){
			console.log(response);
			$("#proc").html(response);
		},
		error: function(){
			console.log("ERRORRRRRR chooseDisplay");
		}
	});
}
/*function chooseDisplay(display){
	//alert("chooseDisplay");
	tableNom = Array();
	j = 0;
	for ( index = indProc-1 ; index >= 0 ; index--){
		tableNom[j++] = deletedProc[index][0].value
	}

	for (i = 0 ; i < $("select[id=proc]").children().length ; i++){
		$("select[id=proc] option:nth-child").remove();
	}

	if (display == "all"){
		for (i = 0 ; i < tableMetier.length ; i++ ){
			if (tableMetier[i].name != Uname)
				if ( $.inArray( tableMetier[i].name,tableNom) == -1 )
					$("select[id=proc]").append("<option value='"+tableMetier[i].name+"'>"+tableMetier[i].name+"</option>");
		};
	}
	else {
		for (i = 0 ; i < tableMetier.length ; i++ ){
			if (tableMetier[i].priority == display)
				if (tableMetier[i].name != Uname)
					if ( $.inArray( tableMetier[i].name,tableNom) == -1 )
						$("select[id=proc]").append("<option value='"+tableMetier[i].name+"'>"+tableMetier[i].name+"</option>");
		};
	}

	hasChild("#proc","#addProc");
}*/

//---------------------------------------- add_process.php
function checkURL(objet) {
	//alert("checkURL");
	hasURL = false;
	if ( objet.value != ""){
		hasURL = true;
		regURL = RegExp("(https?://)?(www.)?([a-zA-Z0-9-]{1,}\\.)+([a-zA-Z]{2,4})(/[a-zA-Z0-9-_/\\.#:+?%=&]*)?", "i");
		if (!regURL.test(objet.value)){
			//alert("This is not an URL. We don't keep it.");
			objet.value = "";
			hasURL = false;
		}
	}
	checkCommand($('input[name=process_cmd]')[0]);
}

function checkCommand(objet){
	//alert("checkCommand");
	if ( !hasURL ){
		regURL = RegExp("(https?://)?(www.)?([a-zA-Z0-9-]{1,}\\.)+([a-zA-Z]{2,4})(/[a-zA-Z0-9-_/\\.#:+?%=&]*)?", "i");
		if (regURL.test(objet.value)){
			//alert("This seems to be an URL in 'Command' field. We replace it in 'URL' field.");
			$('input[name=process_url]').val(objet.value);
			objet.value = "";
		}
	}
}

function checkName(objet){
	//alert("checkName");
	//reg = RegExp("[;\n\r]","g");
	reg = RegExp("[;\n\r]","g");
	if (reg.test(objet.value)){
		objet.value = objet.value.replace(reg,"");
	}
}

function clean(objet) {
	//alert("clean");
	if (objet.value == "Choose a name" ) objet.value = "" ;
}

function refillSource(objet) {
	//alert ("refillSource\nobjet: "+objet.value);
	if (objet.value == "") {
		objet.value = "Choose a name";
		state("#add",0,"add");
	}
	else 
		exist(objet.value) ;
}

function refill(objet,source) {
	//alert("objet : " + objet.value);
	//alert ("source : "+source);
	if (objet.value == "") {
		objet.value = "Choose a name";
		state("#add",0,"add");
	}
	else 
		exist(objet.value,source) ;
}

function exist(valeur,source) {
	//alert ("exist");
	params = "source="+source+"&valeur="+valeur;
	$.post("exist.php", params, result, "html");
}

function result(data) {
	resultat = data.split("\n").pop();	//Get the last line of the response.
	//alert ("resultat:\n" + resultat );
	state("#add",1,"add");
	if ( resultat == "ok") {
		$("#output").html("");
		$("#add").val("add");
	}
	else {
		if (resultat == "mod") {
			$("#output").html("");
			$("#add").val("modify");
			loadData(data.split("\n"));
		}
		else {
			$("#output").html(data);
			state("#add",0,"add");
		}
	}
}

function loadData(datas){
	//alert("loadData");
	document.form_bp.source_name.value = datas[0];
	document.form_bp.unique_name.value = datas[1];
	document.form_bp.process_name.value = datas[2];
	document.form_bp.process_prio.value = datas[3];
	document.form_bp.process_type.value = datas[4];
	document.form_bp.process_cmd.value = datas[5];
	document.form_bp.process_url.value = datas[6];
	if ( datas[4] == "MIN") {
		document.form_bp.process_type_min.disabled = false;
		document.form_bp.process_type_min.value = datas[7];
	}
	else  document.form_bp.process_type_min.disabled = true;
	isDisp(document.form_bp.process_prio);
	////alert ("document.form_bp.source_name.value: " + document.form_bp.source_name.value);
}

function ismin(objet) {
	//alert ("ismin");
	if ( objet.value == "MIN") 
		document.form_bp.process_type_min.disabled=false;
	else 
		document.form_bp.process_type_min.disabled=true;
}

function isDisp(objet) {
	//alert ("isDisp");
	if ( objet.value != "null") {
		document.form_bp.process_url.disabled=false;
		document.form_bp.process_cmd.disabled=false;
		document.form_bp.process_name.disabled=false;
	}
	else {
		document.form_bp.process_url.disabled=true;
		document.form_bp.process_cmd.disabled=true;
		document.form_bp.process_name.disabled=true;
	}
}

function enable() {
	//alert ("enable");
	document.form_bp.unique_name.disabled=false;
	document.form_bp.source_name.disabled=false;
}

// ------------------------------------------------------------ index.php
max_display = null;

function makeTable(source,prio,name,url,cmd){
	//alert("makeTable");
	if( prio == "null") prio = 0;
	else prio++;

	table = $("table[id="+prio+"]");

	if(table.css("display") == "none") {
		table.css("display","inline");
		existingTable[prio] = true;
	}

	table.append("<tr><td nowrap><span name=\"source_name\">"+source+"</span></td><td nowrap><a href='add_process.php?name="+name+"&source="+source+"'>"+name+"</a></td><td><center><input type='checkbox' class='checkbox' name='bp_selected"+prio+"[]' value='"+name+"::"+source+"'></center></td></tr>");
}

function resizeAll(){
	//Size the table
	//alert("resizeAll");
	maxsize = 0;

	for (i = 0 ; i < max_display+2 ; i++) {
		taille = $("table[id="+i+"]").width();	//With the inline display, we get the real width
		if ( taille > maxsize) maxsize = taille ;
	}

	for (i = 0 ; i < max_display+2 ; i++) {
		table = $("table[id="+i+"]");
		table.css("display","table");	//Change the display, then we can change the width. Else it do nothing.
		table.width(maxsize+5);	//Some problems with IE. May need more than "+5"px
	}

	//Size the columns
	size = maxsize-35;	//Size of the table less the last column size (40px)
	size = 33*size/100 ; // 1/3 of table width. (Or more with nowrap attribut)
	for (i = 0 ; i < max_display+2 ; i++) {
		//Get the table, then the thead child of the table, then the tr childs, then the td childs
		child = $("table[id="+i+"]").children("thead").children().children() ;
		for ( j = 0 ; j < 3 ; j++) child[j].style.width=size;
	}

	//Size the checkbox
	maxsize = 0;
	tab = $("select");

	for ( i = 0 ; i < tab.length ; i++ ) {
		taille = tab[i].offsetWidth;
		if ( taille > maxsize) maxsize = taille ;
	}

	for ( i = 0 ; i < tab.length ; i++ ) {
		tab[i].style.width = maxsize;
	}
}

function show(display){
	//alert("show");

	if ( display == "all"){
		for (i = 0 ; i < max_display+2 ; i++) {
			$("table[id="+i+"]").css("display","none");	//hide all
		}
		$("#survey").css("display","none");

		for (i = 0 ; i < max_display+2 ; i++) {
			if ( existingTable[i]) $("table[id="+i+"]").css("display","table");	//Show existing
		}
	}
	else {
		if ( display == "survey"){
			for (i = 0 ; i < max_display+2 ; i++) {
				$("table[id="+i+"]").css("display","none");	//hide all
			}
			$("#survey").css("display","block");
		}
		else {
			$("#survey").css("display","none");
			for (i = 0 ; i < max_display+2 ; i++) {
				if (i == display ) $("table[id="+i+"]").css("display","table");
				else $("table[id="+i+"]").css("display","none");
			}
		}
	}
}

function appendDisplay(){
	//alert("appendDisplay");
	for (i = 0 ; i < max_display+2 ; i++) {
		if ( (i != 0) && existingTable[i]) $("select[id=prio]").append("<option value='"+i+"'>Display "+(i-1)+"</option>");
		else if ( (i == 0) && existingTable[i]) $("select[id=prio]").append("<option value='"+i+"'>No Display</option>");
	}
}

function setDisplay(value){
	//alert("setDisplay");
	max_display = value;

	existingTable = Array(max_display+2);
	tabChecked = Array(max_display+2);
	for (i = 0 ; i < max_display+2 ; i++) {
		existingTable[i] = null ;
		tabChecked[i] = true ;
	}
}

function setVisible(value){
	//alert("setVisible");
	if ( value == "backup") {
		$("#setVis").css("display","table-row");
		showSurvey($("select[name=bu_list]").val());
	}
	else {
		$("#setVis").css("display","none");
		showSurvey("clean");
	}
}

function showSurvey(value){
	//alert("showSurvey");
	if ( value == "clean") $("input[name=survey]").css("visibility","hidden");
	else $("input[name=survey]").css("visibility","visible");
}

function preview(){
	//alert("preview");
	params ="option=survey&valeur="+$("select[name=bu_list]").val();
	$.post("file.php", params, resultSurvey, "html");
}

function resultSurvey(datas){
	//alert("resultSurvey");
	for (i = 0 ; i < max_display+2 ; i++) {
		$("table[id="+i+"]").css("display","none");	//hide all
	}

	$("select[id=prio] option[value='survey']").detach();
	$("select[id=prio]").append("<option value='survey'>Survey "+$("select[name=bu_list]").val()+"</option>");
	
	$("#survey").css("display","block");
	$("#survey").html(datas);
	$("#prio").val("survey");
}

function selectAll (value){
	//alert("selectAll");
	tab = $("input[name='bp_selected"+value+"[]']");
	val = tabChecked[value] ;
	tabChecked[value] = !val ;
	for ( i = 0 ; i < tab.length ; i++) tab[i].checked = val; 
}

function getConfirm(value){
	//alert("getConfirm");
	if ( value == "submit") {
		code = $("select[name=bp_mgt_list]").val();

		switch (code) {
			case "delete_process" :
			case "delete_all" :
			case "cascade_delete" :
				return confirm("Are you sure?");
				break;
			default :
				return true;
				break;
		}
	}
	else return confirm("Are you sure?");
}
