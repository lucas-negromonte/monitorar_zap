$(".sidebar-toggle").on("click", function () {
    $("nav.sidebar").toggleClass("toggled");
});
$(".sidebar span.sidebar-link").on("click", function () {
    var link = $(this).closest("li");
    var toggle = $(this).closest("li").attr("data-toggle");

    $(".sidebar .sidebar-item").find("ul").slideUp();
    $(".sidebar .sidebar-link").attr("aria-expanded", false);
    $(".sidebar .sidebar-item").attr("data-toggle", false);
    // $(".sidebar .sidebar-item").removeClass("active");

    if (toggle == "true") {
        link.attr("data-toggle", false);
        // link.removeClass("active");
        link.find(".sidebar-link").attr("aria-expanded", false);
        link.find("ul").slideUp();
    } else {
        link.attr("data-toggle", true);
        // link.addClass("active");
        link.find(".sidebar-link").attr("aria-expanded", true);
        link.find("ul").slideDown();
    }
});
$(function () {
    var link;
    // pegando a URL sem query_string
    var url = window.location.href.split('?')[0];
    $(".sidebar li.sidebar-item a").each(function () {
        link = $(this).attr("href").split('?')[0];
        if (link == url) {
            $(this).addClass('active')
                .parent('li.sidebar-subitem').addClass('active')
                .parent("ul.sidebar-dropdown").css('display', 'block')
                .siblings("span.sidebar-link").attr("aria-expanded", true)
                .parent("li.sidebar-item").addClass('active').attr('data-toggle', true);

            $(this).parent("li.sidebar-item").addClass('active');
        }
    });
});