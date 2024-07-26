<?php
// Function to display the admin menu page content// Function to display the admin menu page content
function user_info_plugin_page()
{
    global $wpdb;

    // if (isset($_GET['page']) && $_GET['page'] == 'exam-details') {
    //     include_once plugin_dir_path(__FILE__) . 'exam-details.php';
    //     exit; // Stop further execution to prevent duplication of content
    // }
    echo '<div class="wrap">';

    // Display Exam List Table
    echo '<div class="col-md-12">';
    echo '<div class="main-card mb-3">';
    echo '<div class="card-header">Pariksha</div>';
    echo '<div class="card-body">';

    // Add Data Section
    echo '<button id="add-data-btn" class="btn btn-primary mb-3">Add Exam</button>';
    ?>
    <div class="modal fade show" id="add-data-form-inner" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="refreshFrm" id="addExamFrm" method="post"
                action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="add_exam_form_submission">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Exam</h5>

                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Select Course</label>
                                <select class="form-control" name="courseSelected" style="max-width: 100%;">
                                    <option value="0">Select Course</option>
                                    <?php
                                    $selCourse = $wpdb->get_results("SELECT ID,post_title FROM {$wpdb->prefix}posts WHERE post_type = 'product'  ORDER BY ID DESC");
                                    if ($selCourse) {
                                        foreach ($selCourse as $selCourseRow) {
                                            echo '<option value="' . esc_attr($selCourseRow->ID) . '">' . esc_html($selCourseRow->post_title) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="0">No Course Found</option>';
                                    }
                                    ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Exam Time Limit</label>
                                <select class="form-control" name="timeLimit" required="" style="max-width: 100%;">
                                    <option value="0">Select time</option>
                                    <option value="30">30 Minutes</option>
                                    <option value="45">45 Minutes</option>
                                    <option value="60">60 Minutes</option>
                                    <option value="75">75 Minutes</option>
                                    <option value="90">90 Minutes</option>
                                    <option value="120">120 Minutes</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Question Limit to Display</label>
                                <input type="number" name="examQuestDipLimit" id="" class="form-control"
                                    placeholder="Input question limit to display">
                            </div>
                            
                            <div class="form-group">
                                <label>Schedule Exam(Optional)</label>                                
                                    <input type="datetime-local" class="form-control"  id="activation_time" name="activation_time">
                            </div>
                            <div class="form-group">
                                <label>Exam Title</label>
                                <input type="" name="examTitle" class="form-control" placeholder="Input Exam Title"
                                    required="">
                            </div>

                            <div class="form-group">
                                <label>Exam Description</label>
                                <textarea name="examDesc" class="form-control" rows="4" placeholder="Input Exam Description"
                                    required=""></textarea>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="examclose">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_exam_submit">Add Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
    // Exam List Table
    echo '<div class="table-responsive">';
    echo '<table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="text-left pl-4">Exam Title</th>';
    echo '<th class="text-left ">Course</th>';
    echo '<th class="text-left ">Shortcode</th>';
    echo '<th class="text-left ">Time limit</th>';
    echo '<th class="text-left ">Display limit</th>';
    echo '<th class="text-center" width="20%">Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    check_scheduled_records();

    $exam_table = $wpdb->prefix . 'exam_tbl'; // Assuming your table name is prefixed with WordPress table prefix
    $course_table = $wpdb->prefix . 'posts'; // Assuming your course table name is prefixed with WordPress table prefix
    $exam_results = $wpdb->get_results("SELECT * FROM $exam_table ORDER BY ex_id DESC");
    if ($exam_results) {
        foreach ($exam_results as $exam_row) {
            $shortcode_exam = '[display_user_info exam_id="'.$exam_row->ex_id.'"]';
            $course_results = $wpdb->get_results("SELECT post_title FROM $course_table WHERE ID ='$exam_row->cou_id'");
            echo '<tr>';
            echo '<td class="pl-4">' . esc_html($exam_row->ex_title) . '</td>';
            if ($course_results) {
                foreach ($course_results as $course_row) {
                    echo '<td>' . esc_html($course_row->post_title) . '</td>';
                }
            } else {
                echo '<td>Course not found</td>';
            }
            echo '<td>' . $shortcode_exam . '</td>';
            echo '<td>' . esc_html($exam_row->ex_time_limit) . '</td>';
            echo '<td>' . esc_html($exam_row->ex_questlimit_display) . '</td>';
            echo '<td class="text-center">';
            ?> <a href="<?php echo esc_url(admin_url('admin.php?page=exam-details&exam_id=' . $exam_row->ex_id)); ?>"
                class="btn btn-primary btn-sm">Manage</a>
            <?php echo '<button type="button" id="deleteExam" data-id="' . esc_attr($exam_row->ex_id) . '" class="btn btn-danger btn-sm" data-nonce="' . wp_create_nonce('delete_exam_nonce') . '">Delete</button>';
            echo '</td>';
            echo '</tr>';

        }
    } else {
        echo '<tr><td colspan="6"><p class="p-3">No Exam Found</p></td></tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Closing table-responsive div
    echo '</div>'; // Closing card-body div
    echo '</div>'; // Closing main-card div
    echo '</div>'; // Closing col-md-12 div
    echo '</div>'; // Closing wrap div
}

?>