<?php
// Add action hook to handle form submission
add_action('admin_post_add_exam_form_submission', 'process_add_exam_form');

function process_add_exam_form()
{
    // Check if the form is submitted
    if (isset($_POST['add_exam_submit'])) {
        // Sanitize and retrieve form data
        $courseSelected = isset($_POST['courseSelected']) ? sanitize_text_field($_POST['courseSelected']) : '';
        $timeLimit = isset($_POST['timeLimit']) ? intval($_POST['timeLimit']) : 0;
        $examQuestDipLimit = isset($_POST['examQuestDipLimit']) ? intval($_POST['examQuestDipLimit']) : 0;
        $examTitle = isset($_POST['examTitle']) ? sanitize_text_field($_POST['examTitle']) : '';
        $examDesc = isset($_POST['examDesc']) ? $_POST['examDesc'] : '';
        // Schedule time exam
        $activation_time = isset($_POST['activation_time']) ? $_POST['activation_time'] : '';

        if(empty($activation_time)){
            $status = true ;
            $gmt_time = NULL;
        }
        else{
            $status = false ;  
            // Fetch timezone string from WordPress settings
        $timezone_string = get_option('timezone_string');
        
        // Check if timezone string is empty
        if (empty($timezone_string)) {
            // If timezone string is empty, set default timezone to Kathmandu
            $timezone = new DateTimeZone('Asia/Kathmandu');
        } else {
            // Otherwise, use the fetched timezone string
            $timezone = new DateTimeZone($timezone_string);
            
        }
        
        // Adjust the date format to include 'T' in the middle
        $activation_time_obj = DateTime::createFromFormat('Y-m-d\TH:i', $activation_time, $timezone);
        
        $gmt_activation_time = new DateTime($activation_time_obj->format('Y-m-d H:i:s'), new DateTimeZone('GMT'));
        
        $gmt_time = $gmt_activation_time->format('Y-m-d H:i:s');
        }
        

        
    

        // Perform validation
        // You can add your validation logic here

        // If validation passes, proceed to save data
        global $wpdb;
        $exam_table = $wpdb->prefix . 'exam_tbl';

        $data = array(
            'cou_id' => $courseSelected,
            'ex_time_limit' => $timeLimit,
            'ex_questlimit_display' => $examQuestDipLimit,
            'ex_title' => $examTitle,
            'ex_description' => $examDesc,
            'ex_status' => $status,
            'scheduled_time' => $gmt_time
        );

        $wpdb->insert($exam_table, $data);

        // Redirect after form submission
        wp_redirect(admin_url('admin.php?page=pariksha-plugin'));
        exit;
    }
}
?>