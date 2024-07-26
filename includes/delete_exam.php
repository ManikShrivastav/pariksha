<?php
// Function to handle AJAX request for deleting exam
function delete_exam_ajax_callback()
{
    global $wpdb;

    // Check if the request is valid and has a valid nonce
    if (isset($_POST['exam_id']) && isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'delete_exam_nonce')) {
        $exam_id = intval($_POST['exam_id']);

        // Delete the exam record from the database
        $table_name = $wpdb->prefix . 'exam_tbl';
        $wpdb->delete($table_name, array('ex_id' => $exam_id), array('%d'));

        // Send a response
        wp_send_json_success('Exam deleted successfully.');
    } else {
        // Invalid request
        wp_send_json_error('Invalid request.');
    }
}
add_action('wp_ajax_delete_exam', 'delete_exam_ajax_callback');


function delete_question_ajax_handler() {
    // Check if the question ID is set in the GET request
    if (isset($_POST['id']) && isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'delete_question_nonce')) {
        // Sanitize and retrieve the question ID
        $questionId = intval($_POST['id']);

        // Perform deletion
        global $wpdb;
        $table_name = $wpdb->prefix . 'exam_question_tbl';
        $result = $wpdb->delete($table_name, array('eqt_id' => $questionId));

        // Check if deletion was successful
        if ($result !== false) {
            // If deletion is successful, return "success"
            wp_send_json_success('Question deleted successfully.');
        } else {
            // If deletion fails, return an error message
            wp_send_json_error('Invalid request.');
        }
    } 
}
add_action('wp_ajax_delete_question', 'delete_question_ajax_handler');
?>
