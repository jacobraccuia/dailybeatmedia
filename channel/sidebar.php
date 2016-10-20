<div class="col-fixed col-fixed-right pull-right" id="sidebar">
	

	<div class="col-header">
		<div class="bar"></div>
		<div class="logo dblive"><span>DB</span>Live</div>
		<div class="bar right"></div>
	</div>
	<?php 
	$feed = new NowFeed();	
	$feed->getNowFeed(array('limit' => 12, 'ad' => true, 'image_cutoff' => 4, 'unique_class' => 'top-home-nowfeed'));
	?>


</div>