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
		<div id="filters" style="width: 48%; height: 300px; border: solid 1px #000000; float: right;">

		</div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#browsers > img").draggable();

				jQuery("#filters").droppable({
					drop: function( event, ui ) {
						var drag = $(ui.draggable);
						drag.css("top", 0);
						drag.css("left", 0);
						drag.css("position", "static");
						drag.appendTo(this);
					}
				});

			});
		</script>
	</body>
</html>