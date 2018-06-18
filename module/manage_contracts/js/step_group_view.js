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

function EditSelection(id){
	$(location).attr('href',"step_group.php?id_number=" + id + "");
}

function RemoveSelection(id){
	DisplayPopupRemove(dictionnary["message.manage_contracts.step_group_suppress"], "step_group", id, dictionnary["action.delete"],dictionnary["label.yes"],dictionnary["label.no"]);
}