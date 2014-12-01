<?php
/*
Plugin Name: Hello Ajax Tabs
Plugin URI: http://hello-plugins.constflash.com
Description: Create tabs from any widgets you have in 3 easy steps. 12 predefined styles, 23 animation effects, easing support, equal tabs support, header selector, ajax loading, vertical tabs support, fixed height support, multitabs support !  Compatible with IE8+, Opera, Safari, Google Chrome, Firefox.
Version: 2.2.0
Author: Anton Korda
*/

include ( 'hello_ajax_tabs_widget.php');
include ( 'hello_ajax_tabs_admin.php');

function hat_admin_theme_style() {
    wp_enqueue_style('hat-admin', plugin_dir_url( __FILE__ ).'css/hat-admin.css');
}
add_action('admin_enqueue_scripts', 'hat_admin_theme_style');


