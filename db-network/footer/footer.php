<?php

function dbm_footer() {
	dbm_get_footer();
}

function dbm_get_footer() { ?>

<?php

	//if(is_home()) {
	//	get_ad('skin');
	//}
?>

<footer id="footer-wrapper">
	<div class="footer-border"></div>
	<ul class="external-links">
		<li><a href="http://dailybeatmedia.com"><div class="svg dbm-badge"></div></a></li>
		<li><a href="#"><div class="svg skull"></div></a></li>
		<li><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"><div class="rick"></div></a></li>
	</ul>
	<div class="col-md-3 col-logo">
		<a href="http://dailybeatmedia.com"><div class="svg logo"></div></a>
		<div class="copy">&copy;2016 Daily Beat Media All Rights Reserved</div>
	</div>
	<div class="col-md-9 col-links">
		<div class="col-channels">
			<h2>Channels</h2>
			<div class="divider"></div>
			<div class="col-md-4">
				<ul class="links">
					<li><a href="http://daily-beat.com">Daily Beat</a></li>
					<li><a href="http://beatsmersive.com">Beatsmersive</a></li>
					<li><a href="">BeatsXtra</a></li>
				</ul>
			</div>
			<div class="col-md-4">
				<ul class="links">
					<li><a href="#">Attack Mag</a></li>
					<li><a href="#">Raver Rafting</a></li>
					<li><a href="#">TRC</a></li>
				</ul>
			</div>
			<div class="col-md-4">
				<ul class="links">
					<li><a href="#">Headliners Tribune</a></li>
					<li><a href="#">Fresh New Tracks</a></li>
				</ul>
			</div>
		</div>
		<div class="col-info">
			<h2>Info</h2>
			<div class="divider"></div>
			<div class="row">
				<div class="col-md-6">
					<ul class="links">
						<li><a href="#">About</a></li>
						<li><a href="#">Advertising</a></li>
						<li><a href="#">Careers</a></li>
					</ul>
				</div>
				<div class="col-md-6">
					<ul class="links">
						<li><a href="#">Contact</a></li>
						<li><a href="#">Press Inquiries</a></li>
						<li><a href="#">Submissions</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-corporate">
			<h2>Corporate</h2>
			<div class="divider"></div>
			<div class="row">
				<div class="col-xs-12">
					<ul class="links">
						<li><a href="http://dailybeatmedia.com">Daily Beat Media</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>

<?php } ?>