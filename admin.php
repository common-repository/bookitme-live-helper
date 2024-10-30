<?php

delete_option('bookitme-live-helper_version');

add_action('admin_init', 'blh_admin_init');

function blh_admin_init() {
    if (isset($_GET['page']) && strpos($_GET['page'], 'bookitme-live-helper/') === 0) {
        header('X-XSS-Protection: 0');
		wp_enqueue_style('admincss',plugins_url('admin.css', __FILE__));
    }
}

add_action('admin_head', 'blh_admin_head');

function blh_admin_head() {
    if (isset($_GET['page']) && strpos($_GET['page'], 'bookitme-live-helper/') === 0) {
    }
}

add_action('admin_menu', 'blh_admin_menu');

function blh_admin_menu() {
    add_options_page('Bookitme Live Helper', 'Bookitme Live Helper', 'manage_options', 'bookitme-live-helper/options.php');
}

add_action('add_meta_boxes', 'blh_add_meta_boxes');

add_action('save_post', 'blh_save_post');

function blh_add_meta_boxes() {
    foreach (array('post', 'page') as $screen) {
        add_meta_box(
                'blh', __('Bookitme Live Helper', 'bookitme-live-helper'), 'blh_add_meta_boxes_callback', $screen
        );
    }
}

function blh_add_meta_boxes_callback($post) {

    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'blh');

    // The actual fields for data entry
    // Use get_post_meta to retrieve an existing value from the database and use the value for the form
    $before = get_post_meta($post->ID, 'blh_before', true);
    $after = get_post_meta($post->ID, 'blh_after', true);
    echo '<label>';
    echo '<input type="checkbox" id="blh_before" name="blh_before" ' . (empty($before) ? "" : "checked") . '> ';
    _e("Disable top injection", 'bookitme-live-helper');
    echo '</label> ';
    echo '<br>';
    echo '<label>';
    echo '<input type="checkbox" id="blh_after" name="blh_after" ' . (empty($after) ? "" : "checked") . '> ';
    _e("Disable bottom injection", 'bookitme-live-helper');
    echo '</label> ';
}

function blh_save_post($post_id) {

    // check current user authorised.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) return;
    } else {
        if (!current_user_can('edit_post', $post_id)) return;
    }

    // check user to change.
    if (!isset($_POST['blh']) || !wp_verify_nonce($_POST['blh'], plugin_basename(__FILE__))) return;

    $mydata = sanitize_text_field($_POST['myplugin_new_field']);

    update_post_meta($post_id, 'blh_before', isset($_REQUEST['blh_before']) ? 1 : 0);
    update_post_meta($post_id, 'blh_after', isset($_REQUEST['blh_after']) ? 1 : 0);
}

