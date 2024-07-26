jQuery(document).ready(function ($) {
  $("#add-data-btn").click(function () {
    $("#add-data-form").toggle();
    $("#add-data-form-inner").get(0).style.display = "block";
    $("#add-data-form-inner").get(0).style.overflowY = "auto"; // Corrected overflowY
  });

  // Add event listener to close button
  $("#examclose").click(function () {
    $("#add-data-form").toggle();
    $("#add-data-form-inner").get(0).style.display = "none";
  });

  // Add Event Listener for add question
  jQuery(document).ready(function ($) {
    $("#modalForAddQuestion").on("click", function () {
      $("#addQuestionModal").modal("show");
      $("#addQuestionModal").get(0).style.display = "block";
      $("#addQuestionModal").get(0).style.overflowY = "auto"; // Corrected overflowY
    });
  });

  //   Update Question
  jQuery(document).ready(function ($) {
    $("#modalForupdateQuestion").on("click", function () {
      $("#updateQuestionModal").modal("show");
      $("#updateQuestionModal").get(0).style.display = "block";
      $("#updateQuestionModal").get(0).style.overflowY = "auto"; // Corrected overflowY
    });
  });

  //   Update question data
  // Handle click event on update button
  $(".update-question").on("click", function () {
    // Retrieve data attributes from the clicked button
    var questionId = $(this).data("id");
    var question = $(this).data("question");
    var image = $(this).data("image");
    var choiceA = $(this).data("choice-a");
    var choiceB = $(this).data("choice-b");
    var choiceC = $(this).data("choice-c");
    var choiceD = $(this).data("choice-d");
    var correctAnswer = $(this).data("correct-answer");

    // Populate modal fields with retrieved values
    $("#updateQuestionId").val(questionId);
    $("#updateQuestion").val(question);
    $("#updateQuestionImagePreview").attr("src", image); // Update image preview
    $("#question_image_old").val(image);
    $("#updateChoiceA").val(choiceA);
    $("#updateChoiceB").val(choiceB);
    $("#updateChoiceC").val(choiceC);
    $("#updateChoiceD").val(choiceD);
    $("#updateCorrectAnswer").val(correctAnswer);

    // Show the modal
    $("#updateQuestionModal").modal("show");
  });

  // Delete Question
  jQuery(document).ready(function ($) {
    // Function to handle delete question AJAX request
    function deleteQuestion(questionId, nonce) {
      // Confirm deletion
      if (confirm("Are you sure you want to delete this question?")) {
        // Send AJAX request
        $.ajax({
          url: ajaxurl,
          type: "POST",
          data: {
            action: "delete_question",
            id: questionId,
            nonce: nonce,
          },
          success: function (response) {
            // Refresh the page after successful deletion
            location.reload();
            console.log(response);
          },
          error: function (xhr, status, error) {
            alert(
              "An error occurred while deleting the exam." + error + status
            );
            console.error(xhr.responseText);
          },
        });
      }
    }

    // Event listener for delete buttons
    $(document).on("click", ".delete-question", function () {
      var questionId = $(this).data("id");
      var nonce = $(this).data("nonce");
      deleteQuestion(questionId, nonce);
    });
  });

  // Event listener for delete exam button
  $("body").on("click", "#deleteExam", function () {
    var examId = $(this).data("id");
    var nonce = $(this).data("nonce");

    // Confirm deletion
    if (confirm("Are you sure you want to delete this exam?")) {
      // Send AJAX request
      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "delete_exam",
          exam_id: examId,
          nonce: nonce,
        },
        success: function (response) {
          // Refresh the page after successful deletion
          location.reload();
          console.log(response);
        },
        error: function (xhr, status, error) {
          alert("An error occurred while deleting the exam.");
          console.error(xhr.responseText);
        },
      });
    }
  });
});

// Exam Starts for Students
  function showOtherContents(){
    var warningBox = document.getElementById('warningBox');
        var mainContents = document.getElementById('mainContents');

        if (warningBox && mainContents) {
            warningBox.style.display = 'none';
            mainContents.style.display = 'block';
        }
  }
