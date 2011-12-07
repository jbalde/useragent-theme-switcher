<?php
	$pathToIcon = $this->blogUrl . '/wp-content/plugins/user-agent-theme-switcher/icon/';
?>
<script type="text/javascript" src="<?php echo $this->blogUrl; ?>/wp-content/plugins/user-agent-theme-switcher/js/jqueryui.js"></script>
<link href="<?php echo $this->blogUrl; ?>/wp-content/plugins/user-agent-theme-switcher/css/user-agent-theme-switcher.css" rel="stylesheet" type="text/css" />
<div class="wrap">
	<h2>Browsers</h2>
	<table class="widefat page fixed" width="100%" cellpadding="3" cellspacing="3">
		<thead>
			<tr>
				<th class="manage-column" scope="col" width="64">Icon</th>
				<th class="manage-column" scope="col">Code</th>
				<th class="manage-column" scope="col">Browser</th>
				<th class="manage-column" scope="col">Tags</th>
				<th class="manage-column" scope="col">Theme</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$countBrowersWithTheme = count($browsersWithTheme);

			for($i = 0; $i < $countBrowersWithTheme; $i++) {
			?>
			<tr <?php if($i % 2 == 0) { echo 'class="alternate"'; } ?>>
				<td><img class="widefat" src="<?php echo $this->blogUrl; ?>/wp-content/plugins/user-agent-theme-switcher/icon/<?php echo $browsersWithTheme[$i]->getCode(); ?>.png" width="50" height="50" /></td>
				<td style="line-height: 50px;"><strong><?php echo $browsersWithTheme[$i]->getCode(); ?></strong></td>
				<td style="line-height: 50px;"><strong><?php echo $browsersWithTheme[$i]->getName(); ?></strong></td>
				<td style="line-height: 50px;"><?php echo $browsersWithTheme[$i]->getTagsAsString(); ?></td>
				<td style="line-height: 50px;"><?php echo $browsersWithTheme[$i]->getTheme(); ?></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<h2>Sync browser with theme</h2>
	<div style="overflow: hidden;">
		<div id="browsers" class="postbox uafilters">
			<?php
				$countBrowersWithoutTheme = count($browsersWithoutTheme);

				for($i = 0; $i < $countBrowersWithoutTheme; $i++) {
					echo '<img src="'.$pathToIcon.$browsersWithoutTheme[$i]->getCode().'.png" />';
				}
			?>
		</div>
		<div id="filters" class="postbox uafilters">

		</div>
	</div>
	<div class="postbox" style="overflow: hidden;">
		<div style="margin: 7px; float: left;">
			<form method="get" action="<?php echo $this->blogUrl; ?>/wp-admin/admin.php">
				<input type="hidden" name="page" value="<?php echo UserAgentThemeSwitcher::PAGE_TEMPLATE; ?>" />
				<input type="hidden" name="action" value="<?php echo UserAgentThemeSwitcher::ACTION_SYNCBROWSER; ?>" />
				<table cellpadding="5" cellspacing="5">
					<tr>
						<td>Browser</td>
						<td>
							<select name="browser">
							<?php
								$countBrowersWithoutTheme = count($browsersWithoutTheme);

								for($i = 0; $i < $countBrowersWithoutTheme; $i++) {
									echo '<option value="'.$browsersWithoutTheme[$i]->getCode().'">'.$browsersWithoutTheme[$i]->getName().'</option>';
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Theme</td>
						<td>
							<select name="theme">
							<?php
								foreach($themes as $key => $theme ) {
								?>
									<option value="<?php echo $theme['Name']; ?>"><?php echo $theme['Name']; ?></option>
								<?php
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;"><input type="submit" name="save" class="button bold" value="Save"></td>
					</tr>
				</table>
			</form>
		</div>
		<div style="margin: 7px 7px 7px 100px; float: left;">
			<form method="get" action="<?php echo $this->blogUrl; ?>/wp-admin/admin.php">
				<input type="hidden" name="page" value="<?php echo UserAgentThemeSwitcher::PAGE_TEMPLATE; ?>" />
				<input type="hidden" name="action" value="<?php echo UserAgentThemeSwitcher::ACTION_SYNCTAG; ?>" />
				<table cellpadding="5" cellspacing="5">
					<tr>
						<td>Tags</td>
						<td>
							<select name="tag">
							<?php
								$countTags = count($tags);

								for($i = 0; $i < $countTags; $i++) {
								echo '<option value="'.$tags[$i].'">'.$tags[$i].'</option>';
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Theme</td>
						<td>
							<select name="theme">
							<?php
								foreach($themes as $key => $theme ) {
								?>
									<option value="<?php echo $theme['Name']; ?>"><?php echo $theme['Name']; ?></option>
								<?php
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;"><input type="submit" name="save" class="button bold" value="Save"></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<h2>Browsers rules</h2>
	<table class="widefat page fixed" width="100%" cellpadding="3" cellspacing="3">
		<thead>
			<tr>
				<th class="manage-column" scope="col" width="64">Icon</th>
				<th class="manage-column" scope="col">Code|Tag</th>
				<th class="manage-column" scope="col">Theme</th>
				<th class="manage-column" scope="col">Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$countRules = count($rules);

			for($i = 0; $i < $countRules; $i++) {
			?>
			<tr <?php if($i % 2 == 0) { echo 'class="alternate"'; } ?>>
				<td><img class="widefat" src="<?php echo $this->blogUrl; ?>/wp-content/plugins/user-agent-theme-switcher/icon/<?php echo $rules[$i]->code; ?>.png" width="50" height="50" /></td>
				<td style="line-height: 50px;"><strong><?php echo $rules[$i]->code; ?></strong></td>
				<td style="line-height: 50px;"><strong<?php echo $rules[$i]->theme; ?></strong></td>
				<td style="line-height: 50px;">
				<a href="admin.php?page=<?php echo UserAgentThemeSwitcher::PAGE_TEMPLATE; ?>&action=<?php echo UserAgentThemeSwitcher::ACTION_DELETERULE; ?>&code=<?php echo $rules[$i]->code; ?>">delete</a>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<?php include('useragent-donation.php'); ?>
</div>
<br/>
<br/>
<br/>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#browsers > img, #filters > img").draggable();

		jQuery("#filters").droppable({
			drop: function( event, ui ) {
				var drag = jQuery(ui.draggable);
				drag.css("top", 0);
				drag.css("left", 0);
				drag.css("position", "inline");
				drag.appendTo(this);
			}
		});

		jQuery("#browsers").droppable({
			drop: function( event, ui ) {
				var drag = jQuery(ui.draggable);
				drag.css("top", 0);
				drag.css("left", 0);
				drag.css("position", "inline");
				drag.appendTo(this);
			}
		});
	});
</script>