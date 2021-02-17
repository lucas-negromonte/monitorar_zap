$(function () {
    var ajaxResponseBaseTime = 3;
 
    //ajax form
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        var time = 200;
        var form = $(this);
        var load = $(".main-loading");
        var flashClass = "ajax_response";
        var flash = $("." + flashClass);
        // var dados = $(this).serialize();

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(time).css("display", "flex");
            },
            success: function (response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
                //reload
                if (response.reload) {
                    window.location.reload();
                } else {
                    load.fadeOut(time);
                }

                // message
                if (response.message) {
                    ajaxMessage(response.message, 5);
                } else {
                    flash.fadeOut(time);
                }

                // mostrar html na class 
                if (response.class && response.html) {
                    // $('.'+response.classRemove).html('');
                     $('.'+response.class).html(response.html);
                } 

                if (response.close_modal) {
                    $('.modal').modal('hide');
                }
            },
            error:function(){
                // load.fadeIn(time).css("display", "none");
                alert('Houve um erro ');
                // console.log(url);
            },
            complete: function () {
                load.fadeOut(time);
                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            }
        });
    });

    $("[data-update]").on("click", function () {
        exec($(this));
    });
    
    $("[data-change]").on("change", function () {
        exec($(this), true);
    });

    function exec(clicked, value = false) {
        if (clicked.is(":checked")) {
            clicked.data("status", "active");
        } else {
            clicked.data("status", "inactive");
        }

        var dataset = clicked.data();
        if (value) {
            dataset = Object.assign({ value: clicked.val() }, dataset);
        }

        var load = $(".main-loading");
        load.fadeIn(200).css("display", "flex");

        $.post(clicked.data("url"), dataset, function (response) {
            if (response.reload) {
                window.location.reload();
            } else {
                if (response.message) {
                    ajaxMessage(response.message, 5);
                }
                load.fadeOut(200);
            }
        }, "json");

        // load.fadeOut(200);
    }
    
    // atualizando o timezone do sidebar
    $("[data-uptimezone]").on("change", function () {
        exec($(this), true);
    });

    $("[data-remove]").on("click", function (e) {
        var clicked = $(this);
        var dataset = clicked.data();

        var load = $(".main-loading");
        load.fadeIn(200).css("display", "flex");

        $.post(clicked.data("remove"), dataset, function (response) {
            if (response.reload) {
                window.location.reload();
            } else if (response.redirect) {
                window.location.href = response.redirect;
            } else {
                if (response.message) {
                    ajaxMessage(response.message, 5);
                }
                load.fadeOut(200);
            }
        }, "json")
            .fail(function () {
                load.fadeOut(200);
            });
    });

    // atualizar dados no modal
    $("[data-target]").on("click", function (e) {
        e.preventDefault();
        var clicked = $(this);

        if (clicked.data("target") == "#modal-remove") {
            $("#confirm_remove").attr("data-remove", clicked.data("url"));
        }
    });


    // carregar conteudo|ação do click
    $("[data-load]").on("click", function (e) {
        e.preventDefault();

        $(".main-loading").fadeIn(200).css("display", "flex");

        if ($(this).is("button")) {
            $("form[data-formfilter]").submit();
            return;
        }
        
        window.location.href = $(this).attr('href');
    });

    $("[data-load-report]").on("click", function (e) {
        e.preventDefault();
        var report = $("#savedReport").val();
        if (report != "") {
            $(".main-loading").fadeIn(200).css("display", "flex");
            window.location.href = report;
        }
    });

    // AJAX RESPONSE
    function ajaxMessage(message, time) {
        var ajaxMessage = $(message);

        ajaxMessage.append("<div class='message_time'></div>");
        ajaxMessage.find(".message_time").animate({ "width": "100%" }, time * 1000, function () {
            $(this).parents(".message").fadeOut(200);
        });

        $(".ajax_response").append(ajaxMessage);
        ajaxMessage.effect("bounce");
    }

    // AJAX RESPONSE MONITOR
    $(".ajax_response .message").each(function (e, m) {
        ajaxMessage(m, ajaxResponseBaseTime += 2);
    });

    // AJAX MESSAGE CLOSE ON CLICK
    $(".ajax_response").on("click", ".message", function (e) {
        $(this).fadeOut("slow");
    });


    $('.btn-pdf').click(function () {
        var btn = $(this);
		var htmlPag = $('.table').html();
		var dados = 'htmlPag=' + htmlPag;
		$.ajax({
			url: btn.attr("data-url"),
			dataType: 'json',
			data: dados,
			type: "POST",
			complete: function () { 
				window.open('pdf/pdf.php');
			}
		});
    });
    
});