<?php

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'br', TEMPLATEPATH . '/languages' );
 
$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable($locale_file) )
    require_once($locale_file);
 
// Get the page number
function get_page_number() {
    if ( get_query_var('paged') ) {
        print ' | ' . __( 'Page ' , 'br') . get_query_var('paged');
    }
} // end get_page_number

function first_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "/images/default.jpg";
  }
  return $first_img;
}

function limit_words($string, $word_limit) {
 
	// creates an array of words from $string (this will be our excerpt)
	// explode divides the excerpt up by using a space character
 
	$words = explode(' ', $string);
 
	// this next bit chops the $words array and sticks it back together
	// starting at the first word '0' and ending at the $word_limit
	// the $word_limit which is passed in the function will be the number
	// of words we want to use
	// implode glues the chopped up array back together using a space character
 
	$trunc =  implode(' ', array_slice($words, 0, $word_limit));
	
	if ($string == $trunc) { } 
		else {	
	$last = $trunc[strlen($trunc)-1];
	if ($last == ".") {	$trunc = $trunc . "..";  } else { $trunc = $trunc . "..."; } }
	
	return $trunc;
}

function get_content($more_link_text = '(more...)', $stripteaser = 0, $more_file = '')
{
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

function improved_trim_excerpt($text) { // Fakes an excerpt if needed
  global $post;
  if ( '' == $text ) {
    $text = get_the_content('');
    $text = apply_filters('the_content', $text);
    $text = str_replace('\]\]\>', ']]&gt;', $text);
    $text = strip_tags($text);
    $excerpt_length = 65;
    $words = explode(' ', $text, $excerpt_length + 1);
    if (count($words)> $excerpt_length) {
		
	  array_pop($words);
      array_push($words, '');
      $text = implode(' ', $words);
    }
  }
  
		$text = substr_replace($text ,"",-1);
		$last = $text[strlen($text)-1];
	//if ($last == ".") {	$text = $text . "..";  } else { $text = $text . "..."; } 
	
return $text;
}


remove_filter('the_excerpt', 'wp_trim_excerpt');
add_filter('the_excerpt', 'improved_trim_excerpt');

function event_date($id, $var)
{
	if ($var == m) {
		 $a = get_post_meta($id, 'Date', 'True');	
		 $m = substr($a, 0, 2);
		 switch ($m):
			 case 01: return "JAN"; break;
			 case 02: return "FEB"; break;
			 case 03: return "MAR"; break;
			 case 04: return "APR"; break;
			 case 05: return "MAY"; break;
			 case 06: return "JUN"; break;
			 case 07: return "JUL"; break;
			 case 08: return "AUG"; break;
			 case 09: return "SEP"; break;
			 case 10: return "OCT"; break;
			 case 11: return "NOV"; break;
			 case 12: return "DEC"; break;
		 endswitch;
		}
	else if($var == d) {
		 $a = get_post_meta($id, 'Date', 'True');	
		 return substr($a, 3, 2);
		}
	else if($var == y) {
		 $a = get_post_meta($id, 'Date', 'True');	
		 return substr($a, 6, 4);
		}
	else if($var == full) {
		 $a = get_post_meta($id, 'Date', 'True');	
		 $m = substr($a, 0, 2);
		 switch ($m):
			 case 01: $m = "January"; break;
			 case 02: $m = "February"; break;
			 case 03: $m = "March"; break;
			 case 04: $m = "April"; break;
			 case 05: $m = "May"; break;
			 case 06: $m = "June"; break;
			 case 07: $m = "July"; break;
			 case 08: $m = "August"; break;
			 case 09: $m = "September"; break;
			 case 10: $m = "October"; break;
			 case 11: $m = "November"; break;
			 case 12: $m = "December"; break;
		endswitch;
		$d = substr($a, 3, 2);
			if (substr($d, 0, 1) == 0) { $d = substr($d, -1); }
		$y = substr($a, 6, 4);
		return $m . ' ' . $d . ', ' . $y;
		}
}
function pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1; 
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }  
 
     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}


// Create a new filtering function that will add our where clause to the query
function my_password_post_filter( $where = '' ) {
    // Make sure this only applies to loops / feeds on the frontend
    if (!is_single() && !is_admin()) {
        // exclude password protected
        $where .= " AND post_password = ''";
    }
    return $where;
}
add_filter( 'posts_where', 'my_password_post_filter' );



function twitter_handles($artist) {
	switch($artist) {
		case 'aseemmangaokar':
	           $twitter = "elmangolibre";
	           break;
	        case 'RealMaxRose':
	           $twitter = "RealMaxRose";
	           break;
	        case 'SamanthaLopez':
	           $twitter = "tranceinphilly";
	           break;
	         case 'Emily Prather':
	           $twitter = "iAmWhoi_Em";
	           break;
	         case 'NickMomeni':
	 	   $twitter = "somecallmemoney";
	 	   break;
	          case 'engelauren':
	 	   $twitter = "engelauren";
		   break;
	          case 'Mr. Evans':
	 	   $twitter = "WillEvanstheace";
		   break;
	         case 'RutherfordHouse':
	 	   $twitter = "RutherfordDBeat";
		   break;
	        case 'hillcoulson':
	 	   $twitter = "HillCoulson";
		   break;
	        case 'OscarHoyle':
	 	   $twitter = "OscarHoyle0";
		   break;
	        case 'Young Cause':
	 	   $twitter = "Reuben_Friedman";
		   break;
	        case 'DailyBeatPress':
	 	   $twitter = "BeaconDailyBeat";
		   break;
	        case 'LilyCampbell':
	 	   $twitter = "Livealil_";
		   break;
                case 'JamesD':
	 	   $twitter = "semajDailyBeat";
		   break;
	        case 'Pasotizing':
	 	   $twitter = "hotdogmonster67";
		   break;
	      	case 'lylehawthorne':
		   $twitter = "LyleHawthorne";
		   break;
                case 'Dr. Disco':
		   $twitter = "mlewin22";
		   break;
		case 'admin':
		$twitter = "BeaconChris";
		   break;
		case 'ManilaKilla':
		   $twitter = "ManilaKilla";
		   break;
		case 'admin':
		   $twitter = "BeaconChris";
		   break;
		case 'owers100':
		   $twitter = "BeaconSam";
		   break;
		case 'finnlurcott':
		   $twitter = "FinnLurcott";
		   break;
		case 'Brett B':
		   $twitter = "BrettBlackman";
		   break;
		case 'sandromarcos7':
		   $twitter = "SandroMarcos7";
		   break;
		case 'jhard':
		   $twitter = "jennahardenb";
		   break;
		case 'Paulster':
		   $twitter = "BeaconPaulster";
		   break;
		case 'Ryan T.':
		   $twitter = "RoninOfficial";
		   break;
	         case 'abRAVEgirls':
			$twitter = "shlynDB";
			break;
	default:
		$twitter = "BeaconDailyBeat";
	}
		
	if($twitter == "") { $handle = ''; }
	else {
		$handle = '<div class="twitterhandle">';
			$handle .= "<img src='" . get_bloginfo('template_url') . "/images/bird_blue_32.png' width='20' style='vertical-align:top;'/><a class='twitter' href='http://twitter.com/intent/user?screen_name={$twitter}' target='_blank'>@{$twitter}</a>";
		
		$handle .= '</div>';
	}
	
	return $handle;
}


function post_meta_bar($page) {

echo '<div class="readmore"><span style="float:left;">'; ?>
posted by <span style="font-weight:bold;"><?php the_author_posts_link(); ?></span>
<?php
echo twitter_handles(get_the_author_meta('user_login'));
echo the_timestamp() . '</span>';

	if($page == "post") { echo '<span style="float:right;"><a href="' . get_permalink() . '">Read More</a></span></div>'; }
	
	elseif($page == "single") {
	global $post;
		echo '<span style="float:right;"><a href="http://daily-beat.com/">Home</a></span><div style="clear:both;"></div>';
		echo '<div class="tags">Tags: <ul class="tagformat">';
		
		$categories = get_the_category($post->ID);
		foreach ($categories as $category) {
		$category_link = get_category_link( $category->cat_ID );
		echo "<li><a href='". esc_url( $category_link ) . "' title='Category Name'>";
		echo $category->name;
		echo "</a></li>";
		}

		echo '</ul></div>';
		echo '</div>';
		
	}
	
}

add_filter('wp_handle_upload_prefilter','tc_handle_upload_prefilter');
function tc_handle_upload_prefilter($file)
{

    $img=getimagesize($file['tmp_name']);
	$filetype = wp_check_filetype($file['tmp_name']);
	
	list($category,$type) = explode('/',$file['type']);
  if (in_array($type,array('jpg', 'gif', 'png', 'JPG', 'PNG', 'JPEG', 'jpeg', 'GIF'))) {
	
    $minimum = array('width' => '625', 'height' => '300');
    $maximum = array('width' => '1000', 'height' => '1000');
    $width= $img[0];
    $height =$img[1];

    if ($width < $minimum['width'] )
        return array("error"=>"Image dimensions are too small. Minimum width is {$minimum['width']}px. Uploaded image width is $width px" . $filetype['ext']);

    elseif ($height <  $minimum['height'])
        return array("error"=>"Image dimensions are too small. Minimum height is {$minimum['height']}px. Uploaded image height is $height px");
    elseif ($width >  $maximum['width'])
        return array("error"=>"Image dimensions are too large. Maximum width is {$maximum['width']}px. Uploaded image height is $width px");
    elseif ($height >  $maximum['height'])
        return array("error"=>"Image dimensions are too large. Maximum width is {$maximum['height']}px. Uploaded image height is $height px");
    else
        return $file; 
	}
return $file;
}

function the_timestamp() { echo " on "; the_time('F j, Y'); echo " at "; the_time('g:i a'); echo " EST"; }
// remove jetpack open graph tags // NOTE USED FOR SEO
remove_action('wp_head','jetpack_og_tags');

function my_scripts_method() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script('waypoints', get_template_directory_uri() . '/js/waypoints.min.js');
	wp_enqueue_script('waypoints2', get_template_directory_uri() . '/js/waypoints-sticky.min.js');

}

add_action('wp_enqueue_scripts', 'my_scripts_method');

//wp_enqueue_script('jQuery');
//wp_enqueue_script('jQuery-ui-core');
//wp_enqueue_script('cufon-yui', '/wp-content/themes/hippos/js/cufon-yui.js', array('jquery'));

add_theme_support( 'post-thumbnails' ); 	

// custom admin login logo
function custom_login_logo() {
echo '<style type="text/css">
h1 a { background-image: url('.get_bloginfo('template_directory').'/images/custom_login_logo.png) !important; height:58px!important; width:312px!important;}
</style>';
}
add_action('login_head', 'custom_login_logo');

?>