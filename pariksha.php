<?php
/*
Plugin Name: Pariksha
Description: Takes exams along with Tutor LMS and Woocommerce
Version: 1.0
Author: Vaikartan Groups
*/
ob_start();



// Function to create tables on plugin activation
function my_plugin_create_tables() {
    global $wpdb;
    
    // Table Exam
    $table1_name = $wpdb->prefix . 'exam_tbl'; // Replace with your table name
    $sql1 = "CREATE TABLE IF NOT EXISTS $table1_name (
        ex_id INT NOT NULL AUTO_INCREMENT,
        cou_id INT NOT NULL,
        ex_title VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        ex_time_limit VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        ex_questlimit_display INT NOT NULL,
        ex_description VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        ex_status TINYINT(1) NOT NULL DEFAULT 1,
        ex_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        scheduled_time TIMESTAMP NULL,
        PRIMARY KEY (ex_id)
    ) ENGINE=InnoDB;";
    dbDelta($sql1);
    
    
    //Questions Table 
    $table2_name = $wpdb->prefix . 'exam_question_tbl'; 
    $sql2 = "CREATE TABLE IF NOT EXISTS $table2_name (
        eqt_id INT NOT NULL AUTO_INCREMENT,
        exam_id INT NOT NULL,
        exam_question VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        question_image VARCHAR(255) COLLATE latin1_swedish_ci DEFAULT NULL,
        exam_ch1 VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        exam_ch2 VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        exam_ch3 VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        exam_ch4 VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        exam_answer VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
        exam_status VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL DEFAULT 'active',
        PRIMARY KEY (eqt_id)
    ) ENGINE=InnoDB;";
    dbDelta($sql2);
    

    // Table Answers
    $table3_name = $wpdb->prefix . 'exam_answers'; // Replace with your table name
$sql3 = "CREATE TABLE IF NOT EXISTS $table3_name (
    exans_id INT NOT NULL AUTO_INCREMENT,
    axmne_id INT NOT NULL,
    exam_id INT NOT NULL,
    quest_id INT NOT NULL,
    exans_answer VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL,
    exans_status VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL DEFAULT 'new',
    exans_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (exans_id)
) ENGINE=InnoDB;";
dbDelta($sql3);

      // Table Attempts
      $table4_name = $wpdb->prefix . 'exam_attempt'; // Replace with your table name
      $sql4 = "CREATE TABLE IF NOT EXISTS $table4_name (
          examat_id INT NOT NULL AUTO_INCREMENT,
          exmne_id INT NOT NULL,
          exam_id INT NOT NULL,
          examat_status VARCHAR(1000) COLLATE latin1_swedish_ci NOT NULL DEFAULT 'used',
          PRIMARY KEY (examat_id)
      ) ENGINE=InnoDB;";
      dbDelta($sql4);
      


    // Make sure to include dbDelta() only once after all table creation queries
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


    $plugin_images_dir = plugin_dir_path(__FILE__) . 'images/'; // Path to your plugin's images directory
    $upload_dir = wp_upload_dir(); // Get the uploads directory path

    $images_to_upload = array(
        'enroll.png',
        'login.png',
        'exam.png',
        // Add more images here if needed
    );

    foreach ($images_to_upload as $image) {
        $image_path = $plugin_images_dir . $image;

        if (file_exists($image_path)) {
            // Move the image to the uploads directory
            $destination_path = $upload_dir['path'] . '/' . $image;
            copy($image_path, $destination_path);
        }
    }
}



// Function to drop tables on plugin deactivation
function my_plugin_drop_tables() {
    global $wpdb;
    
    // Table exam_tbl
    $table1_name = $wpdb->prefix . 'exam_tbl'; // Replace with your table name
    $wpdb->query("DROP TABLE IF EXISTS $table1_name");
    
    // Table 2
    $table2_name = $wpdb->prefix . 'exam_question_tbl'; // Replace with your table name
    $wpdb->query("DROP TABLE IF EXISTS $table2_name");
    
   // exam_answers table
   $table3_name = $wpdb->prefix . 'exam_answers'; // Replace with your table name
   $wpdb->query("DROP TABLE IF EXISTS $table3_name");

      // Table exam_attempt
      $table4_name = $wpdb->prefix . 'exam_attempt'; // Replace with your table name
      $wpdb->query("DROP TABLE IF EXISTS $table4_name");
      

}

// Register activation hook
register_activation_hook(__FILE__, 'my_plugin_create_tables');

// Register deactivation hook
register_deactivation_hook(__FILE__, 'my_plugin_drop_tables');
// Schedule Exam Activation
register_activation_hook(__FILE__, 'schedule_custom_event');


// Include other files
require_once(plugin_dir_path(__FILE__) . 'includes/schedule.php');
require_once(plugin_dir_path(__FILE__) . 'includes/user-information-display.php');
require_once(plugin_dir_path(__FILE__) . 'includes/menu.php');
require_once(plugin_dir_path(__FILE__) . 'includes/user-information-retrieval.php');
require_once(plugin_dir_path(__FILE__) . 'includes/shortcode.php');
require_once(plugin_dir_path(__FILE__) . 'includes/main.php');
require_once(plugin_dir_path(__FILE__) . 'includes/scripts.php');
require_once(plugin_dir_path(__FILE__) . 'includes/form-submission-ajax.php');
require_once(plugin_dir_path(__FILE__) . 'includes/delete_exam.php');
require_once(plugin_dir_path(__FILE__) . 'includes/export_result.php');


?>

<?php
ob_end_clean();
?>