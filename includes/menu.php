<?php 
// Function to add an admin menu page
function user_info_plugin_menu()
{
    add_menu_page(
        'Pariksha Plugin',
        'Exam',
        'manage_options',
        'pariksha-plugin',
        'user_info_plugin_page',
        'dashicons-clipboard', // Use a dashicon class representing an exam
        19
    );

    add_submenu_page(
        'pariksha-plugin', // Parent slug
        'Exam Details', // Page title
        'Exam Details', // Menu title
        'manage_options', // Capability
        'exam-details', // Menu slug
        'display_exam_details_page' // Callback function to display page content
    );
    add_submenu_page(
        'pariksha-plugin', // Parent slug
        'Results', // Page title
        'Results', // Menu title
        'manage_options', // Capability
        'results', // Menu slug
        'display_results_page' // Callback function to display page content
    );
    add_submenu_page(
        'pariksha-plugin', // Parent slug
        'Contact', // Page title
        'Contact', // Menu title
        'manage_options', // Capability
        'contact', // Menu slug
        'contact_us' // Callback function to display page content
    );
}

function display_exam_details_page() {
    // Include exam-details.php with the exam ID passed as a query parameter
    include_once plugin_dir_path(__FILE__) . '../exam-details.php';
}

function display_results_page() {
    // Display your Results page content here
    include_once plugin_dir_path(__FILE__) . '../results.php';
}

function contact_us() {
    // Display your Results page content here
    include_once plugin_dir_path(__FILE__) . '../contact.php';
}
// Hook into WordPress admin menu
add_action('admin_menu', 'user_info_plugin_menu');
?>
