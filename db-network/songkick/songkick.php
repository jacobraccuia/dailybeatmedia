<?php


add_action('init', 'register_songkick_tour');
function register_songkick_tour() {
    if(!wp_next_scheduled('update_songkick_tour')) {
        wp_schedule_event(time(), 'daily', 'update_songkick_tour');
    }
}   

// this action is called every day by cron
add_action('update_songkick_tour', 'update_songkick_tour_function');
function update_songkick_tour_function() {


    require_once('SongkickAPIExchange.php'); 
    $settings = array('api_key' => '4sT0rY7JO9H5KnnE');

    $blogID = get_blog_by_name('artists');
    switch_to_blog($blogID);
    
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'artists',
        'post_status' => 'publish'
        );

    $artists = get_posts($args);

    foreach($artists as $artist) {
       $id = $artist->ID;

       $artist_id = get_post_meta($id, 'artist_id', true);
       if($artist_id == '' || $artist_id == 0) { continue; }

       $url = 'http://api.songkick.com/api/3.0/artists/' . $artist_id . '/calendar.json';

       $songkick = new SongkickAPIExchange($settings);
       $results = $songkick->buildURL($url)->performRequest();
       update_post_meta($id, 'artist_tour_dates', $results);

       // the api limit is 5 per second lol
       sleep(1);
   }

   restore_current_blog();


}

?>