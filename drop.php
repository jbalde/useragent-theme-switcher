<html>
	<head>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/jqueryui.js"></script>
	</head>
	<body>
		<div id="browsers" style="width: 48%; height: 300px; border: solid 1px #000000; float: left;">
			<img src="icon/ie9.png" />
			<img src="icon/opera.png" />
			<img src="icon/firefox.png" />
			<img src="icon/chrome.png" />
		</div>
		<div style="width: 48%; height: 300px; border: solid 1px #000000; float: right;">

		</div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#browsers > img").draggable();
				//var browsersImages = jQuery("#browsers > img");
				/*browsersImages.mousedown(function() {
					jQuery(this).style("position", "absolute");
				});

				browsersImages.mousemove(function(e){
					var element = jQuery(e.target)[0];

					if(element.style("position") == "absolute") {
						element.style("top", e.pageY);
						element.style("left", e.pageX);
					}
				});*/
			});
		</script>
	</body>
</html>