$(document).ready(function () {
    var time = 10;//5 segundo
    var s = time;
    var status = $('#status').val(); // 1=ativado  -   0=desativado

    //Verifica se escuta ambiente ja alterou o status!
    setInterval(function () {

        var tempo_exedido = $('#tempo_exedido').val();
        if (tempo_exedido > 0) {
            //não fazer nada!
        } else {
            time++;
            var seq = parseInt($('#form-tarefa').attr("data-sequencia")) + 1;
            $('#form-tarefa').attr("data-sequencia", seq);

            if (status  >0 && time > s) {
                var url = $('#form-tarefa').attr("action");
                var dados = "ajax=true&acao=buscar&seq=" + seq;
                console.log(dados);
                $.ajax({
                    url: url,
                    dataType: 'json',
                    data: dados,
                    type: "POST",
                    beforeSend: function () {
                        $(".main-loading").fadeIn(200).css("display", "flex");
                    },
                    success: function (response) {

                        // message
                        if (response.message) {
                            ajaxMessage(response.message, 5);
                        }

                        //redirect
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                        //reload
                        if (response.reload) {
                            window.location.reload();
                        }

                    },
                    error: function () {
                        console.log('errooooo');
                    }, complete: function () {
                        //Remover Carregamento
                        $(".main-loading").fadeOut(200);
                    }
                });
                time = 0;
            }
        }
    }, 1000);

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

});