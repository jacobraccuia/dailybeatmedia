<div class="col-md-4 visible-md visible-lg">
	<div id="sidebar">
		<div class="non-sticky-widgets">
				
			<!-- DailyBeat_300x250 -->
			
		
			<div class="widget">
				<div class="bpm_network"><a href="http://www.thebpm.net/daily-beat/" target="_blank"></a></div>
			</div>
			
			<div class="widget">
				<div style="height:210px; overflow:hidden;">
					<div class="fb-like-box" data-href="https://www.facebook.com/beacondailybeat" data-width="300px" data-height="255px" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
				</div>
			</div>
		
		
		<div class="widget sidebar_ads">
				<div id='div-gpt-ad-1395271462856-1' style='width:300px; height:250px;'>
				<script type='text/javascript'>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395271462856-1'); });
				</script>
				</div>
			</div>
			
			
			<div class="widget">
				<h2>What's Hot</h2>
				<?php
					global $exclude_posts;
					
					$args = array(
						'posts_per_page' => 4,
						'post_status' => 'publish',
						'cat' => '-3013, -3014', 
						'post__not_in' => $exclude_posts
					);
		
					$my_query = new WP_Query($args);
					
					
					$i = 1;
					while ($my_query->have_posts()) : $my_query->the_post();
						sidebar_post($i);
					$i++;
					endwhile;
			   ?>
			</div>
		
       			</br>
       		
			<div class="widget">
				<div class="social">
					<a href="https://www.pinterest.com/dailybeat/" target="_blank"><img class="img-responsive" src="<?php echo THEME_DIR; ?>/images/pinterest.jpg" width="300" height="auto" alt="pinterest" /></a>
				</div>
				<div class="social s2">
					<a href="http://serendipityonthedaily.tumblr.com/" target="_blank"><img class="img-responsive" src="<?php echo THEME_DIR; ?>/images/tumblr.jpg" width="300" height="auto" alt="tumblr"/></a>
				</div>
			</div>
			
			<div class="widget sidebar_ads">	
				<!-- DailyBeat_300x600 -->
				<div id='div-gpt-ad-1395271462856-2' style='width:300px; height:600px;'>
					<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('div-gpt-ad-1395271462856-2'); });
					</script>
				</div>
			</div>
		
		
		</div><!-- close non-sticky sidebar section -->
       

       <!-- <div class="widget twitter_sticky"> -->
        	<div class="twitter">
            	<a href="https://twitter.com/intent/follow?screen_name=BeaconDailyBeat" class="twitter_hover">
                	<div class="twitter_bird"></div>
                	<div class="twitter_header first">
	                    <h3>Daily Beat</h3>
	            		<h4>Join the Conversation</h4>
					</div>
                    <div class="twitter_header second">
                    	<h3>@BeaconDailyBeat</h3>
                        <h4>Follow Us</h4>
                    </div>                    
                	
                <a class="twitter-timeline" href="https://twitter.com/BeaconDailyBeat" data-widget-id="295666351152963585" data-border-color="#ff7373" data-theme="light" data-link-color="#ff7373"  data-related="twitterapi,twitter" data-aria-polite="assertive" data-chrome="transparent nofooter noheader noscrollbar borders" width="275" height="380" lang="EN"></a>            
                          
            </div>
       
	</div>
</div>