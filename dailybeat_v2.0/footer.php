<?php
	
	global $post;
	$id = $post->ID;
	$db_soundcloud_color = get_post_meta($id, 'db_soundcloud_color', true);

	if(empty($db_soundcloud_color)) { $db_soundcloud_color = ""; }

?>

<div id="footer_wrapper" style="border-top-color:<?php echo $db_soundcloud_color; ?>!important;">
	<div id="footer" class="container">
		<div class="col-md-8 col-xs-12 super_tight">
				<div class="db_logo hidden-xs"><a href="<?php echo home_url(); ?>"></a></div>
				<div class="footer_bio"><p>Daily Beat was launched in 2012 in a social media capacity to serve local artists under a boutique record label. Since its launch, Daily Beat has expanded into a leading global youth media company with representatives in over 20 countries.</p></div>
				<div class="sound_wave"></div>
				<div class="copy">&COPY; <?php echo date("Y"); ?> BEACON MEDIA GROUP</div>		
			</div>
		<div class="col-md-4 super_tight">
			<ul class="social">
				<li class="facebook"><a href="http://www.facebook.com/BeaconDailyBeat"><div class="hover_overlay"></div></a></li>
				<li class="twitter_icon"><a href="https://twitter.com/beacondailybeat"><div class="hover_overlay"></div></a></li>
				<li class="youtube"><a href="http://www.youtube.com/channel/UCmx4TjsF0v38iE1kO1zSMuw"><div class="hover_overlay"></div></a></li>
				<li class="instagram"><a href="http://instagram.com/beacondailybeat"><div class="hover_overlay"></div></a></li>
				<li class="wordpress"><a href="http://daily-beat.com/wp-login.php"><div class="hover_overlay"></div></a></li>
			<!--	<li class="mail"><a href="mailto:dailybeat@beaconrecords.com"><div class="hover_overlay"></div></a></li>-->
			</ul>
			<ul class="contact">
				<li>Submission:<a href="mailto:DailyBeat@beaconrecords.com">DailyBeat@beaconrecords.com</a></li>
				<li>Advertising:<a href="mailto:Anuj@beaconrecords.com">Anuj@beaconrecords.com</a></li>
				<li>Writing Inquiries:<a href="mailto:Chris@beaconrecords.com">Chris@beaconrecords.com</a></li>
				<li>Photography & Press:<a href="mailto:Ashlyn@daily-beat.com">Ashlyn@daily-beat.com</a></li>
			</ul>
		</div>
	</div>
</div>
<?php wp_footer(); ?>	

<!-- Quantcast Tag -->
<script type="text/javascript">
	var _qevents = _qevents || [];

	(function() {
	var elem = document.createElement('script');
	elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
	elem.async = true;
	elem.type = "text/javascript";
	var scpt = document.getElementsByTagName('script')[0];
	scpt.parentNode.insertBefore(elem, scpt);
	})();

	_qevents.push({
		qacct:"p-RG3L5DJBmrTBa"
	});
</script>
<noscript>
	<div style="display:none;">
	<img src="//pixel.quantserve.com/pixel/p-RG3L5DJBmrTBa.gif" border="0" height="1" width="1" alt="Quantcast"/>
	</div>
</noscript>
<!-- End Quantcast tag -->
</body>
</html>