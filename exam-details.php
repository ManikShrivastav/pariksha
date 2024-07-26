<?php
// exam-details.php

// Include WordPress core files
define('WP_USE_THEMES', false);
// require_once('../../../wp-load.php');

// Retrieve exam ID from URL parameter
if (isset($_GET['exam_id'])) {
    $exam_id = intval($_GET['exam_id']); // Sanitize input

    // Fetch exam details from the database based on the exam ID
    global $wpdb;
    $exam_table = $wpdb->prefix . 'exam_tbl'; // Replace 'exam_tbl' with your actual table name
    $course_table = $wpdb->prefix . 'posts';

    $exam_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM $exam_table WHERE ex_id = %d", $exam_id));

    // Display exam details
    if ($exam_details) {
        $courseId = $exam_details->cou_id;
        $course_details = $wpdb->get_row($wpdb->prepare("SELECT post_title as courseName FROM $course_table WHERE ID = %d", $courseId));
        // echo '<div class="wrap">';


        // Display Exam List Table
        echo '<div class="col-md-12">';
        echo '<div class="main-card mb-3">';
        echo '<div class="card-header">Manage Exam</div>';
        echo '<div class="card-body">';

        // Prepare SQL query using WordPress's $wpdb object
        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}exam_question_tbl WHERE exam_id='%d' ORDER BY eqt_id ASC", $exam_id);

        // Execute the query
        $results = $wpdb->get_results($query);


?>

        <div class="app-main__inner">
            <div class="col-md-12">
                <div id="refreshData">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="main-card mb-3 card" style="padding: 0;">
                                <div class="card-header">
                                    <i class="header-icon lnr-license icon-gradient bg-plum-plate"> </i>Exam Information
                                </div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=exam-details&exam_id=' . $exam_id)); ?>" id="updateExamFrm">
                                        <input type="hidden" name="action" value="update_exam_details">
                                        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('edit_exam_nonce'); ?>">

                                        <div class="form-group">
                                            <label>Course</label>
                                            <select class="form-control" name="courseId" required="" style="max-width:100%">
                                                <option value="<?php echo $exam_details->cou_id; ?>">
                                                    <?php echo $course_details->courseName; ?>
                                                </option>
                                                <?php
                                                $all_courses = $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type='product' ORDER BY ID DESC");
                                                // Check if there are any courses
                                                if ($all_courses) {
                                                    // Loop through each course and display as options
                                                    foreach ($all_courses as $course) {
                                                        echo '<option value="' . esc_attr($course->ID) . '">' . esc_html($course->post_title) . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">No Courses Found</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Exam Title</label>
                                            <input type="hidden" name="examId" value="<?php echo $exam_details->ex_id; ?>">
                                            <input type="" name="examTitle" class="form-control" required="" value="<?php echo esc_html($exam_details->ex_title); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Exam Description</label>
                                            <input type="" name="examDesc" class="form-control" required="" value="<?php echo esc_html($exam_details->ex_description); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Exam Time limit</label>
                                            <select class="form-control" name="examLimit" required="" style="max-width:100%">
                                                <option value="<?php echo esc_html($exam_details->ex_time_limit); ?>">
                                                    <?php echo esc_html($exam_details->ex_time_limit); ?> Minutes
                                                </option>
                                                <option value="30">30 Minutes</option>
                                                <option value="45">45 Minutes</option>
                                                <option value="60">60 Minutes</option>
                                                <option value="75">75 Minutes</option>
                                                <option value="90">90 Minutes</option>
                                                <option value="120">120 Minutes</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <lable>Schedule Exam(Optional)</lable>
                                            <input type="datetime-local" name="examSchedule" class="form-control" value="<?php echo esc_html($exam_details->scheduled_time); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Display limit</label>
                                            <input type="number" name="examQuestDipLimit" class="form-control" value="<?php echo esc_html($exam_details->ex_questlimit_display); ?>">
                                        </div>
                                        <div class="form-group" align="right">
                                            <button type="submit" name="update_btn_exam" class="btn btn-primary btn-lg">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php

                        // Check if results are obtained
                        ?>
                        <div class="col-md-6">
                            <div class="main-card mb-3 card" style="padding: 0;max-width: 100%;">
                                <div class="card-header"><i class="header-icon lnr-license icon-gradient bg-plum-plate">
                                    </i>Exam Question's
                                    <span class="badge badge-pill badge-primary ml-2">
                                        <?php echo count($results); ?>
                                    </span>
                                    <div class="btn-actions-pane-right">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" id="modalForAddQuestion">Add
                                            Question</button>
                                    </div>
                                </div>
                                <?php if ($results) {
                                ?>
                                    <div class="card-body">
                                        <div class="scroll-area-sm" style="min-height: 470px;">
                                            <div class="scrollbar-container">

                                                <div class="table-responsive">
                                                    <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left pl-1">Questions</th>
                                                                <th class="text-center" width="20%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $count = 0;
                                                            foreach ($results as $selQuestionRow) {
                                                                $count++;
                                                            ?>
                                                                <tr>
                                                                    <td>
                                                                        <b>

                                                                            <?php echo $count . '.)' . $selQuestionRow->exam_question; ?>
                                                                        </b><br>
                                                                        <?php
                                                                        // Choice A
                                                                        if ($selQuestionRow->exam_ch1 == $selQuestionRow->exam_answer) { ?>
                                                                            <span class="pl-4 text-success">A -
                                                                                <?php echo $selQuestionRow->exam_ch1; ?>
                                                                            </span><br>
                                                                        <?php } else { ?>
                                                                            <span class="pl-4">A -
                                                                                <?php echo $selQuestionRow->exam_ch1; ?>
                                                                            </span><br>
                                                                        <?php }
                                                                        // Choice B
                                                                        if ($selQuestionRow->exam_ch2 == $selQuestionRow->exam_answer) { ?>
                                                                            <span class="pl-4 text-success">B -
                                                                                <?php echo $selQuestionRow->exam_ch2; ?>
                                                                            </span><br>
                                                                        <?php } else { ?>
                                                                            <span class="pl-4">B -
                                                                                <?php echo $selQuestionRow->exam_ch2; ?>
                                                                            </span><br>
                                                                        <?php }
                                                                        // Choice C
                                                                        if ($selQuestionRow->exam_ch3 == $selQuestionRow->exam_answer) { ?>
                                                                            <span class="pl-4 text-success">C -
                                                                                <?php echo $selQuestionRow->exam_ch3; ?>
                                                                            </span><br>
                                                                        <?php } else { ?>
                                                                            <span class="pl-4">C -
                                                                                <?php echo $selQuestionRow->exam_ch3; ?>
                                                                            </span><br>
                                                                        <?php }
                                                                        // Choice D
                                                                        if ($selQuestionRow->exam_ch4 == $selQuestionRow->exam_answer) { ?>
                                                                            <span class="pl-4 text-success">D -
                                                                                <?php echo $selQuestionRow->exam_ch4; ?>
                                                                            </span><br>
                                                                        <?php } else { ?>
                                                                            <span class="pl-4">D -
                                                                                <?php echo $selQuestionRow->exam_ch4; ?>
                                                                            </span><br>
                                                                        <?php }
                                                                        ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-primary update-question" data-toggle="modal" data-target="#updateQuestionModal" data-id="<?php echo esc_attr($selQuestionRow->eqt_id); ?>" data-question="<?php echo esc_attr($selQuestionRow->exam_question); ?>" data-image="<?php echo esc_attr($selQuestionRow->question_image); ?>" data-choice-a="<?php echo esc_attr($selQuestionRow->exam_ch1); ?>" data-choice-b="<?php echo esc_attr($selQuestionRow->exam_ch2); ?>" data-choice-c="<?php echo esc_attr($selQuestionRow->exam_ch3); ?>" data-choice-d="<?php echo esc_attr($selQuestionRow->exam_ch4); ?>" data-correct-answer="<?php echo esc_attr($selQuestionRow->exam_answer); ?>">Update</button>

                                                                        <button type="button" data-nonce="<?php echo wp_create_nonce('delete_question_nonce'); ?>" class="btn btn-danger btn-sm delete-question" data-id="<?php echo esc_attr($selQuestionRow->eqt_id); ?>">Delete</button>
                                                                        <!-- <input type="hidden" id="delete-question-ajax-url"
                                                                            value="<?php echo admin_url('admin-ajax.php'); ?>"> -->

                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php } else {
                                    echo "<h4 class='text-primary'>No question found...</h4>";
                                } ?>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    <?php
        echo '</div>'; // Closing table-responsive div
        echo '</div>'; // Closing card-body div
        echo '</div>'; // Closing main-card div
        echo '</div>'; // Closing col-md-12 div
    } else {
        echo '<p>Exam not found.</p>';
    }
    ?>

<?php

} else {
    // Handle case when exam ID is not provided
    echo "<script>alert('You cannot access this page directly.');
    window.history.back();
    </script>";
}

require_once(plugin_dir_path(__FILE__) . 'includes/update_exam_function.php');
require_once(plugin_dir_path(__FILE__) . 'includes/add_question.php');
require_once(plugin_dir_path(__FILE__) . 'includes/update_question.php');
require_once(plugin_dir_path(__FILE__) . 'includes/scripts.php');

?>