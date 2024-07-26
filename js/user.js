jQuery(document).ready(function ($) {
    var totalMinutes;
    var remainingSeconds;
    var cdTimer;

    function cd() {
        var totalMinutesStr = $('#timeExamLimit').val();
        totalMinutes = parseInt(totalMinutesStr, 10);
        remainingSeconds = totalMinutes * 60; // Convert total minutes to seconds
        redo();
    }

    function formatTime(totalSeconds) {
        var mins = Math.floor(totalSeconds / 60);
        var secs = totalSeconds % 60;
        return (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
    }

    function displayTimeUpAlert() {
        // Display alert to inform user that time is up
        alert('Time is up! Click OK to view results.');
    }

    function redirectToResultPage() {
        // Redirect user to result page
        window.location.href = 'result-page-url'; // Replace 'result-page-url' with the actual URL of the result page
    }

    function submitFormAndRedirect() {
        var formData = $('#submitAnswerFrm').serialize();

        $.ajax({
            url: plugin_vars.ajax_url,
            type: 'POST',
            data: formData, // Replace 'your_exam_id_value' with the actual exam ID
            success: function (response) {
                // alert(response.answers); // Display the message from the response
                if (response.success) {
                    // console.log('Exam ID:', response.exam_id);
                    // console.log('Answers:', response.answers);
                    // Redirect to result page after displaying output
                    // window.location.href = 'result-page-url'; // Replace 'result-page-url' with the actual URL of the result page
                    console.log(response);
                    location.reload();

                }
            },
            error: function (xhr, status, error) {
                // Handle AJAX error
                console.error(xhr.responseText); // Log the full error response for debugging
                alert('An error occurred. Please try again.');
            }
        });
    }
    

    function redo() {
        remainingSeconds--;
        if (remainingSeconds < 0) {
            clearTimeout(cdTimer);
            // Handle time expiration
            $('#txt').val('00:00'); // Update display to show 00:00
            displayTimeUpAlert(); // Display time up alert
            // Call submitFormAndRedirect function when user clicks anywhere on the alert
            $(document).one('click', submitFormAndRedirect);
            return;
        }
        $('#txt').val(formatTime(remainingSeconds)); // Update display with remaining time
        cdTimer = setTimeout(redo, 1000);
    }

    function init() {
        cd();
        $('#submitAnswerFrmBtn').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission
            submitFormAndRedirect(); // Submit the form
        });
    }

    $('#start_exam').on('click', function (e) {
        init();
    });

    // Clear timeout when the user navigates away
    $(window).on('unload', function () {
        clearTimeout(cdTimer);
    });



    
});

// Check if page is refreshed
