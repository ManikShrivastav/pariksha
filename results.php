<?php
// exam-details.php

// Include WordPress core files
define('WP_USE_THEMES', false);
// require_once('../../../wp-load.php');
global $wpdb;
$exam_table = $wpdb->prefix . 'exam_tbl'; 
$course_table = $wpdb->prefix . 'posts';

// Fetch all exam details
$exam_details = $wpdb->get_results("SELECT * FROM $exam_table");

echo '<div class="wrap">';
echo '<div class="col-md-12">';
echo '<div class="main-card mb-3">';
echo '<div class="card-header">Results</div>';
echo '<div class="card-body">';

if ($exam_details) {
    echo '<div class="table-responsive">';
    echo '<table class="align-middle mb-0 table table-borderless table-striped table-hover">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="text-left pl-4">Exam Title</th>';
    echo '<th class="text-left">Exam Date</th>';
    echo '<th class="text-left">Course Name</th>';
    echo '<th class="text-center">Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($exam_details as $exam) {
        $courseId = $exam->cou_id;
        $course_name = $wpdb->get_row($wpdb->prepare("SELECT post_title as courseName FROM $course_table WHERE ID = %d", $courseId));

        echo '<tr>';
        echo '<td class="pl-4">' . esc_html($exam->ex_title) . '</td>';
        echo '<td>' . esc_html($exam->ex_created) . '</td>';
        echo '<td>' . esc_html($course_name->courseName) . '</td>';
        echo '<td class="text-center">';
        echo '<a href="#" class="btn btn-primary export-results" data-exam-id="' . esc_attr($exam->ex_id) . '">Export</a>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>'; 
} else {
    echo '<p>No exam details found.</p>';
}

echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

// Register AJAX handler for exporting results
add_action('wp_ajax_export_exam_results', 'export_exam_results');

function export_exam_results() {
    global $wpdb;
    $exam_id = intval($_POST['exam_id']);
    $exam_table = $wpdb->prefix . 'exam_tbl';
    $results_table = $wpdb->prefix . 'results_tbl'; // Assuming you have a results table

    // Fetch exam results
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $results_table WHERE exam_id = %d", $exam_id), ARRAY_A);


    if ($results) {
        wp_send_json_success($results);
    } else {
        wp_send_json_error('No results found.');
    }
}
?>
<script>
  jQuery(document).ready(function ($) {
    // Export Results Button Click Event
    $(document).on('click', '.export-results', function () {
        var examId = $(this).data('exam-id');

        // AJAX request to export results
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            dataType: 'json', // Expect JSON response
            data: {
                action: 'export_exam_results',
                exam_id: examId
            },
            success: function (response) {
                if (response.success) {
                    // Generate XLSX file
                    generateXLSX(response.data);
                } else {
                    // Handle errors
                    alert('Error: ' + response.data);
                }
            },
            error: function (xhr, status, error) {
                // Log error to console
                console.error('Error:', error);
            }
        });
    });
});

function generateXLSX(data) {
    // Create a new workbook
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.json_to_sheet(data);

    // Add the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, "Exam Results");

    // Generate XLSX file and trigger download
    XLSX.writeFile(wb, 'exam_results.xlsx');
}
</script>
