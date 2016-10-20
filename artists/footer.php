<?php

	//if(is_home()) {
	//	get_ad('skin');
	//}
?>

<div id="footer-wrapper">
	<footer class="container">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4"></div>
			<div class="col-md-4"></div>
		</div>
		<div class="row">

			<div class="col-md-12">
				<p class="copyright">Passionately supporting the electronic community since 2012. DAILY BEAT MEDIA, LLC &copy; 2012 - 2015</p>
			</div>
		</div>
	</footer>
	<ul class="brand-wrapper">
		<li class="svg svg-dbm"></li>
		<li class="svg svg-trc"></li>
		<li class="svg svg-rafting"></li>
		<li class="svg svg-beatsxtra"></li>
		<li class="svg svg-db"></li>
	</ul>
</div>
<?php
global $broken_switch_posts, $reason, $exclude_posts; 
echo ($broken_switch_posts === true) ? ':(' : ':)'; ?>
<br/> <?php echo $reason; ?> 
<br/> Manual Now Feed Calls: insta - <?php echo get_transient('manual_call_insta'); ?>, twitter - <?php echo get_transient('manual_call_twitter'); ?>
<?php wp_footer(); ?>
</body>
</html>