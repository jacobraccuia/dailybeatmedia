<?php

require_once('player.php');

function dbm_header() {
	dbm_navbar();
	dbm_channel_list();
	dbm_player();
}

function dbm_navbar() { ?>

	<div class="navbar-wrapper">
		<nav class="navbar navbar-default navbar-fixed-top" id="navbar">
			<div id="page-progress"></div>
			<div class="nav-border"></div>

			<div class="navbar-header">
				<div id="veggie" class="toggle-menu menu-left push">
					<div class="veggieburger">	
						<span></span>
						<span></span>
						<span></span>
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>
				<a class="navbar-brand <?php echo get_current_site_subdomain(); ?>" href="<?php echo home_url(); ?>">
					<span class="svg svg-icon svg-<?php echo get_current_site_subdomain(); ?>-icon-color"></span><img src="<?php echo PLUGIN_DIR; ?>images/svg/logos/logo_color/<?php echo get_current_site_subdomain(); ?>.svg" />
				</a>

				<ul class="navbar-extras">
					<li class="navbar-mobile search-wrapper">
						<ul>
							<li class="header-search">
								<?php get_search_form(); ?>
							</li>
							<li class="h-icon search" data-toggle="search-slide">
								<i class="fa fa-times fa-fw"></i>
								<i class="fa fa-search fa-fw"></i>
							</li>
						</ul>
					</li>
					<li class="h-icon divider">&nbsp;</li>
					<li class="social-header-icons">
						<ul>
							<li class="h-icon facebook"><a href="https://www.facebook.com/groups/torontoravecommunity/" target="_blank"><i class="fa fa-fw fa-facebook"></i></a></li>
							<li class="h-icon twitter"><a href="https://twitter.com/torontorc" target="_blank"><i class="fa fa-fw fa-twitter"></i></a></li>
							<li class="h-icon instagram"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-instagram"></i></a></li>
							<li class="h-icon soundcloud"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-soundcloud"></i></a></li>
							<li class="h-icon youtube"><a href="http://instagram.com/torontoravecommunity" target="_blank"><i class="fa fa-fw fa-youtube-play"></i></a></li>
							<li class="h-icon divider">&nbsp;</li>
						</ul>
					</li>
					<li class="navbar-player">
						<ul>
							<li>
								<div class="album">
									<div class="art"></div>
									<div class="play-overlay">
										<span class="fa-stack">
											<i class="fa fa-circle fa-stack-2x"></i>
											<i class="fa fa-play-circle fa-stack-1x"></i>
											<i class="fa fa-pause-circle fa-stack-1x"></i>
										</span>
									</div>
								</div>
							</li>
							<li class="track-info">
								<h3></h3>
								<h4></h4>
							</li>
						</ul>
					</li>
					<li class="h-icon beatsxtra" id="player_button"><i class="svg svg-beatsxtra"></i><i class="svg svg-beatsxtra-icon"></i></li>
				</ul>
			</div>

		</nav>
	</div>

	<?php }

	function get_current_site_subdomain() {
		$blogId = get_current_blog_id();
		if($blogId == 1) { return 'db'; }

		$blog_info = get_blog_details($blogId);
		$blog_name = explode('.', $blog_info->domain)[0];

		return $blog_name;
	}



	function dbm_channel_list() { ?>

		<nav id="cabinet" class="cbp-spmenu-left">
			<?php

			$channels = array(
				'db' => array('Daily Beat', 'http://daily-beat.com'),
				'attack' => array('Attack', 'http://daily-beat.com'),
				'beatmersive' => array('Beatmersive', 'http://beatmersive.com'),
				'headliners' => array('Headliners Tribune', 'http://dailybeatmedia.com'),
				'fnt' => array('Fresh New Tracks', 'http://freshnewtracks.com'),
				'rafting' => array('Raver Rafting', 'http://raverrafting.daily-beat.com'),
				'trc' => array('Toronto Rave Community', 'http://trc.daily-beat.com')
				);		

			$corporate = array(
				'dbm' => array('Daily Beat Media', 'http://dailybeatmedia.com')
				);

			unset($channels[get_current_site_subdomain()]);

			?>	

			<h2><span>Channels</span></h2>
			<ul class="sites">
				<?php foreach($channels as $key => $logo) {
					echo '<li><a href="' . $logo[1] .'" title="' . $logo[0] . '">';
					echo '<span class="svg svg-icon svg-' . $key . '-icon-grey"></span><span class="svg svg-logo svg-' . $key . '-logo-grey"></span>';		
					echo '</a></li>';
					echo '<li class="divider"></li>';
				}
				?>
			</ul>

			<h2><span>Corporate</span></h2>
			<ul class="sites">
				<?php foreach($corporate as $key => $logo) {
					echo '<li><a href="' . $logo[1] .'" title="' . $logo[0] . '">';
					echo '<span class="svg svg-icon svg-' . $key . '-icon-grey"></span><span class="svg svg-logo svg-' . $key . '-logo-grey"></span>';		
					echo '</a></li>';
					echo '<li class="divider"></li>';
				}
				?>
			</ul>
		</nav>
		<div id="cabinet-overlay"></div>


		<?php }


		function db_channel_guide() {

			$blog_id = get_current_blog_id();


			$logos = array(
				'5' => array(beatmersive_logo(), 'Beatmersive', 'http://beatmersive.com'),
				'1' => array(dailybeat_logo(), 'Daily Beat', 'http://daily-beat.com'),
				'3' => array(dailybeatmedia_logo(), 'Daily Beat Media', 'http://dailybeatmedia.com'),
	//	'0' => array('/images/db_first.png', 'DB First'),
				'4' => array('/images/lightedmusicgroup.png', 'Lighted Music Group', 'http://lightedmusicgroup.com'),
				'6' => array(raverrafting_logo(), 'Raver Rafting', 'http://raverrafting.daily-beat.com'),
				'2' => array(trc_logo(), 'Toronto Rave Community', 'http://trc.daily-beat.com'),
				);

			unset($logos[$blog_id]);


			?>	
			<div class="guide-wrapper">
				<?php /*	<a class="navbar-brand" href="<?php echo home_url(); ?>"><img src="<?php echo $current_logo; ?>" alt="logo" /></a> */ ?>
				<!-- <div id="toggle-channels" class="open-list" title="View DB Channels"><i class="fa fa-caret-down fa-fw"></i><i class="fa fa-caret-up fa-fw" style="display:none;"></i></div> -->
				<ul id="channel-list" class="channels">
					<?php foreach($logos as $key => $logo) {
				//$url = get_home_url($key);
						echo '<li><a href="' . $logo[2] .'">';
						if(substr($logo[0], 0, 1) === '/') echo '<img src="' . plugins_url($logo[0] , __FILE__) . '" title="' . $logo[1] . '" />';
						else echo $logo[0];
						echo '</a></li>';
					}
					?>
				</ul>

			</div>

			<?php	
		}

		function db_search() { ?>

			<div id="search-dropdown" class="dropdown-content">
				<?php $ph = "type to search"; ?>
				<form action="<?php echo home_url(); ?>" class="search-wrapper" method="get">
					<label class="sr-only" for="s">Search</label>
					<span class="search-icon"><i class="fa fa-search"></i></span>
					<input type="text" class="search-input" id="s" name="s" autocomplete="off" 
					value="<?php echo $ph; ?>"
					onfocus="if(this.value=='<?php echo $ph; ?>')this.value='';"
					onblur="if(this.value=='')this.value='<?php echo $ph; ?>'"
					placeholder="<?php echo $ph; ?>"
					/>
				</form>		
			</div>


			<?php
		}



		?>