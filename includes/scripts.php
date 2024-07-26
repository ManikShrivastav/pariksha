<?php 
// Enqueue scripts
function user_info_plugin_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-js', plugins_url('../js/bootstrap.min.js', __FILE__), array('jquery'), '4.5.2', true);
    wp_enqueue_style('bootstrap-css', plugins_url('../css/bootstrap.min.css', __FILE__), array(), '4.5.2');
    wp_enqueue_script('pariksha-plugin-script', plugins_url('../js/user-info-plugin-script.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('pariksha-plugin-style', plugins_url('../css/user-info-plugin-style.css', __FILE__));
    wp_enqueue_script('xlsx-js', plugins_url('../js/xlsx.full.min.js', __FILE__), array('jquery'), '0.17.4', true);


    wp_localize_script('pariksha-plugin-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('admin_enqueue_scripts', 'user_info_plugin_scripts');
?>
