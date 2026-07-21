jQuery(document).ready(function ($) {
  console.log('setup working');

  /* Animation Reveal */
  const $reveals = $('.reveal');

  // if (!$reveals.length) {
  //   return;
  // }

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        $(entry.target).addClass('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15
  });

  $reveals.each(function () {
    observer.observe(this);
  });

  $("#new-activity").on("click", function(){
    const $button = $(this);
    const $result = $("#activity-result");

    $button.prop("disabled", true);
    $result.html("<p>Finding an activity...</p>");

    $.ajax({
      url: wp_ajax_object.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "get_bored_activity"
      },
      success: function (response){
        if (!response.success){
          $result.html(
            $("<p>").text(response.data.message)
          );

          return;
        }

        const activity = response.data;

        const $markup = $("<article>", {
          class: "bored-activity"
        });

        $("<h2>")
          .text(activity.activity)
          .appendTo($markup);

        $("<p>")
          .text("Price " + activity.price)
          .appendTo($markup);

        $("<button>")
          .text("Complete")
          .attr("data-id", activity.id)
          .addClass("complete-btn")
          .appendTo($markup);

        $("<button>")
          .text("Delete")
          .attr("data-id", activity.id)
          .addClass("delete-btn")
          .appendTo($markup);

        $result.empty().append($markup);
      },
      error: function(){
        $result.html(
          "<p>Error</p>"
        );
      },
      complete: function(){
        $button.prop("disabled", false);
      }
    });
  });

  $(document).on("click", ".complete-btn", function(){
    $.post(wp_ajax_object.ajax_url, {
      action: "complete_activity",
      id: $(this).data("id")
    });

    $(this).text("Completed");
  })

  $(document).on("click", ".delete-btn", function(){
    const $button = $(this);
    const id = $button.data("id");

    $.post(wp_ajax_object.ajax_url, {
      action: "delete_activity",
      id: id
    });

    $button.closest(".bored-activity").remove();
  })
});