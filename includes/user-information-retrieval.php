<?php 
// Function to retrieve user information
function get_user_info()
{
    // Get the current user object
    $current_user = wp_get_current_user();

    // Check if the user is logged in
    if ($current_user->ID !== 0) {
        // User is logged in, return user information
        $user_info = array(
            'username' => $current_user->user_login,
            'email' => $current_user->user_email,
            'display_name' => $current_user->display_name,
            'user_id' => $current_user->ID
        );
        return $user_info;
    } else {
        // User is not logged in
        return false;
    }
}

?>