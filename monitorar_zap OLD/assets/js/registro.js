$(document).ready(function(){

	var imei = $.cookie("_hashimei");

	$(function(){
		load(1);
		//alert('1');
	}); 
	
	function load(){
		
		var page = "exibir";
		$.ajax({
			dataType: 'json',
			type: 'POST',
			data: {
				imei: imei,
				page: page
			},
			url: path+"controller/registroController.php",
			cache: false,
			beforeSend: function(){
				$(".txtdevice").val("Carregando...").show();
			}, 
			success: function(dado){
				if(dado.not === "2"){
					$(".txtdevice").val('Sem Licença').show();
					//alert('não');
				}else{
					$(".txtdevice").val(dado.licenca).show();
				}
				
			}, 
			complete: function(){
				//$(".txtdevice").val("").show();
			}
		});
	}
$('.btnsavelicenca').click(function(e){

	var licenca = $("#txtlicenca").val();
	var page = "inserir";

	if($("#txtlicenca").val() === ''){
		$("#msg").html('<div class="alert alert-warning text-center"><i class="far fa-times-circle"></i> Por favor, insira a licença</div>');
		$("#txtlicenca").focus();
		return false;
	}

	$.ajax({
		dataType: 'json',
		type: 'POST',
		data:{
			imei: imei,
			licenca: licenca,
			page: page
		}, 
		url: path+"controller/registroController.php",
		cache: false,
		beforeSend: function () {
			$(".loader2").html("<section class='l-section'><p class='t-loader'>Carregando...</p></section>").show();
		}, 
		success: function(dados){
			if(dados.registra == '1'){
				$("#msg").html('<div class="alert alert-success text-center"><i class="far fa-check-circle"></i> '+dados.msg+'</div>');
				input();
				load(1);
			}
			if(dados.uso == '1'){
				$("#msg").html('<div class="alert alert-danger text-center"><i class="far fa-times-circle"></i> '+dados.msg+'</div>');
			}
			if(dados.existe == '1'){
				$("#msg").html('<div class="alert alert-danger text-center "><i class="far fa-times-circle"></i> '+dados.msg+'</div>');
			}
			$(".loader2").html("").show();
		},
		complete: function(){
			$(".loader2").html("").show();
		}
	});

});
function input()
{
	$(':input','#formlicenca')
	.val('')
	/*.removeAttr('checked')*/
	.removeAttr('selected');
	setTimeout(function() {
		$('.modal').modal('hide');
		$("#msg").html('');
	}, 1000);
	
}
});	