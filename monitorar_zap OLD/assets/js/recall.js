$(window).ready(function(){

	$(function(){
		load(1);
	});

	function load()
	{
		var token = $("#txtget").val();
		var page = 'changepassword';
	if(token != ''){
	$.ajax({
			dataType:'json',
			type: 'POST',
			data: { 'token': token,
					'page': page
			 },
			url:path+'controller/recallController.php',
			cache: false,
			success: function(dados){
				if(dados.valido === '3'){
					$("#txtuseremail").val(dados.email);
				}else if(dados.invalido === '4'){
					$(".login-page").hide();
					$(".container-fluid").html(
						'<p class="text-center text-white linkfrase"><i class="fas fa-info-circle"></i> Ops, parace que seu Token é inválido. Por favor, gere um novo<br><br><a id="linkpassword" href="http://zap.monitorar.info/">Voltar</a></p>');
				}else if(dados.expired === '6'){
					$(".container-fluid").html(
						'<p class="text-center text-white linkfrase"><i class="fas fa-info-circle"></i> Ops, parace que o tempo para troca da senha expirou. Por favor, gere um novo<br><br><a id="linkpassword" href="http://zap.monitorar.info/">Voltar</a></p>');
				}else if(dados.Noexist === '5'){
					$(".login-page").hide();
					//window.location.href = path+"?page=home";
				}
			}
		});
	}else{
		window.location.href = path+"?page=home";
	}
}
});