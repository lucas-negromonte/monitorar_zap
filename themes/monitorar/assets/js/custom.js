var div = $(".full-height");

$('body').tooltip({ selector: '[data-toggle="tooltip"]' });

$(function () {
    $(window).on('resize scroll ready', function () {
        if (isOnScreen(div)) {
            fixHeight(div);
        }
    });
});

$(document).ready(function () {
    setTimeout(function () {
        if (isOnScreen(div)) {
            fixHeight(div);
        }
    }, 100);
});

function fixHeight(el) {
    var win = $(window).height();
    var table = $(".my-table-sticky");
    var footer = $("footer").outerHeight();
    var navbar = $("nav.navbar").outerHeight();

    var tableTop = $("#datatable-top").outerHeight();
    var newHeight = win - navbar - footer;

    el.height(newHeight);
    table.height(newHeight - tableTop * 2);
}

function isOnScreen(element) {
    if (!element.length) {
        return false;
    }
    var win = $(window);

    var screenTop = win.scrollTop();
    var screenBottom = screenTop + win.height(); //win.height()

    var elementTop = element.offset().top;
    var elementBottom = elementTop + element.height();

    return ((elementTop <= screenBottom) && (elementBottom >= screenTop));
}

// copiando conteudo de uma div ou input
$("[data-copyfrom]").on("click", function (e) {
    e.preventDefault();
    var id = $(this).data("copyfrom");
    var nodename = $("#" + id).get(0).nodeName;

    var node = document.getElementById(id);
    // console.log(nodename, node);

    if (nodename == "INPUT") {
        node.select();
        node.setSelectionRange(0, 99999); /*For mobile devices*/
    } else {
        var range = document.createRange();
        range.selectNode(node);

        window.getSelection().removeAllRanges(); // clear current selection
        window.getSelection().addRange(range); // to select text
    }

    document.execCommand("copy");
    window.getSelection().removeAllRanges();// to deselect
});
$('[data-toggle="tooltip"]').on("click", function () {
    $(this).tooltip("show");
    $(this).on('hidden.bs.tooltip', function () {
        $(this).tooltip("dispose")
    })
});

// atualizando tracking link
var default_url = $("#tracking_link").val();
$("[data-track]").on("keyup", function () {

    var url = $("#url_tracking");
    var params = $("#tracking_params");
    var url_id = $("#url_id").val();

    var source = $("#source");
    var aff_sub1 = $("#aff_sub1");
    var aff_sub2 = $("#aff_sub2");
    var aff_sub3 = $("#aff_sub3");
    var aff_sub4 = $("#aff_sub4");
    var aff_sub5 = $("#aff_sub5");
    var aff_click_id = $("#aff_click_id");
    var utm_source = $("#utm_source");
    var utm_medium = $("#utm_medium");
    var utm_campaign = $("#utm_campaign");
    var utm_term = $("#utm_term");
    var utm_content = $("#utm_content");

    source = (source.val() != "") ? "&source=" + source.val() : "";
    aff_sub1 = (aff_sub1.val() != "") ? "&aff_sub1=" + aff_sub1.val() : "";
    aff_sub2 = (aff_sub2.val() != "") ? "&aff_sub2=" + aff_sub2.val() : "";
    aff_sub3 = (aff_sub3.val() != "") ? "&aff_sub3=" + aff_sub3.val() : "";
    aff_sub4 = (aff_sub4.val() != "") ? "&aff_sub4=" + aff_sub4.val() : "";
    aff_sub5 = (aff_sub5.val() != "") ? "&aff_sub5=" + aff_sub5.val() : "";
    aff_click_id = (aff_click_id.val() != "") ? "&aff_click_id=" + aff_click_id.val() : "";
    utm_source = (utm_source.val() != "") ? "&utm_source=" + utm_source.val() : "";
    utm_medium = (utm_medium.val() != "") ? "&utm_medium=" + utm_medium.val() : "";
    utm_campaign = (utm_campaign.val() != "") ? "&utm_campaign=" + utm_campaign.val() : "";
    utm_term = (utm_term.val() != "") ? "&utm_term=" + utm_term.val() : "";
    utm_content = (utm_content.val() != "") ? "&utm_content=" + utm_content.val() : "";

    params.val(source + aff_sub1 + aff_sub2 + aff_sub3 + aff_sub4 + aff_sub5 + aff_click_id + utm_source + utm_medium + utm_campaign + utm_term + utm_content);
    urlTracking(url_id);
});

$("#url_id").on("change", function () {
    var url = $(this).val();
    urlTracking(url);
});

function urlTracking(url_id) {
    url_id = url_id.split("|");
    $("#url_preview").attr("href", url_id[1]);

    var url = $("#url_tracking");
    var params = $("#tracking_params").val();

    url_id = "&url_id=" + url_id[0];
    url.val(default_url + url_id + params);
}