$(document).ready(function () {
    $(".btn-audio").click(function () {

        var id = $(this).attr("chaveDeBusca");
        var seq = $(this).attr("seq");
        var url = $(this).attr("data-url");
        var dados = "ajax=true&acao=buscar&id=" + id;
        console.log(url);
        $.ajax({
            url: url,
            dataType: 'json',
            data: dados,
            type: "POST",
            beforeSend: function () {
                $(".main-loading").fadeIn(200).css("display", "flex");
            },
            success: function (response) {
                if (response.html) {
                    $("#btn-audio-" + seq).hide();
                    $(".mostrar-audio-" + seq).html(response.html);
                }

                //Botao excluir
                $(".btn-enviar-audio").click(function () {

                    $('.btn-excluir-audio').attr('data-url', url);

                    // alterar mensagem
                    $('.modal-audio-msg').removeClass('alert alert-info').html('');

                    // voltar botão ao original 
                    $('.btn-excluir-audio').prop('disabled', false);

                    //Mudar o valor do id no botão do modal
                    $(".btn-excluir-audio").attr('idAudio', $(this).attr('idAudio'));

                    // copia html do audio do click 
                    $('.modal-audio-mostrar').html($('.audio' + $(this).attr('idAudio')).html());
                });

            },
            error: function () {
                $(".mostrar-audio-" + seq).html('Erro');
            }, complete: function () {
                //Remover Carregamento
                $(".main-loading").fadeOut(200);
            }
        });
    });



    $('.btn-excluir-audio').click(function () {
        var id = $(this).attr('idAudio');
        var dados = 'ajax=true&id=' + id + '&acao=excluir';
        var btn = $(this);
        console.log(dados);
        $.ajax({
            url: btn.attr("data-url"),
            dataType: 'json',
            data: dados,
            type: "POST",
            beforeSend: function () {
                $(".main-loading").fadeIn(200).css("display", "flex");
            },
            success: function (response) {
                if (response.delete == true) {

                    // $('.modal-audio-msg').html('excluido com sucesso').addClass('alert alert-success');
                    
                    $('.tr' + id).hide();
                    $('.rows').html($('.rows').html() - 1);
                    // $("#modalExcluirAudio").modal("hide"); //erro
                    $(".close").trigger('click');
                } else {
                    $('.modal-audio-msg').html(response.html).addClass('alert alert-info');
                }
            },
            error: function () {
                $(".modal-audio-msg").html('Erro');
            }, complete: function () {
                //Remover Carregamento
                $(".main-loading").fadeOut(200);
            }
        });
    });
});