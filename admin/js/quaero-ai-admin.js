(function ($) {
  "use strict";

  jQuery(document).ready(function () {
    jQuery("#sync-website-links").on("click", function () {
      jQuery(".qai-progress-element").show();
      qai_update_progress_bar(0);
      var pages_limit = 1000;
      var data = {
        action: "get_total_posts",
      };

      jQuery.post(ajaxurl, data, async function (response) {
        response = parseInt(response);
        if (response < pages_limit) {
          // Get and push the pages based on pagination
          var request_data = {
            action: "sync_website_links",
            page: -1,
          };
          jQuery.post(ajaxurl, request_data, function (response) {
            qai_update_progress_bar("100");
            console.log("response" + response);
          });
        } else if (response > pages_limit) {
          // Get and push the pages based on pagination
          var pages = response / pages_limit;
          var progress = 0;
          for (let page = 1; page <= pages; page++) {
            progress = (page / pages) * 100;
            var request_data = {
              action: "sync_website_links",
              page: page,
            };

            await qai_exe_ajax(request_data, progress);
          }
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