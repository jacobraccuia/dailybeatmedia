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
			<div class="col-header col-header-fnt">
				<div class="bar"></div>
				<div class="logo fnt"><i class="svg svg-fnt"></i><span>Top40</span></div>
				<div class="bar right"></div>
			</div>
			<div class="top-home-fnt">
				<?php get_fresh_new_tracks(array('posts_per_page' => 20, 'sticky_wrapper' => true)); ?>
			</div>
		</div>



		<div class="col-fixed col-fixed-right pull-right sticky-wrapper">
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

		<div class="col-offset-center">
			<div class="row">
				<div class="col-xs-12 home-center-content">

					<div class="row post-wrapper top-post-wrapper full-width-wrapper">
						<?php  
						$args = array(
							'list_post_title' => '<i class="svg-trending"></i>RECENT HEADLINES',
							);

						get_top_posts($args);
						?>
					</div>

					<div class="row post-wrapper full-width-wrapper video-wrapper">
						<?php get_video_post(); ?>
					</div>

					<div class="row post-wrapper rr-wrapper">
						<div class="col-xs-12">		
							<div class="trending-topic"><span class="svg svg-icon svg-rr-icon-color"></span><span class="trending-slogan rr">Raver Rafting<span>WE ARE THE SCENE</span></span></div>
						</div>

						<?php get_brand_posts('raver'); ?>

					</div>

					<div class="row post-wrapper trc-wrapper">
						<div class="col-xs-12">		
							<div class="trending-topic"><span class="svg svg-icon svg-trc-icon-color"></span><span class="trending-slogan trc">Toronto Rave Community<span>Toronto's Home for all Things Nightlife</span></span></div>
						</div>

						<?php get_brand_posts('trc'); ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="video-wrapper beatmersive post-wrapper">
		<div class="col-xs-12">		
		<div class="trending-topic"><span class="svg svg-icon svg-beatmersive-icon-color"></span><span class="trending-slogan beatmersive">Beatmersive<span>360&deg; Immersive Virtual Reality Experience</span></span></div>
		</div>

		<?php get_beatmersive_posts(); ?>
	</section>


	<section class="news_continued">

		<div class="col-fixed col-fixed-left pull-left">
			<?php get_fresh_new_tracks(array('offset' => 20, 'posts_per_page' => 20)); ?>


		</div>

		<div class="col-fixed col-fixed-right pull-right">
			<?php $feed->getNowFeed(array('limit' => 8, 'unique_class' => 'bottom-home-nowfeed')); ?>
		</article>
	</div>

	<div class="col-offset-center">
		<div class="row">
			<div class="col-xs-12 home-center-content">

				<div class="row post-wrapper ht-wrapper">
					<div class="col-xs-12">
						<div class="trending-topic"><span class="svg svg-icon svg-headliners-icon-color"></span><span class="trending-slogan ht">HEADLINERS TRIBUNE</span></div>
					</div>

					<?php get_variant_posts('headliners'); ?>

				</div>

				<div class="row post-wrapper trc-wrapper">
					<div class="col-xs-12">		
						<div class="trending-topic"><span class="svg svg-icon svg-attack-icon-color"></span><span class="trending-slogan a">Attack</span></div>
					</div>

					<?php get_brand_posts('attack'); ?>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<?php get_standard_loop(); ?>	
					</div>
				</div>

			</div>
		</div>
	</div>
</section>



</div>

<?php get_footer(); ?><?php





/*    this code will get the trending post from the section.  currently not in use.

$tags = get_option('trending_post_tags');
$trending_logo = get_option('trending_logo');

$args = array(
	'tags' => $tags,
//	'trending_logo' => $trending_logo,
	'list_post_title' => '<i class="svg-trending"></i>TRENDING',
	);

if($tags && $tags != '') {
	get_trending_posts($args);
}

*/
?>