
(function($) {

Drupal.behaviors.page_editor = {
  attach: function () {
    $('#page-node-form #edit-title')
    .after(' &nbsp; <span id="title-length-check"></span>').keyup(function() {
      var length = $(this).val().length;
      var title_msg, title_color;
      if (length <= 35) {
        title_msg = length + ' chars. Increase title to optimize for search engine.';
        title_color = '#fc0';
      }
      else if (length <= 65) {
        title_msg = length + ' chars.';
        title_color = '#eee';
      }
      else if (length <= 90) {
        title_msg = length + ' chars. Shorten title to optimize for search engines.';
        title_color = '#fc0';
      }
      else {
        title_msg = length + ' chars. Shorten title to optimize for search engines.';
        title_color = '#f93';
      }
      $(this).next('#title-length-check').css('background-color', title_color).html(title_msg);
    });
  }
};

})(jQuery);
