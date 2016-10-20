<?php

// We'll be outputting CSS
header('Content-type: text/css');


$theme_color_light = zuko_get_options('theme_color_light');
$theme_color_dark = zuko_get_options('theme_color_dark');


?>

body { background:black!important; }
/* set gradients */
.nav-border { background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
#page-progress { background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
#veggie.menu-active { background:<?php echo $theme_color_light; ?>; background-image:linear-gradient(to bottom, <?php echo $theme_color_dark; ?>, <?php echo $theme_color_light; ?>); }


#cabinet h2 span { color:<?php echo $theme_color_light; ?>; background:-webkit-gradient(linear, 0 0, 100% 100%, from(<?php echo $theme_color_light; ?>), to(<?php echo $theme_color_dark; ?>)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }


#player_button:hover, #player_button.open { background:$blue; background-image:linear-gradient(to bottom, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
.h-icon.divider { background:<?php echo $theme_color_dark; ?>; background-image:linear-gradient(to bottom, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
.footer-border { background-color:<?php echo $theme_color_light; ?>; background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
footer .divider { background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }

.col-header .bar { background-color:<?php echo $theme_color_light; ?>; background:linear-gradient(to right, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }
.col-header .bar.right { background-color:<?php echo $theme_color_light; ?>; background:linear-gradient(to left, <?php echo $theme_color_light; ?>, <?php echo $theme_color_dark; ?>); }