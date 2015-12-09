<?php
/*
Plugin Name: DB Network
Plugin URI: http://www.jacobraccuia.com
Description: DB Network functions
Author: Jacob Raccuia
Author URI: http://www.jacobraccuia.com
Version: 0.01

Copyright 2013 Jacob Raccuia (jacobraccuia@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

require(dirname (__FILE__) . '/install.php');
require(dirname (__FILE__) . '/functions.php');


register_activation_hook(__FILE__, array('DB_Global', 'on_activation'));
register_deactivation_hook(__FILE__, array('DB_Global', 'on_deactivation'));
?>