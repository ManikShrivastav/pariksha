<?php 
// Function to schedule event
function schedule_custom_event() {
    if (!wp_next_scheduled('custom_activation_event')) {
        wp_schedule_event(time(), 'daily', 'custom_activation_event');
    }
}


// Function to check and update scheduled records
function check_scheduled_records() {
    // Get scheduled records from the database
    $records = get_scheduled_records();

    // Loop through records
    foreach ($records as $record) {
        // Check if it's time to publish
        if (strtotime($record->scheduled_time) <= current_time('timestamp')) {
            // Update status to published
            update_record_status($record->ex_id, TRUE);
        }
    }
}

// Function to retrieve scheduled records from database
function get_scheduled_records() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'exam_tbl'; 
    $sql = "SELECT * FROM $table_name";
    $records = $wpdb->get_results($sql);
    return $records;
}

// Function to update record status
function update_record_status($id, $status) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'exam_tbl'; // Replace 'custom_table' with your table name

    $wpdb->update(
        $table_name,
        array('ex_status' => $status),
        array('ex_id' => $id)
    );
}

// Hook into WordPress cron event
add_action('custom_activation_event', 'check_scheduled_records');
?>