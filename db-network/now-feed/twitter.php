<?php 

require_once('TwitterAPIExchange.php');

function linkify_tweet($tweet) {

	$tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
	$tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
	$tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);

	return $tweet;

}



$x = 1;
$settings = array(
	'oauth_access_token' => '132239128-o5xONmZNeOCXbXMhaUax9snOAAn8Ip3xlwt8ZUiQ',
	'oauth_access_token_secret' => 'IaSB76sgxNoZDWEv7CZ2D2EsiXUyxC8yYBLPFI3Rnld8I',
	'consumer_key' => '3u3rlmwHbMVCSU4Pj4eJLoJg5',
	'consumer_secret' => 'RiVPz8sImWRzkVvbI0WzDdjTpS5gA73NJP2aL2BDrDxIRxVJIz'
	);


$url = 'https://api.twitter.com/1.1/lists/statuses.json';
$getfield = '?list_id=217168616&include_rts=false&include_entities=true&count=20';

$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);
$results = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();

$tweets = json_decode($results);
foreach($tweets as $tweet) {

	$tweet_text = linkify_tweet($tweet->text);

	$media_url = '';
	if(isset($tweet->extended_entities->media)) {
		foreach ($tweet->extended_entities->media as $media) {
 	       $media_url = $media->media_url; // Or $media->media_url_https for the SSL version.
 	   }
 	}

 	$datetime = new DateTime($tweet->created_at); 


//	$datetime->setTimezone(new DateTimeZone('UTC'));
 	$unix_date = $datetime->format('U');

//	$unix_date = strtotime($tweet->created_at);
 	$timestamp = human_time_diff($unix_date, gmdate('U')); 

 	?>
 	<div class="widget twitter">
 		<a class="profile" href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank"><img src="<?php echo $tweet->user->profile_image_url; ?>" /></a>
 		<h5><a href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank"><?php echo $tweet->user->name; ?></a></h5>
 		<h6><a href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank">@<?php echo $tweet->user->screen_name; ?></a></h6>
 		
 		<p><?php echo $tweet_text; ?></p>
 		<?php if($media_url != '') {
 			echo '<div class="featured-image" style="background-image:url(' . $media_url .');"></div>';
 		} ?>
 		<div class="meta">
 			<a href="http://twitter.com" target="_blank"><i class="fa fa-fw fa-twitter"></i></a>
 			<span class="timestamp"><?php echo $timestamp; ?></span>
 			<div class="web-intents">
 				<a class="reply" href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet->id; ?>"></a>
 				<a class="retweet" href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet->id; ?>"></a>
 				<a class="favorite" href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet->id; ?>"></a>
 			</div>
 		</div>
 	</div>
 	<?php 

 	if($x == 1) { ?>


 	<?php
$x++;
 	}

 }











 ?>