(function ($) {
  "use strict";

  jQuery(document).ready(function () {
    jQuery("#sync-website-links").on("click", function (e) {
      e.preventDefault();
      if (jQuery(this).hasClass("disabled")) {
        return false;
      }
      jQuery(".qai-progress-element").show();
      qai_update_progress_bar(0);
      var pages_limit = 10;
      var data = {
        action: "get_total_posts",
      };

      jQuery.post(ajaxurl, data, async function (response) {
        response = parseInt(response);

        // Get and push the pages based on pagination
        var pages = Math.ceil(response / pages_limit);
        var progress = 0;
        for (let page = 1; page <= pages; page++) {
          progress = (page / pages) * 100;
          var request_data = {
            action: "sync_website_links",
            page: page,
          };

          await qai_exe_ajax(request_data, progress);
        }
      });
    });

    async function qai_exe_ajax(args, progress) {
      let result;

      try {
        result = await jQuery
          .ajax({
            type: "POST",
            url: ajaxurl,
            data: args,
            async: false,
          })
          .done(function () {
            qai_update_progress_bar(progress);
          });

        return result;
      } catch (error) {
        console.error(error);
      }
    }

    function qai_update_progress_bar(progress) {
      jQuery(".qai-progress-percentage").html(progress + "%");
      jQuery(".qai-progress-done").css("width", progress + "%");
      jQuery("#qai-search-sync").val(progress);
    }
  });
})(jQuery);
