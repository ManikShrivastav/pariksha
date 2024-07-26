<?php
if (isset($_POST['update_question']) && isset($_POST['action']) && $_POST['action'] == 'update_questions') {
    global $wpdb;

    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'update_questions_nonce')) {
        wp_send_json_error('Nonce verification failed');
    }

    $exam_id  = intval($_POST['question_id']);
    $question = sanitize_text_field($_POST['question']);
    $choice_A = sanitize_text_field($_POST['choice_A']);
    $choice_B = sanitize_text_field($_POST['choice_B']);
    $choice_C = sanitize_text_field($_POST['choice_C']);
    $choice_D = sanitize_text_field($_POST['choice_D']);
    $correct_answer = sanitize_text_field($_POST['correctAnswer']);

    // Handle file upload
    $question_image = sanitize_text_field($_POST['question_image_old']);
    if (!empty($_FILES['question_image']['name'])) {
        $upload_overrides = array('test_form' => false);
        $uploaded_file = wp_handle_upload($_FILES['question_image'], $upload_overrides);
        if (!empty($uploaded_file['url'])) {
            // File upload successful
            $upload_dir = wp_upload_dir();
            $file_name = basename($uploaded_file['url']);
            $file_path = $upload_dir['path'] . '/' . $file_name;

            // Generate a random and unique name
            $random_string = wp_generate_password(12, false); // Generate a random string of 12 characters
            $new_file_name = $random_string . '_' . time() . '_' . $file_name;

            // Rename the uploaded file
            if (rename($file_path, $upload_dir['path'] . '/' . $new_file_name)) {
                // Update the file URL with the new name
                $question_image = $upload_dir['url'] . '/' . $new_file_name;
            } else {
                // Failed to rename the file
                wp_send_json_error('Failed to rename the uploaded file.');
            }
        } else {
            // File upload failed
            wp_send_json_error('File upload failed.');
        }
    }

    // Update data into the wp_exam_question_tbl table
    $result = $wpdb->update(
        $wpdb->prefix . 'exam_question_tbl',
        array(
            'exam_question' => $question,
            'question_image' => $question_image,
            'exam_ch1' => $choice_A,
            'exam_ch2' => $choice_B,
            'exam_ch3' => $choice_C,
            'exam_ch4' => $choice_D,
            'exam_answer' => $correct_answer
        ),
        array(
            'eqt_id' => $exam_id // Update condition
        ),
        array(
            '%s', // exam_question
            '%s', // question_image
            '%s', // exam_ch1
            '%s', // exam_ch2
            '%s', // exam_ch3
            '%s', // exam_ch4
            '%s'  // exam_answer
        ),
        array(
            '%d' // exam_id
        )
    );
    

    if ($result === false) {
        // Database insertion failed
        $error_message = $wpdb->last_error;
        echo '<script>alert("Failed to update exam details. Error: ' . $error_message . '"); window.location.reload();</script>'; // Display error message in alert and reload the page

    } else {
        // Database insertion successful
        echo '<script>window.location.reload();</script>'; // Reload the page

    }
}
?>

<div class="modal fade" id="updateQuestionModal" tabindex="-1" role="dialog" aria-labelledby="updateQuestionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateQuestionModalLabel">Update Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="refreshFrm" method="post" id="updateQuestionFrm" enctype="multipart/form-data"
                action="<?php echo esc_url(admin_url('admin.php?page=exam-details&exam_id=' . $exam_id)); ?>">
                <input type="hidden" name="action" value="update_questions">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('update_questions_nonce'); ?>">
                <input type="hidden" name="question_id" id="updateQuestionId">
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Question</label>
                            <textarea class="form-control" name="question" id="updateQuestion" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Question Image(If it is in figure)</label>
                            <img id="updateQuestionImagePreview" src="" alt="Question Image" style="max-width: 100%; max-height: 200px;">
                            <input type="hidden" name="question_image_old" id="question_image_old">

            <input type="file" name="question_image" id="updateQuestionImage" class="form-control" autocomplete="off">
                        </div>
                        <fieldset>
                            <legend>Input word for choices</legend>
                            <div class="form-group">
                                <label>Choice A</label>
                                <input type="text" name="choice_A" id="updateChoiceA" class="form-control" placeholder="Input choice A" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>Choice B</label>
                                <input type="text" name="choice_B" id="updateChoiceB" class="form-control" placeholder="Input choice B" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>Choice C</label>
                                <input type="text" name="choice_C" id="updateChoiceC" class="form-control" placeholder="Input choice C" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>Choice D</label>
                                <input type="text" name="choice_D" id="updateChoiceD" class="form-control" placeholder="Input choice D" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>Correct Answer</label>
                                <input type="text" name="correctAnswer" id="updateCorrectAnswer" class="form-control" placeholder="Input correct answer" autocomplete="off">
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_question">Update Question</button>
                </div>
            </form>
        </div>
    </div>
</div>
