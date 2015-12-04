<div class="col-md-4 visible-md visible-lg">
	<div id="sidebar">
	
   			<?php get_ad('sidebar-top'); ?>
    
    		<div class="widget">
				<h2 class="cat-border">What's Hot</h2>
				<?php 
					global $exclude_posts;
					get_related_posts(array('type' => 'stack'));  
				?>
			</div>
			
				
			<?php if(function_exists('vote_poll') && !in_pollarchive()) { ?>
				<div class="widget">
					<h2 class="cat-border">Vote</h2>
					<div class="poll-wrapper">
						<ul>
							<li><?php get_poll();?></li>
						</ul>
					</div>
				</div>
			<?php } ?>	
			
		
       <!-- <div class="widget twitter_sticky"> -->
        	<div class="twitter cat-border">
            	<a href="https://twitter.com/intent/follow?screen_name=TorontoRC" class="twitter_hover cat-background">
                	<div class="twitter_bird cat-background"><i class="fa fa-twitter"></i></div>
                	<div class="twitter_header first">
	                    <h3>Toronto Rave Community</h3>
	            		<h4>Join the Conversation</h4>
					</div>
                    <div class="twitter_header second">
                    	<h3>@TorontoRC</h3>
                        <h4>Follow Us</h4>
                    </div>                    
                	
				<?php 
					$category = get_primary_category($id);
					
					$color = "#666";
					switch($category->slug) {
						case 'events': $color = "#FFCA93;"; break;
						case 'music': $color = "#FFAD5B;"; break;
						case 'news': $color = "#16E693;"; break;
						case 'lifestyle': $color = "#FF5959;"; break;
						case 'interviews': $color = "#d3a6ff;"; break;		
					}
					
				?>
					
                <a class="twitter-timeline" href="https://twitter.com/TorontoRC" data-widget-id="539248226822815744" data-border-color="<?php echo $color; ?>" data-theme="light" data-link-color="<?php echo $color; ?>"  data-related="twitterapi,twitter" data-aria-polite="assertive" data-chrome="transparent nofooter noheader noscrollbar borders" width="275" height="380" lang="EN"></a>            
     		</div>
       
       			<?php get_ad('sidebar'); ?>

       
	   
	</div>
</div>