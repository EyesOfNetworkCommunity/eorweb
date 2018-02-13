//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size

(function (window, document, $) {
  $(window).on("load resize", function () {
    var topOffset = 50;
    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
    if (width < 768) {
      $('div.navbar-collapse').addClass('collapse');
      topOffset = 100; // 2-row-menu
    } else {
      $('div.navbar-collapse').removeClass('collapse');
    }

    var height = ((window.innerHeight > 0) ? window.innerHeight : screen.height) - 1;
    height = height - topOffset;
    if (height < 1)
      height = 1;
    if (height > topOffset) {
      $("#page-wrapper").css("min-height", (height) + "px");
    }
  });

  $(document).ready(function () {
    $('#side-menu').metisMenu();

    var url = window.location;
    // var element = $('ul.nav a').filter(function() {
    //     return this.href == url;
    // }).addClass('active').parent().parent().addClass('in').parent();
    var element = $('ul.nav a').filter(function () {
      return this.href == url;
    }).addClass('active').parent();

    while (true) {
      if (element.is('li')) {
	element.addClass('active');
        element = element.parent().addClass('in').parent();
      } else {
        break;
      }
    }
  });
}(this, document, jQuery));
