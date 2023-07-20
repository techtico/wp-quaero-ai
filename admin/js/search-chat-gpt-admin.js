(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  jQuery(document).ready(function () {
    jQuery("#sync-website-links").on("click", function () {
      jQuery(".scg-progress-element").show();
      updateProgressBar(0);
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
            updateProgressBar("100");
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

            await scg_exe_ajax(request_data, progress);
          }
        }
      });

      async function scg_exe_ajax(args, progress) {
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
              updateProgressBar(progress);
            });

          return result;
        } catch (error) {
          console.error(error);
        }
      }
    });

    function updateProgressBar(progress) {
      jQuery(".scg-progress-percentage").html(progress + "%");
      jQuery(".scg-progress-done").css("width", progress + "%");
      jQuery("#scg-search-sync").val(progress);
    }
  });
})(jQuery);
