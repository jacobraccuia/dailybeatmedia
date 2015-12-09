<?php


if(isset($_POST['refresh_manual_calls'])) {
	set_transient('manual_call_insta', 0, 60 * 60 * 24 * 7 * 12);
	set_transient('manual_call_twitter', 0, 60 * 60 * 24 * 7 * 12);

}


$manual_call_insta = get_transient('manual_call_insta');
$manual_call_twitter = get_transient('manual_call_twitter'); 



?>

<style>

	#wpcontent, #wpfooter { background:white; }
	.now-feed h3 em { font-size:14px; font-weight:400; }

	.now-feed ol { margin-top:20px; }
	.now-feed h5 { font-weight:bold; font-size:15px; }

	.now-feed li { padding:5px 10px; margin:0px; width:75%; }
	.now-feed li:nth-child(odd) { background:#efefef; }
	.now-feed input { border:0px; border-radius:5px; padding:5px 10px; }

	form { margin:20px 0px; }

</style>



<div class="wrap now-feed">

	<div id="icon-upload" class="icon32"></div>
	<h2 class="cap">Now Feed Admin</h2>

	<a href="https://twitter.com/DailyBeat/lists" target="_blank">View Twitter list to see what we should be seeing.</a>

	<br/>
	<br/>
	Manual Twitter calls: <?php echo $manual_call_twitter; ?><br/>
	Manual Instagram calls: <?php echo $manual_call_insta; ?>

	<form method="POST">
		<input type="submit" name="refresh_manual_calls" value="refresh manual call numbers" />
	</form>

	<div class="row">
		<div class="col-md-3">

			<h5>Twitter Cache</h5>
			
			<?php

			$all_tweets = get_transient('tweets');
			$time = get_transient('tweets_time');
			echo humanTiming($time);

			if(!is_array($all_tweets) || !isset($all_tweets[0]->created_at)) {
				echo '<pre>'; print_r($all_tweets); echo '</pre>';
			}

			$count = count($all_tweets);
			echo '<div class="count">' . $count . ' tweets cached.</div>';
			echo '<ol>';
			foreach($all_tweets as $tweet) {
				echo '<li>' . tweets($tweet) . '</li>';
			}
			echo '</ol>';

			?>		

		</div>

		<div class="col-md-3">

			<h5>Twitter Backup Cache</h5>
			<?php
			
			$all_tweets = get_transient('tweets_backup');
			$time = get_transient('tweets_backup_time');

			echo humanTiming($time);
			echo '<br/>';			
			if(!is_array($all_tweets) || !isset($all_tweets[0]->created_at)) {
				echo '<pre>'; print_r($all_tweets); echo '</pre>';
			}

			$count = count($all_tweets);
			echo '<div class="count">' . $count . ' tweets cached.</div>';
			echo '<ol>';
			foreach($all_tweets as $tweet) {
				echo '<li>' . tweets($tweet) . '</li>';
			}
			echo '</ol>';
			?>	
		</div>

		<div class="col-md-3">
			<h5>Instagram Cache</h5>
			<?php
			$all_instas = get_transient('instas');
			$time = get_transient('instas_time');

			echo humanTiming($time);
			echo '<br/>';		
			if(!is_array($all_instas) || !isset($all_instas[0]->created_time)) {
				echo '<pre>'; print_r($all_instas); echo '</pre>';
			}


			usort($all_instas, 'now_feed_cmp');

			$count = count($all_instas);
			echo '<div class="count">' . $count . ' instas cached.</div>';
			echo '<ol>';
			foreach($all_instas as $insta) {
				echo '<li>' . instas($insta) . '</li>';
			}
			echo '</ol>';
			?>	
		</div>

		<div class="col-md-3">
			<h5>Instagram Backup Cache</h5>
			<?php
			$all_instas = get_transient('instas_backup');
			$time = get_transient('instas_backup_time');

			echo humanTiming($time);
			echo '<br/>';		

			if(!is_array($all_instas) || !isset($all_instas[0]->created_time)) {
				echo '<pre>'; print_r($all_instas); echo '</pre>';
			}

			usort($all_instas, 'now_feed_cmp');

			$count = count($all_instas);
			echo '<div class="count">' . $count . ' instas cached.</div>';
			echo '<ol>';
			foreach($all_instas as $insta) {
				echo '<li>' . instas($insta) . '</li>';
			}
			echo '</ol>';
			?>	
		</div>
	</div>

	<?php 

	function tweets($tweet) {

		$datetime = new DateTime($tweet->created_at); 
		$unix_date = $datetime->format('U');
		$timestamp = human_time_diff($unix_date, gmdate('U')); 

		$return = '<a href="http://twitter.com/' . $tweet->user->screen_name . '" target="_blank">' . $tweet->user->name . '</a>';
		$return .= '<br/><span class="timestamp">' . $timestamp . '</span>';

		return $return;
	}

	function instas($insta) {


		$unix_date = $insta->created_time;
		$timestamp = human_time_diff($unix_date, gmdate('U')); 

		$username = $insta->user->username;

		$return = '<a href="http://instagram.com/' . $username . '" target="_blank">' . $username . '</a>';
		$return .= '<br/><span class="timestamp">' . $timestamp . '</span>';

		return $return;
	}



	function now_feed_cmp($a, $b) {
		if ($a->created_time == $b->created_time) {
			return 0;
		}
		return ($a->created_time > $b->created_time) ? -1 : 1;
	}


	function humanTiming($time) {

	    $time = time() - $time; // to get the time since that moment
	    $time = ($time<1)? 1 : $time;
	    $tokens = array (
	    	31536000 => 'year',
	    	2592000 => 'month',
	    	604800 => 'week',
	    	86400 => 'day',
	    	3600 => 'hour',
	    	60 => 'minute',
	    	1 => 'second'
	    	);

	    foreach ($tokens as $unit => $text) {
	    	if ($time < $unit) continue;
	    	$numberOfUnits = floor($time / $unit);
	    	return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	    }
	}



	?>

</div>