<?php 
/**
 * Template Name: OLD splash sign in
 */


get_header('trc');	
?>

<script>
jQuery(document).ready(function($) {
	
	$('.vid-wrapper').tubular({videoId: '9CfbMi1oaf4'}); // where idOfYourVideo is the YouTube ID.
	
});

</script>


<section id="full-width-premiere" style="background-image:url(<?php echo THEME_DIR; ?>/images/trc_background.jpg);" class="vid-wrapper">

<div class="trc-big-logo"><img src="<?php echo THEME_DIR; ?>/images/trc_logo_main2.png" /></div>
			
			
			<div class="tubular-play"><i class="fa fa-play-circle"></i></div>
			<div class="signup" style="display:none;">
			<!-- Begin MailChimp Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
<h2 style="color:white;">Get notified when TRC launches</h2>
<style type="text/css">
	#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
	/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
	   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
</style>
<div id="mc_embed_signup">
<form action="//beaconrecords.us8.list-manage.com/subscribe/post?u=90eeba7b59fa4c32b1d5dab94&amp;id=a49f487d2d" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
	
<div class="mc-field-group">
	<label for="mce-EMAIL">Email Address </label>
	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
</div>
<div class="mc-field-group">
	<label for="mce-FNAME">First Name </label>
	<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
</div>
<div class="mc-field-group">
	<label for="mce-LNAME">Last Name </label>
	<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
</div>
	<div id="mce-responses" class="clear">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;"><input type="text" name="b_90eeba7b59fa4c32b1d5dab94_a49f487d2d" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
    </div>
</form>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->
			</div>


</section>
		

<?php get_footer('trc'); ?>