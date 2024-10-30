<?php

/*
  Plugin Name: Bookitme Live Helper
  Plugin URI: http://www.bookitme.com/live-helper/
  Description: Bookitme Live Helper
  Version: 1.1.7
  Author: Bookitme
  Author URI: http://www.bookitme.com
  Disclaimer: Easy Integration of Bookitme Live Helper
 */

/*
  Copyright 2006-2014 Bookitme (sales@bookitme.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$blh_options = get_option('bookitme_live_helper');
$blh_is_mobile = false;
if (isset($_SERVER['HTTP_USER_AGENT']) && isset($blh_options['mobile_user_agents_parsed'])) {
    $blh_is_mobile = preg_match('/' . $blh_options['mobile_user_agents_parsed'] . '/', strtolower($_SERVER['HTTP_USER_AGENT']));
}

if (is_admin()) {    
	include dirname(__FILE__) . '/admin.php';
}

add_action('wp_footer', 'blh_wp_footer');

function blh_wp_footer() {
    global $blh_options;

    $buffer = $blh_options['footer'];

    ob_start();
    eval('?>' . $buffer);
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $buffer;
	
}


function blh_execute($buffer) {
    if (empty($buffer))
        return '';
    ob_start();
    eval('?>' . $buffer);
    $buffer = ob_get_clean();
    return $buffer;
}



