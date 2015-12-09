<?php

	//if(is_home()) {
		get_ad('skin');
	//}
?>

<div class="footer-bg-wrapper"><div class="footer-bg-img"></div></div>
<div id="footer_wrapper">
	<div id="footer" class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-4 col-sm-12">
					</div>
					<div class="col-md-8 col-sm-12 super-tight">
						<div class="footer_bio"><p>Stemming from its roots as a Facebook group in 2012, TRC brings Toronto-area electronic music fans together as one community.  The platform is an extension of this mission, providing a fully-integrated and unified ecosystem for electronic music fans to connect, and be the trusted source for local talent, events, and global music news through community-driven editorial and podcasting.</p></div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="sound_wave"></div>
						<div class="copy">&COPY; <?php echo date("Y"); ?> Toronto Rave Community. All Rights Reserved.
						<a href="/privacy-policy">Privacy</a> | <a href="/terms-conditions">Terms & Conditions</a><a href=""></a></div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<ul class="social">
					<li class="facebook"><a href="http://www.facebook.com/TorontoRaveCommunity"><div class="hover_overlay"></div></a></li>
					<li class="twitter_icon"><a href="https://twitter.com/TorontoTRC"><div class="hover_overlay"></div></a></li>
					<li class="instagram"><a href="http://instagram.com/torontoravecommunity"><div class="hover_overlay"></div></a></li>
					<li class="wordpress"><a href="/wp-login.php"><div class="hover_overlay"></div></a></li>
				<!--	<li class="mail"><a href="mailto:dailybeat@beaconrecords.com"><div class="hover_overlay"></div></a></li>-->
				</ul>
				<ul class="contact">
					<li>Submissions:<a href="mailto:TRC@daily-beat.com">TRC@daily-beat.com</a></li>
					<li>Advertising:<a href="mailto:Andrew@daily-beat.com">Andrew@daily-beat.com</a></li>
					<li>Writing Inquiries:<a href="mailto:Kurtis@daily-beat.com">Kurtis@daily-beat.com</a></li>
					<li>Photography & Press:<a href="mailto:Andrew@daily-beat.com">Andrew@daily-beat.com</a></li>
				</ul>
			</div>
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
	<img src="//pixel.quantserve.com/pixel/p-RG3L5DJBmrTBa.gif" border="0" height="1" width="1" alt="Quantcast" style="display:none;s"/>
	</div>
</noscript>
<!-- End Quantcast tag -->
</body>
</html>