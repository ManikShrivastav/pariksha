<?php
// Set the response type to JSON
header('Content-Type: application/json');
require_once ('../../../../wp-load.php');

// Initialize response array
$response = array();
global $wpdb;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the submitted form data
    if (isset ($_POST['exam_id'])) {
        // Extract the exam ID
        if (isset ($_POST['examineeid'])) {
            $examId = $_POST['exam_id'];
            $examineeid = $_POST['examineeid'];

            $ex_attempt_table = $wpdb->prefix . 'exam_attempt'; // Adding WordPress table prefix
            $ex_answers_table = $wpdb->prefix . 'exam_answers'; // Adding WordPress table prefix

            $selExAttempt = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $ex_attempt_table WHERE exmne_id = %d AND exam_id = %d",
                    $examineeid,
                    $examId
                )
            );

            $selAns = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $ex_answers_table WHERE axmne_id = %d AND exam_id = %d",
                    $examineeid,
                    $examId
                )
            );

            if (!empty ($selExAttempt)) {
                // Handle invalid request
                $response['success'] = false;
                $response['message'] = "You have already given this exam.";
            } elseif (!empty ($selAns)) {
                $response['success'] = false;
                $response['message'] = "Internal Database Error Occured! Error: 0x00Va006";
            } else {

                // Initialize an array to store answers
                $answers = array();

                // Loop through the submitted answers
            // foreach ($_POST['answer'] as $questionId => $answer) {
            //     // Store each answer along with the corresponding question ID
            //     $answers[$questionId] = $answer['correct'];
            //     $wpdb->insert(
            //         $ex_answers_table,
            //         array('axmne_id' => $exmne_id, 'exam_id' => $exam_id, 'quest_id' => $key, 'exans_answer' => $value)
            //     );
            // }
                foreach ($_REQUEST['answer'] as $key => $value) {
                    $value = $value['correct'];
                    $wpdb->insert(
                        $ex_answers_table,
                        array('axmne_id' => $examineeid, 'exam_id' => $examId, 'quest_id' => $key, 'exans_answer' => $value)
                    );
                }

                $insAttempt = $wpdb->insert(
                    $ex_attempt_table,
                    array('exmne_id' => $examineeid, 'exam_id' => $examId)
                );

                if ($insAttempt) {
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Error: 0x00Va005";
        }

    } else {
        // If exam_id is not set in the POST data
        $response['success'] = false;
        $response['message'] = "Error: 0x00Va004";
    }
} else {
    // Handle invalid request
    $response['success'] = false;
    $response['message'] = "Invalid request!";
}

// Encode the response as JSON and output it
echo json_encode($response);
?>