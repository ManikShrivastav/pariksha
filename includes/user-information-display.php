<?php 
// Function to retrieve and display user information
function display_user_info()
{
    // Get the current user object
    $current_user = wp_get_current_user();

    // Check if the user is logged in
    if ($current_user->ID !== 0) {

        $user_id = $current_user->ID;

        
        // User is logged in, display user information
        echo '<h2>User Information</h2>';
        echo '<div class="user-info-table">';
        echo '<table>';
        echo '<tr><th>Username</th><th>Email</th><th>Display Name</th><th>User ID</th></tr>';
        echo '<tr>';
        echo '<td>' . $current_user->user_login . '</td>';
        echo '<td>' . $current_user->user_email . '</td>';
        echo '<td>' . $current_user->display_name . '</td>';
        echo '<td>' . $current_user->ID . '</td>';
        echo '</tr>';
        echo '</table>';
        echo '</div>';
    } else {
        // User is not logged in
        echo '<p>User is not logged in.</p>';
    }
}

?>