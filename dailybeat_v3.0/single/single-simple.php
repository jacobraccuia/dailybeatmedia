<?php
/**
 * Single Default
 *
 */

global $exclude_posts;
if(have_posts()): while(have_posts()): the_post();

$id = get_the_ID();

$category = get_primary_category($id);

$author_id = get_the_author_meta('ID');
$author_username = get_the_author_meta('display_name');

$artist_id = get_post_meta($id, 'db_featured_artist_id', true);

$exclude_posts = array_merge(array($id), $exclude_posts);

$permalink = get_permalink();

/* if(is_single()) { ?>
<div style="position:fixed; margin:0px auto; width:100%;">
<div class="container">
<div class="row">
<div class="col-sm-8">
<div class="single-header-title"><?php the_title(); ?></div>
</div>
</div>
</div>
</div> 
<?php } */

?>
<div id="content">

	<section class="single-body">

		<div class="col-fixed col-fixed-left pull-left">
			<?php echo single_author_widget(); ?>
			<div class="divider"></div>
			<div id="sticky_sharing">
				<h4 class="sharing">Social</h4>
				<ul class="share">
					<?php 
					$bitly = make_bitly_url($permalink);
					$share_title = urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8'));
					?>

					<li class="social facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $bitly; ?>" data-shared="<?php the_title(); ?>" data-social="Facebook" target="_blank"><i class="fa fa-facebook-square"></i>Share</a></li>
					<li class="social twitter"><a href="https://twitter.com/share?text=<?php echo $share_title; ?>&url=<?php echo $bitly; ?>&via=TorontoRC&hashtags=TRC" data-shared="<?php the_title(); ?>" data-social="Twitter" target="_blank"><i class="fa fa-twitter"></i>Tweet</a></li>
					<li class="social linkedin"><a href="https://www.linkedin.com/cws/share?url=<?php echo $bitly; ?>" data-shared="<?php the_title(); ?>" data-social="Google Plus" target="_blank"><i class="fa fa-linkedin-square"></i>Post</a></li>
					<li class="social email"><a href="mailto:?subject=<?php the_title(); ?>&body=<?php echo the_excerpt(); ?>" data-shared="<?php the_title(); ?>" data-social="Email"><i class="fa fa-envelope"></i>Email</a></li>
					<li class="social pinterest"><a href="https://www.pinterest.com/pin/create/button/?url=<?php echo $bitly; ?>" target="_blank" data-shared="<?php the_title(); ?>" data-social="Pinterest"><i class="fa fa-pinterest-square"></i>Pin</a></li>
					<?php /* <li class="social comment"><a href="#comments"><i class="fa fa-comment"></i>Comment</a></li> */ ?>
				</ul>
			</div>
		</div>

		<div class="col-fixed col-fixed-right pull-right">
			
		</div>

		<div class="col-offset-center">

			<div class="single-content" style="height:1500px;">
				<?php the_content(); ?>
			</div>
		</div>
		<div style="clear:both;"></div>
	</section>

</div> <!-- close content -->

<?php endwhile; endif; ?>
