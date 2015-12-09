<?php 

require_once('InstagramAPIExchange.php');

use MetzWeb\Instagram\Instagram;

$instagram = new Instagram('41f7ca3bb3534b9ab8f93983fc3e0b89');

$result = $instagram->getUserMedia(1540079, 10);
?>

<?php
foreach ($result->data as $media) {

	if ($media->type === 'video') { continue; }

	$avatar = $media->user->profile_picture;
	$username = $media->user->username;
	$comment = (!empty($media->caption->text)) ? $media->caption->text : '';


	$image = $media->images->low_resolution->url;
	$unix_date = $media->created_time;

	$timestamp = human_time_diff($unix_date, gmdate('U')); 

	?>

	<div class="widget instagram">

		<a class="profile" href="http://instagram.com/<?php echo $username; ?>" target="_blank"><img class="rounded" src="<?php echo $avatar; ?>" /></a>
		<h5><a href="http://instagram.com/<?php echo $username; ?>" target="_blank"><?php echo $username; ?></a></h5>

		<div class="featured-image" style="background-image:url(<?php echo $image; ?>);"></div>
		<p><?php echo $comment; ?></p>

		<div class="meta">
			<a href="http://instagram.com" target="_blank"><i class="fa fa-fw fa-instagram"></i></a>
			<span class="timestamp"><?php echo $timestamp; ?></span>
		</div>

	</div>
	<?php

}


?>