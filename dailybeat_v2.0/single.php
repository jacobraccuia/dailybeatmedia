<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header(); ?>	


<?php //featured_nav(); // this includes container class ?>
<div class="container main">
	<br />
<div class="row">
		<div class="col-md-8">
			 <?php
	global $exclude_posts;
	if(have_posts()): while(have_posts()): the_post();
    
		$id = get_the_ID();
		$db_soundcloud = get_post_meta($id, 'db_soundcloud', true);
		$category = get_the_terms($id, 'category');		
		
		$song_title = get_the_title();
		
		$exclude_posts = array_merge(array($id), $exclude_posts);
		
	
		?>
	
		<div class="post_wrapper post">
				<div class="single_featured">
					<?php if(has_post_thumbnail()) { the_post_thumbnail(); } ?>
				</div>
				<div class="content">
                	<div class="meta_wrapper row">   
                        <ul class="meta col-xs-7 col-sm-6 tight-right-padding">
                            <li class="author">
                                <span class="glyphicon glyphicon-user"></span>
                                <?php the_author_posts_link(); ?>
                            </li>
                            <li class="date">
                                <span class="glyphicon glyphicon-calendar"></span>
                                <?php echo the_timestamp_short(); ?>
                            </li>
                            <li class="category">
                                <span class="glyphicon glyphicon-align-left"></span>
                            <?php // display 3 'random' categories
                                $i = 0;
                                $c = count($category);
                                foreach($category as $term) {
                                    echo '<a href="' . get_term_link($term->slug, 'category') . '">';
                                        echo $term->name;
            //							if($i < 3) { echo ","; }
                                    echo '</a>';
                                    if($i == 1 or $i == $c - 1) { break; } else { echo ", "; }
                                    $i++;
                                }
                            ?>
                            </li>
                        </ul>
                    	<div class="share col-xs-5 col-sm-6 tight-left-padding">
                        	<span class='st_facebook_hcount share_this' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='facebook'></span><span st_via='BeaconDailyBeat #DailyBeat' st_username='@BeaconDailyBeat' class='st_twitter_hcount share_this' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='twitter'></span><span class='st_pinterest_hcount' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='pinterest'></span>
						</div>
                    </div>
                    <h1 class="title sticky_waypoint"><?php the_title(); ?></h1>
					<div class="sticky_title">
                    	<div class="container">
                            <div class="right"><span class='st_facebook_hcount share_this' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='facebook'></span>&nbsp;<span st_via='BeaconDailyBeat #DailyBeat' st_username='@BeaconDailyBeat' class='st_twitter_hcount share_this' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>' displayText='twitter'></span></div>
                            <div class="left"><h1 class="title"><?php the_title(); ?></h1></div>
						</div>
                    </div>
					<div class="post_content">
						<?php echo the_content(); ?>
                    </div>
                    	<?php if(isset($db_soundcloud) && !empty($db_soundcloud)) { ?>
						<div class="bar">
							<div class="player">
                        		<?php soundcloud_player($db_soundcloud, $song_title); ?>
							</div>
						</div>
 	                       <div class="beneath_post">
                                <p class="tags">
                                <?php $i = 0;
                                    $c = count($category);
                                    $category_ids = array();
                                    foreach($category as $term) {
                                        array_push($category_ids, $term->term_id);
                                        echo '<a href="' . get_term_link($term->slug, 'category') . '">';
                                            echo "#" . $term->name;
                                        echo '</a>';
                                        if($i == $c - 1) { break; } else { echo " "; }
                                        $i++;
                                    }
                                ?>
                                </p>
                            </div>
						<?php } else { ?>
							<div class="divider margins"></div>
                            <div class="beneath_post">
								<p class="tags">
								<?php $i = 0;
									$c = count($category);
									$category_ids = array();
									foreach($category as $term) {
										array_push($category_ids, $term->term_id);
										echo '<a href="' . get_term_link($term->slug, 'category') . '">';
											echo "#" . $term->name;
										echo '</a>';
										if($i == $c - 1) { break; } else { echo " "; }
										$i++;
									}
								?>
								</p>
                            	</div>      
                        <?php } ?>
                        </div>

					   <?php
				
				
				// author
				$author_id = get_the_author_meta('ID');
				$author_username = get_the_author_meta('display_name');
				
		
				?>
				
				<div class="related_posts">
					<?php author_biography(); ?>
				</div>		
					
				<!-- Place in body part -->
		<!--		<div id="ingageunit"></div>
				<!-- Place in body part -->
				
					
		

				<div class="related_posts">
					<h3 class="section_title">Related</h3>
					<div class="divider bottom_margins"></div>
					<div class="row">
						<?php get_related_posts($category_ids, array($id)); ?>
					</div>
				</div>		
						
				<!-- <div class="related_posts">	
					<h3 class="section_title">Sponsored</h3>
					<div class="divider bottom_margins"></div>
						<?php if(in_category('electronic')) { ?>
							<a href="http://www.beatport.com/track/samurai-bounce-original-mix/5682109">
								<img class="img-responsive" src="<?php echo THEME_DIR;?>/images/SamuraiBounceleaderboard.jpg" width="750" height="auto" alt="Samurai Bounce" />
							</a>
						<?php } ?>
				</div> -->
				
					<div class="related_posts zuus_player_wrapper">
						<h3 class="section_title">DailyBeat TV Powered by Zuus</h3>
						<div class="divider bottom_margins"></div>
						<?php zuus_player(); ?>
					</div>
			
					<div class="related_posts author">
						<h3 class="section_title">More By <strong><?php echo $author_username; ?></strong></h3>
						<div class="divider bottom_margins"></div>
							<div class="row">
								<?php get_author_posts($author_id); ?>
							</div>
					</div>
				 <div class="comments">                        	
						<h3 class="section_title">Comments</h3>
						<div class="divider"></div>
						<?php // this is really fucking important. 
							wp_reset_query(); ?>
						<?php echo do_shortcode('[fbcomments]'); ?>        
					</div>
 				</div>
		
	<?php endwhile; endif; ?>
		</div>
        
			<?php get_sidebar(); ?>
	</div>
</div> <!-- close container -->
<?php get_footer(); ?>