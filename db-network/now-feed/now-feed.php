<?php
use MetzWeb\Instagram\Instagram;

// add custom cron interval
add_filter('cron_schedules', 'add_custom_cron_intervals', 10, 1);
function add_custom_cron_intervals($schedules) {
    $schedules['2mins'] = array(
        'interval'  => 120, // Number of seconds, 600 in 10 minutes
        'display' => 'Every Two Minutes'
        );

    return (array)$schedules; 
}

add_action('init', 'register_update_feed_event');
function register_update_feed_event() {
    if(!wp_next_scheduled('update_now_feed')) {
        wp_schedule_event(time(), '2mins', 'update_now_feed');
    }

    if(!wp_next_scheduled('update_backup_now_feed')) {
        wp_schedule_event(time(), 'hourly', 'update_backup_now_feed');
    }
}   

add_action('update_now_feed', 'update_now_feed_function');
function update_now_feed_function() {

    NowFeed::set_instagram_cache();
    NowFeed::set_twitter_cache();
}

// this action is called every minute by cron
add_action('update_backup_now_feed', 'update_backup_now_feed_function');
function update_backup_now_feed_function() {

    NowFeed::set_instagram_backup_cache();
    NowFeed::set_twitter_backup_cache();
}






add_action('wp_ajax_update_now_feed', 'update_now_feed');
add_action('wp_ajax_nopriv_update_now_feed','update_now_feed');

function update_now_feed() {

    $nonce = $_POST['postCommentNonce'];
    if(!wp_verify_nonce( $nonce, 'myajax-post-comment-nonce'))
        die('Busted!');

    $response = array();
    $feed = new NowFeed();

    ob_start();
    $feed->getNowFeed(array('limit' => 12, 'ad' => true, 'image_cutoff' => 4, 'unique_class' => 'top-home-nowfeed'));
    $response['top'] = ob_get_clean();  

    ob_start();
    $feed->getNowFeed(array('limit' => 20, 'unique_class' => 'bottom-home-nowfeed'));
    $response['bottom'] = ob_get_clean();

    echo json_encode($response);
    die;
}



class NowFeed {

    public $now_feed = array();
    public $exclude_feed = array();
    public $now_feed_loops = 1;

    public function __construct() {

        $i = 0;
        $now_feed = array();

        // check both caches, to help reduce unnecessary calls
        if(false === ($all_instas = get_transient('instas'))) {
            if(false === ($all_instas = get_transient('instas_backup'))) {

                if(false === ($x = get_transient('manual_call_insta'))) {
                    $x = 0;
                }
                set_transient('manual_call_insta', $x + 1, 60 * 60 * 24 * 7 * 12);
                $all_instas = $this->set_instagram_cache();
            }
        }

        if(false === ($all_tweets = get_transient('tweets'))) {
            if(false === ($all_tweets = get_transient('tweets_backup'))) {

                if(false === ($y = get_transient('manual_call_twitter'))) {
                    $y = 0;
                }
                set_transient('manual_call_twitter', $y + 1, 60 * 60 * 24 * 7 * 12);
                $all_tweets = $this->set_twitter_cache();
            }
        }

        // insert instas into feed
        if($all_instas) {
            foreach($all_instas as $media) {
                $now_feed[$i]['time'] = $media->created_time;
                $now_feed[$i]['data'] = $media;
                $now_feed[$i]['type'] = 'instagram'; 
                $i++;
            }
        }

        // insert tweets into feed
        if($all_tweets) {
            foreach($all_tweets as $tweet) {
                $datetime = $tweet->created_at;
                $now_feed[$i]['time'] = strtotime($datetime);
                $now_feed[$i]['data'] = $tweet;
                $now_feed[$i]['type'] = 'twitter';
                $i++;
            }
        }

        usort($now_feed, array($this, 'now_feed_cmp'));

        $this->now_feed = $now_feed;

    }

    public function getNowFeed($args = array()) {

        $defaults = array(
            'limit' => 30,
            'offset' => 0,
            'ad' => false,
            'image_cutoff' => 9999,
            'unique_class' => ''
            );

        // merge arguments with defaults && set keys as variables
        $args = array_merge($defaults, $args);
        foreach($args as $key => $val) { ${$key} = $val; }

        $now_feed = $this->now_feed;
        $images_looped = 0;

        echo '<div class="now-feed-container ' . $unique_class .'">';

        $i = $k = 0;
        $now_feed_loops = $this->now_feed_loops;
        foreach($now_feed as $key => $item) {
            if(isset($this->exclude_feed[$key])) { continue; } // if has already been displayed
            if($images_looped > $image_cutoff) { break; } // if has too many images
            if($this->has_video($item)) { continue; } // skip videos for now
            if($i++ < $offset) continue; // offset
            if($i > $offset + $limit) break; // limit

            $this->exclude_feed[$key] = $item;
            if($this->has_image($item) === true) {
                $images_looped++;
                if($images_looped > $image_cutoff) { break; } // keep looping until there is another image
            }

            if($item['type'] == 'twitter') {
                echo $this->tweets($item['data'], $now_feed_loops);
            }
            else if($item['type'] == 'instagram') {
                echo $this->instas($item['data'], $now_feed_loops);
            }

            $k++;
            // on second iteration show ad
            if($k == 1 && $ad) {
                echo '<div class="widget ad">';
                echo do_shortcode('[bsa_pro_ad_space id="1"]');
                echo '</div>';
            }

            $now_feed_loops++; // loop tracker across all instances of the call
        }

        echo '</div>';

        $this->now_feed_loops = $now_feed_loops;

    }


    public static function set_instagram_cache() {

        $instagram_ids = array(
            283001946,
            25877499,
            210609437,
            1540079,
            28855276,
            30464350,
            21989850,
            181395647,
            30890446,
            19359711,
            3810502,
            639577784,
            395407,
            366581549
            );

        require_once('InstagramAPIExchange.php');

        $instagram = new Instagram('41f7ca3bb3534b9ab8f93983fc3e0b89');
        $all_instas = array();

        foreach($instagram_ids as $id) {
            $all_instas = array_merge($instagram->getUserMedia($id, 2)->data, $all_instas);
        }

        if(!isset($all_instas[0]->created_time)) {
            return false;
        }

        set_transient('instas_time', time(), 60 * 60); // store for 1 hour (it gets deleted anyways)
        set_transient('instas', $all_instas, 60 * 60); // store for 1 hour (it gets deleted anyways)

        return $all_instas;
    }

    public static function set_twitter_cache() {

        require_once('TwitterAPIExchange.php'); 

        $settings = array(
            'oauth_access_token' => '132239128-o5xONmZNeOCXbXMhaUax9snOAAn8Ip3xlwt8ZUiQ',
            'oauth_access_token_secret' => 'IaSB76sgxNoZDWEv7CZ2D2EsiXUyxC8yYBLPFI3Rnld8I',
            'consumer_key' => '3u3rlmwHbMVCSU4Pj4eJLoJg5',
            'consumer_secret' => 'RiVPz8sImWRzkVvbI0WzDdjTpS5gA73NJP2aL2BDrDxIRxVJIz'
            );

        $url = 'https://api.twitter.com/1.1/lists/statuses.json';
        $getfield = '?list_id=217168616&include_rts=false&include_entities=true&count=45';
        $requestMethod = 'GET';

        $twitter = new TwitterAPIExchange($settings);
        $results = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();

        $all_tweets = json_decode($results);
        if(isset($all_tweets->errors)) {
            return false;
        }

        set_transient('tweets_time', time(), 60 * 60); // store for 1 hour (it gets deleted anyways)
        set_transient('tweets', $all_tweets, 60 * 60); // store for 1 hour (it gets deleted anyways)

        return $all_tweets;
    }

    public static function set_instagram_backup_cache() {

        if(false === ($all_instas = get_transient('instas')) || !isset($all_instas[0]->created_time)) {
            return false;
        }

        set_transient('instas_backup_time', time(), 60 * 60 * 7); // store for 1 hour (it gets deleted anyways)
        set_transient('instas_backup', $all_instas, 60 * 60 * 7); // store for 1 hour (it gets deleted anyways)

    }

    public static function set_twitter_backup_cache() {

        if(false === ($all_tweets = get_transient('tweets')) || isset($all_tweets->errors)) {
            return false;
        }
        
        set_transient('tweets_backup_time', time(), 60 * 60 * 7); // store for 1 hour (it gets deleted anyways)
        set_transient('tweets_backup', $all_tweets, 60 * 60 * 7); // store for 1 hour (it gets deleted anyways)
    }
    
    private function tweets($tweet, &$i) {   
        $comment = TwitterEntitiesLinker::getHtml($tweet);

        $media_url = '';
        if(isset($tweet->extended_entities->media)) {
            $media_url = $tweet->extended_entities->media[0]->media_url; // Or $media->media_url_https for the SSL version.
            $large_media_url = $tweet->extended_entities->media[0]->media_url; // Or $media->media_url_https for the SSL version.
        }
        $verified = $tweet->user->verified;

        $datetime = new DateTime($tweet->created_at); 
        $unix_date = $datetime->format('U');
        $timestamp = human_time_diff($unix_date, gmdate('U')); 

        ?>
        <div class="widget twitter" id="now-feed-popup-<?php echo $i; ?>">
            <div class="header">
                <a class="profile" href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank"><img src="<?php echo $tweet->user->profile_image_url; ?>" /></a>
                <h5><a href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank"><?php echo $tweet->user->name; 
                    if($verified) { 
                        ?><span class="fa fa-stack fa-lg">
                        <i class="fa fa-certificate fa-stack-2x"></i>
                        <i class="fa fa-check fa-stack-1x fa-inverse"></i>
                    </span>
                    <?php } ?>
                </a></h5>
                <h6><a href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>" target="_blank">@<?php echo $tweet->user->screen_name; ?></a></h6>

                <p><?php echo $comment; ?></p>
            </div>
            <?php if($media_url != '') {
                echo '<a href="#now-feed-popup-' . $i . '" class="featherlight-now-feed"><div class="featured-image" data-image-url="' . $large_media_url . ':large" style="background-image:url(' . $media_url .':small);"></div></a>';
            } ?>
            <div class="meta">
                <a href="http://twitter.com" target="_blank"><i class="fa fa-fw fa-twitter"></i></a>
                <span class="timestamp"><?php echo $timestamp; ?></span>
                <div class="web-intents">
                    <a class="reply" href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet->id; ?>"></a>
                    <a class="retweet" href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet->id; ?>"></a>
                    <a class="favorite" href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet->id; ?>"></a>
                </div>
            </div>
        </div>

        <?php
    }

    private function instas($media, &$i) {

        if($media->type === 'video') { return; }

        $avatar = $media->user->profile_picture;
        $username = $media->user->username;

        $comment = $this->linkify_insta((!empty($media->caption->text)) ? $media->caption->text : '');

        $image = $media->images->low_resolution->url;
        $high_res = $media->images->standard_resolution->url;
        $unix_date = $media->created_time;

        $timestamp = human_time_diff($unix_date, gmdate('U')); 

        ?>

        <div class="widget instagram" id="now-feed-popup-<?php echo $i; ?>">
            <div class="header">
                <a class="profile" href="http://instagram.com/<?php echo $username; ?>" target="_blank"><img class="rounded" src="<?php echo $avatar; ?>" /></a>
                <h5><a href="http://instagram.com/<?php echo $username; ?>" target="_blank"><?php echo $username; ?></a></h5>
                <p><?php echo $comment; ?></p>
            </div>
            <?php echo '<a href="#now-feed-popup-' . $i . '" class="featherlight-now-feed"><div class="featured-image" data-image-url="' . $high_res . '" style="background-image:url(' . $image .');"></div></a>'; ?>
            <div class="meta">
                <a href="http://instagram.com" target="_blank"><i class="fa fa-fw fa-instagram"></i></a>
                <span class="timestamp"><?php echo $timestamp; ?></span>
            </div>

        </div>
        <?php

    }

    private function has_video($item) {
        if($item['type'] != 'instagram') {
            return;
        }
        
        if($item['data']->type === 'video') { return true; }
    }

    private function has_image($item) {
        if($item['type'] == 'instagram') {
            return true;
        }
        if(isset($item['data']->extended_entities->media)) {
            return true;
        }
    }

    private function linkify_insta($insta) {

        $insta = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $insta);
        $insta = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://instagram.com/explore/tags/$1\">#$1</a>", $insta);
        $insta = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.instagram.com/$1\">@$1</a>", $insta);

        return $insta;
    }


    private function now_feed_cmp($a, $b) {
        if ($a['time'] == $b['time']) {
            return 0;
        }
        return ($a['time'] > $b['time']) ? -1 : 1;
    }
}

class TwitterEntitiesLinker {

    public static function getHtml($tweet, $highlight = array()) {
        $convertedEntities = array();
// check entities data exists
        if ( ! isset($tweet->entities) ) {
            return $tweet->text;
        }
// make entities array
        foreach ( $tweet->entities as $type => $entities ) {
            foreach ( $entities as $entity ) {
                $entity->type = $type;
                $convertedEntities[] = $entity;
            }
        }
// sort entities
        usort($convertedEntities,
            "TwitterEntitiesLinker::sortFunction");
// split entities and texts
        $pos = 0;
        $entities = array();
        foreach ($convertedEntities as $entity) {
// not entity
            if ( $pos < $entity->indices[0] ) {
                $substring = mb_substr($tweet->text,
                    $pos,
                    $entity->indices[0] - $pos,
                    'utf-8');
                $entities[] = array('text' => $substring, 
                    'data' => null);
                $pos = $entity->indices[0];
            }
// entity
            $substring = mb_substr($tweet->text,
                $pos,
                $entity->indices[1] - $entity->indices[0],
                'utf-8');
            $entities[] = array('text' => $substring, 
                'data' => $entity);
            $pos = $entity->indices[1];
        }
// tail of not entity
        $length = mb_strlen($tweet->text, 'utf-8');
        if ( $pos < $length ) {
            $substring = mb_substr($tweet->text,
                $pos,
                $length - $pos,
                'utf-8');
            $entities[] = array('text' => $substring, 
                'data' => null);
        }
// replace
        $html = "";
        foreach ( $entities as $entity ) {
            if ( $entity['data'] ) {
                if ( $entity['data']->type == 'urls' ) {
                    $url = ($entity['data']->expanded_url) ? $entity['data']->expanded_url : $entity['data']->url;
                    $html .= '<a href="'.$url.'" target="_blank" rel="nofollow" class="twitter-timeline-link">'.self::highlightText($url, $highlight).'</a>';
                }
                else if ( $entity['data']->type == 'hashtags' ) {
                    $text = $entity['data']->text;
                    $html .= '<a href="http://twitter.com/#!/search?q=%23'.$text.'" title="#'.$text.'" class="twitter-hashtag" rel="nofollow">#'.self::highlightText($text, $highlight).'</a>';
                }
                else if ( $entity['data']->type == 'user_mentions' ) {
                    $screen_name = $entity['data']->screen_name;
                    $html .= '<a class="twitter-atreply" data-screen-name="'.$screen_name.'" href="http://twitter.com/'.$screen_name.'" rel="nofollow">@'.self::highlightText($screen_name, $highlight).'</a>';
                }
                else {
                }
            }
            else {
                $html .= self::highlightText($entity['text'], $highlight);
            }
        }

        return $html;
    }
    /**
    * sort function
    *
    * @param   data a
    * @param   data b
    * @return  1 or -1 or 0
    */
    static private function sortFunction($a, $b)  {
        if ($a->indices > $b->indices) { return 1; }
        else if ($a->indices < $b->indices) { return -1; }
        else { return 0; }
    }
    /**
    * highlight text
    */
    static private function highlightText($text, $highlight) {
        if ( $highlight ) {
            $text = preg_replace($highlight['patterns'],
                $highlight['replacements'],
                $text);
        }
        return $text;
    }
}



?>