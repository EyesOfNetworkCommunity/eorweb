<?php
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
?>

</div> <!-- !#wrapper -->

<footer class="footer navbar navbar-default navbar-fixed-bottom">
	<div class="container">
		<div class="text-center">
			<a href="https://github.com/EyesOfNetworkCommunity/eyesofreport" target="_blank">EyesOfReport</a>
			<?php echo getLabel("label.footer.message"); ?>
		</div>
	</div>
</footer> <!-- !#footer -->

<!-- jQuery -->
<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- jQuery-ui -->
<script src="/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="/bower_components/metisMenu/dist/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="/bower_components/startbootstrap-sb-admin-2/dist/js/sb-admin-2.js"></script>
<!-- EONWEB variables -->
<script src="/js/eonweb.js"></script>
<!-- EORWEB menu -->
<script src="/js/side.js"></script>
<!-- EORWEB traduction -->
<?php 
	# Include javascript dictionnary
	$t->createJSDictionnary();
?>

	<!-- DataTables JavaScript -->
<script src="/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="/bower_components/datatables-responsive/js/dataTables.responsive.js"></script>
<script src="/js/datatable.js"></script>

</body>

</html>
