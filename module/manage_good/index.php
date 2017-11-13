<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>jQuery ModalVideo Plugin Demos</title>

	<link href="modal-videos.css" rel="stylesheet">
	<link href="mike.css" rel="stylesheet">
	
</head>
<body>
	<div class="full">
		<div class="header">
			<h1>The team....</h1>
		</div>
		</div>
		
		<div class="main">
			<div class="row">
				<div class="col-md-4">
					<h2>Mp4 videos</h2>
					<div>
						<a href="./APX_AXIANS_Cloud_Builder_2015.mp4">
							<img class="img-thumbnail" src="./web-video-icon.jpg"/>
						</a>
					</div>
				</div>
				
			</div>
			
		</div>

	</div>

	<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
	<script src="./modal-videos.js"></script>
	
	<script>
		"use strict";

		$(document).ready(function () {
		
			//each video has need its own instance of modalVideoOptions  
			$('a[href]').each(function(){
				$(this).modalvideo(new ModalVideoOptions());
			});
		});
	</script>
</body>
</html>
