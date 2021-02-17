$(document).ready(function(){

	function infoMsg(){
		var $maginfo = 'This is an info message.';
		$("#msgerror").html("<div class='info-msg'><i class='fa fa-infp-circle'></i> " + $maginfo + " </div>");
	}

	function infoSuccess($dados){

		if($dados == 'recover'){
			$msg = $("#msgerror").html("<div class='success-msg'><i class='fa fa-check-circle'></i> Senha alterada com sucesso.</div>");
		}else if($dados == 'email'){
			$msg = $("#msgerror").html("<div class='success-msg'><i class='fa fa-check-circle'></i> Email enviado.</div>");
		}
		return $msg;
	}

	function infoError(){
		var $magError = 'Senha ou Email inválido.';
		$("#msgerror").html("<div class='error-msg'><i class='fa fa-times-circle'></i> " + $magError + " </div>");
	}

	function loader(){
		$("#loader").html("<section class='l-section'><p class='t-loader'>Carregando...</p></section>").show();
	}

	function existEmail($dado){
		if($dado == 'noexist'){
			$msg = $("#msgerror").html("<div class='error-msg'><i class='fa fa-times-circle'></i> Email não encontrado</div");
		}else if($dado == 'exist'){
			$msg = $("#msgerror").html("<div class='error-msg'><i class='fa fa-times-circle'></i> Email de recupereção já foi enviado</div");
		}
		return $msg;
	}

	$('.message a').click(function(){
	   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
	});
	

	$(".login-form").on("submit", function(e){

			e.preventDefault();
			var txtusername = $("#txtusername").val();
			var txtpassword = $("#txtpassword").val();

			//var parametros = $(this).serialize();

		$.ajax({
			dataType:'json',
			type: 'POST',
			data: {
				   'txtusername': txtusername,
				   'txtpassword': txtpassword
					},
			url:path+'controller/loginController.php',
			cache: false,
			beforeSend: function(){
				loader();
			},
			success: function(dados){
				if(dados.success === '1'){
					$.cookie('_hashimei', dados.imei, {expires: 1, path: '/'});
					window.location.href = path+"?page=dashboard";
				}
				if(dados.error === '2'){
					infoError();
				}
			},
			complete: function(){
				$("#loader").html("");
			}, 
			error: function(){
				infoMsg();
			}
		});
	});

	$(".register-form").on("submit", function(e){

		e.preventDefault();

		var txtemail = $("#txtemailrecall").val();
		var page  	 = 'trocasenha';
		$.ajax({
			dataType: 'json',
			type: 'POST',
			data:{
				'email': txtemail,
				'page': page

			},
			url: path+'controller/recallController.php',
			cache: false,
			beforeSend: function(){
				loader();
			},
			success: function(dados){
				if(dados.Noexistemail === '1'){
					existEmail('noexist');
				}else if(dados.existemail === '2'){
					existEmail('exist');
				}else if(dados.success === '3'){
					infoSuccess('email');
				}
			},
			complete: function(){
				$("#loader").html("");
			},
			error: function(){
				infoMsg();
			}
		});
	});

	$('.torecover-form').on("submit", function(e){

		e.preventDefault();

		var password        = $('#txtpassword').val();
		var passwordConfirm = $('#txtconfirmpassword').val();
		var email           = $('#txtuseremail').val();
		var page            = $('#page').val();
		var token           = $('#txtget').val();

		if(passwordConfirm != password){
			$("#msgerror").html("<div class='error-msg'><i class='fa fa-times-circle'></i> As senhas não coincide</div>");
			return false;
		}else{
			var parametros = {'password': password, 'passwordConfirm': passwordConfirm, 'email': email, 'page': page, 'token': token};
		}

		$.ajax({
			dataType: 'json',
			type: 'POST',
			data: parametros,
			url: path+'controller/recallController.php',
			cache: false,
			beforeSend: function(){
				loader();
			},
			success: function(dados){
				if(dados.sucesso === '1'){
					infoSuccess('recover');
					setTimeout(function(){
						window.location.href = "?page=home";
					}, 3000);
				}if(dados.error === '2'){
					infoError();
				}
			},
			error: function(){
				$("#loader").html("");
			},
			complete: function(){
				$("#loader").html("");
			}
		});
	});
});

