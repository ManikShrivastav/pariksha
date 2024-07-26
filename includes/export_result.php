<?php
function export_exam_results_callback()
{
    // Check if user has permission
    if (!current_user_can('manage_options')) {
        wp_send_json_error('You do not have permission to export exam results.', 403);
    }

    // Check if exam ID is provided in the request
    if (!isset($_POST['exam_id'])) {
        wp_send_json_error('Exam ID is missing.', 400);
    }

    // Get exam ID from the request
    $exam_id = intval($_POST['exam_id']);

    // Fetch exam details from the database based on the exam ID
    global $wpdb;
    $exam_table = $wpdb->prefix . 'exam_tbl';
    $exam_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM $exam_table WHERE ex_id = %d", $exam_id));

    // Check if exam details are found
    if (!$exam_details) {
        wp_send_json_error('Exam details not found.', 404);
    }

    // Fetch exam results from another table in the database
    $exam_results_table = $wpdb->prefix . 'exam_answers';
    $exam_results = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT axmne_id FROM $exam_results_table WHERE exam_id = %d", $exam_id), ARRAY_A);

    // Check if exam results are found
    if (!$exam_results) {
        wp_send_json_error('No results found for this exam.', 404);
    }

    // Prepare exam data
    $export_data = array();
    $export_data[] = array('Name', 'Address', 'Correct Answers', 'Incorrect Answers', 'Total Questions', 'Score', 'Percentage');
    foreach ($exam_results as $result) {
        // Fetch correct and incorrect answers
        $selScores = $wpdb->get_results($wpdb->prepare("
            SELECT 
                SUM(CASE WHEN eqt.exam_answer = ea.exans_answer THEN 1 ELSE 0 END) as correct_count,
                SUM(CASE WHEN eqt.exam_answer != ea.exans_answer THEN 1 ELSE 0 END) as incorrect_count,
                us.display_name
            FROM {$wpdb->prefix}exam_question_tbl eqt
            INNER JOIN {$wpdb->prefix}exam_answers ea 
            INNER JOIN {$wpdb->users} us ON
            us.ID = ea.axmne_id AND
            eqt.eqt_id = ea.quest_id
            WHERE ea.axmne_id = %d
            AND ea.exam_id = %d
            AND ea.exans_status = 'new'
            GROUP BY ea.axmne_id
        ", $result['axmne_id'], $exam_id));

        foreach ($selScores as $scoreRow) {
            $correct_count = $scoreRow->correct_count;
            $incorrect_count = $scoreRow->incorrect_count;
            $total_questions = $exam_details->ex_questlimit_display;

            // Calculate score
            $score = ($correct_count * 2) - ($incorrect_count * 0.2);
            $percentage = number_format(($correct_count / $total_questions) * 100, 2) . '%';

            // Fetch user's address from WooCommerce usermeta
            $user_id = $result['axmne_id'];
            $address1 = get_user_meta($user_id, 'billing_address_1', true);
            $city = get_user_meta($user_id, 'billing_city', true);
            $state = get_user_meta($user_id, 'billing_state', true);
            $postcode = get_user_meta($user_id, 'billing_postcode', true);

            // Format the address
            $formatted_address = '';
            if ($address1 || $city || $state || $postcode) {
                $formatted_address = implode(', ', array_filter([$address1, $city, $state, $postcode]));
            } else {
                $formatted_address = 'Address not available';
            }

            $export_data[] = array(
                $scoreRow->display_name,
                $formatted_address,
                $correct_count,
                $incorrect_count,
                $total_questions,
                number_format($score, 2), // Format the score
                $percentage
            );
        }
    }

    // Return exam data
    wp_send_json_success($export_data);
}

// AJAX Handler to Export Exam Results
add_action('wp_ajax_export_exam_results', 'export_exam_results_callback');
?>
