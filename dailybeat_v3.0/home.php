<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header();
?>

<div id="content">

	<section class="spotlight-wrapper">
		<?php get_spotlight_posts(); ?>
	</section>

	<section class="banner-ad">
		<?php /*	<div class=""><img src="<?php echo THEME_DIR; ?>/images/banner-ad.png" /></div> */ ?>

	</section>

	<section class="news">

		<div class="col-fixed col-fixed-left pull-left">
			<h2></h2>

			<?php $i = 1; while($i < 21) { ?>
			<div class="track track-<?php echo $i; ?>">
				<div class="track-meta">
					<h4><?php echo $i; ?></h4>
					<div class="album-art">
						<img src="http://i2.wp.com/hasitleaked.com/wp-content/uploads/2015/07/gallery_11320_2_132156.jpg?fit=320%2C320" />
					</div>
				</div>
				<div class="track-info-wrapper">
					<div class="track-info">
						<div class="song">Come Back remix ft. Kanye & Drake</div>
						<div class="artist">Deafheaven</div>
					</div>
				</div>
			</div>
			<?php $i++; } ?>

		</div>



		<div class="col-fixed col-fixed-right pull-right sticky-wrapper">
			<?php 
			$feed = new NowFeed();	
			$feed->getNowFeed(array('limit' => 12, 'ad' => true, 'image_cutoff' => 4, 'unique_class' => 'top-home-nowfeed'));
			?>
		</div>

		<div class="col-offset-center">
			<div class="row">
				<div class="col-xs-12">

					<div class="row post-wrapper trending-wrapper">

						<?php  


						$tags = get_option('trending_post_tags');
						$trending_logo = get_option('trending_logo');

						$args = array(
							'tags' => $tags,
							'trending_logo' => $trending_logo,
							'list_post_title' => '<i class="svg-trending"></i>TRENDING',
							);

						if($tags && $tags != '') {
							get_trending_posts($args);
						}

						?>

					</div>

					<div class="row post-wrapper full-width-wrapper video-wrapper">
						<?php get_video_post(); ?>
					</div>

					<div class="row post-wrapper rr-wrapper">
						<div class="col-xs-12">		
							<div class="trending-topic"><span class="svg <?php echo 'svg-rr'; //$svg; ?>"></span><span class="trending-slogan">WE ARE THE SCENE</span></div>
						</div>

						<?php get_brand_posts('raver'); ?>

					</div>

					<div class="row post-wrapper trc-wrapper">
						<div class="col-xs-12">		
							<div class="trending-topic"><span class="svg <?php echo 'svg-trc'; //$svg; ?>"></span><span class="trending-slogan">Toronto's Home for all Things Nightlife</span></div>
						</div>

						<?php get_brand_posts('trc'); ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="video-wrapper beatmersive">
		<?php get_beatmersive_posts(); ?>
	</section>


	<section class="news_continued">

		<div class="col-fixed col-fixed-left pull-left">
			<h2></h2>


			<article class="post">
				<div class="featured-image" style="background-image:url('<?php echo THEME_DIR; ?>/images/music-ad.jpg');"></div>
			</article>
		</div>

		<div class="col-fixed col-fixed-right pull-right">
			<?php $feed->getNowFeed(array('limit' => 20, 'unique_class' => 'bottom-home-nowfeed')); ?>
		</article>
	</div>

	<div class="col-offset-center">
		<div class="row">
			<div class="col-xs-12">

				<div class="row post-wrapper rr-wrapper">
					<div class="col-xs-12">		
						<div class="trending-topic"><span class="svg <?php echo 'svg-rr'; //$svg; ?>"></span><span class="trending-slogan">HEADLINERS TRIBUNE</span></div>
					</div>

					<?php get_brand_posts('daily-beat'); ?>

				</div>

				<div class="row post-wrapper trc-wrapper">
					<div class="col-xs-12">		
						<div class="trending-topic"><span class="svg <?php echo 'svg-trc'; //$svg; ?>"></span><span class="trending-slogan">DAILY BEAT PREMIERE OR SOMETHING</span></div>
					</div>

					<?php get_brand_posts('trc'); ?>
				</div>

				<div class="row post-wrapper trending-wrapper">

					<?php  

					$tags = get_option('trending2_post_tags');
					$trending_logo = get_option('trending2_logo');

					$args = array(
						'tags' => $tags,
						'trending_logo' => $trending_logo,
						);

					if($tags && $tags != '') {
						get_trending_posts($args);
					}

					?>
				</div>

				<div class="row">
					<div id="standard-posts" class="col-xs-12">
						<?php get_standard_loop(); ?>	
					</div>
					<div class="col-xs-12">
						<?php global $exclude_posts; ?>
						<script>exclude_posts = '<?php echo json_encode($exclude_posts); ?>';</script>
						<form id="load-more" method="POST" data-target="#standard-posts" action="#">
							<div class="load-posts">
								View More Posts
								<i class="fa fa-spinner fa-spin"></i>
							</div>					
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>



</div>

<?php get_footer(); ?>