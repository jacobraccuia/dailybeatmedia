<?php

if(isset($_POST['trending_post_tags'])) {
	
	$data = stripslashes_deep($_POST);
	
	$trending_post_tags = $data['trending_post_tags'];
	$trending_logo = $data['trending_logo'];

	$trending2_post_tags = $data['trending2_post_tags'];
	$trending2_logo = $data['trending2_logo'];
	
	$trending3_post_tags = $data['trending3_post_tags'];
	$trending3_logo = $data['trending3_logo'];
	
	update_option('trending_post_tags', $trending_post_tags);
	update_option('trending_logo', $trending_logo);
	
	update_option('trending2_post_tags', $trending2_post_tags);
	update_option('trending2_logo', $trending2_logo);
	
	update_option('trending3_post_tags', $trending3_post_tags);
	update_option('trending3_logo', $trending3_logo);

}

?>

<style>
	label { width:150px; text-align:right; margin-right:15px; display:inline-block; }
	.wp-admin select { width:140px; border-radius:5px; padding:7px; height:auto; }
	textarea { width:250px; height:100px; padding:7px; border-radius:5px; }

	h3 em { font-size:14px; font-weight:400; }

</style>



<div class="wrap outage-alerts-admin">

	<div id="icon-upload" class="icon32"></div>
	<h2 class="cap">Trending Post Settings</h2>

	<br/>


	<?php
	
	if(isset($_POST['trending_posts'])) { echo '<div style="color:green;">Trending Post Settings have been updated successfully.</div>'; }

	
	$trending_post_tags = get_option('trending_post_tags');
	$trending_logo = get_option('trending_logo');

	$trending2_post_tags = get_option('trending2_post_tags');
	$trending2_logo = get_option('trending2_logo');

	$trending3_post_tags = get_option('trending3_post_tags');
	$trending3_logo = get_option('trending3_logo');

	?>	

	<br/>
	
	<form name="trending_posts" method="POST">
		
		<h3>Main Trending</h3>
		<label>Trending Post Tags: <em>comma separated list</em></label>
		<textarea style="vertical-align:top;" name="trending_post_tags"><?php echo $trending_post_tags; ?></textarea>
		<br/><br/>
		<label>Trending Post Logo</label>
		<input id="trending_logo" type="text" size="36" name="trending_logo" value="<?php echo $trending_logo; ?>" />
		<input class="upload_image_button" data-target="trending_logo" type="button" value="Upload Logo" />
		<br/><br/><br/>

		<h3>Secondary Trending <em>(standard, pick something like events or interviews)</em></h3>
		<label>Trending Post Tags: <em>comma separated list</em></label>
		<textarea style="vertical-align:top;" name="trending2_post_tags"><?php echo $trending2_post_tags; ?></textarea>
		<br/><br/>
		<label>Trending Post Logo</label>
		<input id="trending2_logo" type="text" size="36" name="trending2_logo" value="<?php echo $trending2_logo; ?>" />
		<input class="upload_image_button" data-target="trending2_logo" type="button" value="Upload Logo" />
		<br/><br/><br/>

		<h3>Third Trending Section <em>(leave tags blank to remove)</em></h3>

		<label>Trending Post Tags: <em>comma separated list</em></label>
		<textarea style="vertical-align:top;" name="trending3_post_tags"><?php echo $trending3_post_tags; ?></textarea>
		<br/><br/>
		<label>Trending Post Logo</label>
		<input id="trending3_logo" type="text" size="36" name="trending3_logo" value="<?php echo $trending3_logo; ?>" />
		<input class="upload_image_button" data-target="trending3_logo" type="button" value="Upload Logo" />
		<br/><br/><br/>


		<input class="button-primary action" type="submit" value="Update Trending Post">
	</form>

</div>