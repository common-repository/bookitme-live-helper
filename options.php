<?php
function blh_request($name, $default=null) {
    if (!isset($_REQUEST[$name])) return $default;
    return stripslashes_deep($_REQUEST[$name]);
}

function blh_field_textarea($name, $label='', $tips='', $attrs='') {
    global $options;    
    if (!isset($options[$name])) $options[$name] = '';
    if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
    if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';
    $logoURL  = plugins_url( 'images/logo52.png' , __FILE__ );
  
    echo '<div style="width:80%;text-align:left;">';
	echo "<a href=\"http://www.bookitme.com/\" style=\"text-decoration:none;\" target=\"_blank\"><div style=\"background-color:black;padding:20px;\"><img src=\"". $logoURL. "\"><span style=\"font-size:50px;color:#ffffff;padding-left:20px;\">LIVE HELPER</span></div></a>";
	echo '<h1>BOOKITME LIVE HELPER set values:</h1>';
	echo '<textarea style="width: 100%; height: 250px" wrap="off" name="options[' . $name . ']">' .
        htmlspecialchars($options[$name]) . '</textarea>';
  
	echo '</div>';

}

if (isset($_POST['save'])) {
    if (!wp_verify_nonce($_POST['_wpnonce'], 'save')) die('Page expired');
    $options = blh_request('options');
    if (empty($options['mobile_user_agents'])) {
        $options['mobile_user_agents'] = "phone\niphone\nipod\nandroid.+mobile\nxoom";
    }
    $agents1 = explode("\n", $options['mobile_user_agents']);
    $agents2 = array();
    foreach ($agents1 as &$agent) {
        $agent = trim($agent);
        if (empty($agent)) continue;
        $agents2[] = strtolower($agent);
    }
    $options['mobile_user_agents_parsed'] = implode('|', $agents2);
    update_option('bookitme_live_helper', $options);
}
else {
    $options = get_option('bookitme_live_helper');
}

?>

<div class="wrap" style="">
    <form method="post" action="">
        <?php wp_nonce_field('save') ?>

		<?php blh_field_textarea('footer', __('Code to be added before the end of the page', 'bookitme-live-helper'), '', 'rows="10"'); ?>
    
    <p><input type="submit" class="button" name="save" value="<?php _e('save', 'bookitme-live-helper'); ?>"></p>
	<p style="font-size:40px;margin:0px;">Powerful plugin
    <span style="color:#36AAE2;">Great possibilities</span></p>
    <p style="font-size:16px">Always in touch with your clients — chat widget embedded on your web pages will allow visitors to talk with you in just a few clicks — and you'll be able to solve their problems immediately!
    Your clients will be very happy not having to wait for an e-mail response.</p>
	<p style="font-size:40px;margin:0px;">See what your visitor <span style="color: #FF0073;">is watching!</span></p>
    <p style="font-size:16px">On top of the chat's view there's always a live status of the page guest is currently visiting. Now you will know what your clients are looking for and be able to immediately respond to their needs!</p>   
	<p style="font-size:40px;margin:0px;">Multiple <span style="color: #86C953;">operators & guests</span> chatting</p>
    <p style="font-size:16px;">Single operators can talk with many visitors at once and multiple operators can be available on-line all at the same time. Create any number of operators you want!</p>
	<p style="font-size:40px;margin:0px;">Full <span style="color: #36AAE2;">history search</span></p>
	<p style="font-size:16px;">Past conversations are always available for you to search through — you can filter the results by names, e-mail addresses and dates.</p> 
    </form>
</div>


