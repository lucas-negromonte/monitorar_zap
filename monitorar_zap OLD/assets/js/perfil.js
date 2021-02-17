$(document).ready(function () {

	function loader() {
		$(".loader").html("<section class='l-section'><p class='t-loader'><span></span></p></section>").show();
	}

	$(function(){
		load(1);
	});

	function load(){
		var imei = $.cookie('_hashimei');

		$.ajax({
			dataType: 'json',
			type: 'POST',
			data: {
				'txtimei': imei
			},
			url: path+'controller/perfilController.php',
			cache: false,
			beforeSend: function () {
				loader();
			},
			success: function (dado) {
				var count = Object.keys(dado).length;

				if (dado.error === '2') {
					console.log('2');
				}
				for (var i in dado) 
				{
					$("#txtdevice").val(dado[i].device);
					$("#txtimei").val(dado[i].imei);
					$("#txtvencimento").val(dado[i].vencimento);
					$("#txtemailaddress").val(dado[i].email);
					$("#txtpassword").val(dado[i].senha);
				}
			},
			error: function () {
				alert('error');
			},
			complete: function () {
				$(".loader").html("");
			}

		});

		$('#modalalteradados').on('show.bs.modal', function (e) {
			var email       = $("#txtemailaddress").val();
			var senhaatual  = $("#txtpassword").val();
			var imei        = $("#txtimei").val();
			$.ajax({
				type : 'post', 
				url  : path+'includes/modal/altera-dados.php', 
				data :  {
					'email'	  : email,
					'senhaatual' : senhaatual,
					'imei'	  : imei,
				}, 
				success : function(data){
					$('#txtemail').val(email);
					$('#txtsenhaatual').val(senhaatual);
					$('#txtimei2').val(imei);
				} 
			});
		});
	}

	$(document).ready(function(){
		$("#formeditardados").submit(function(){

			var senhanova     = $("#txtsenhanova").val();
			var senhaconfirma = $("#txtsenhaconfirma").val();
			var email         = $("#txtemail").val();

			var parametros = $(this).serialize();

			if(senhanova != senhaconfirma){
				$("#msgerro").html('<div class="alert alert-danger text-center"><i class="far fa-times-circle"></i> Senha não coencide</div>');
				return false;
			}
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: parametros, 
				url: path+'controller/perfilController.php',
				cache: false,
				beforeSend: function(){
					$("#loader").html("<section class='l-section'><p class='t-loader'>Alterando...</p></section>").show();
				},
				success: function(dados){
					if(dados.email === '2'){
						$("#msgerro").html('<div class="alert alert-danger text-center"><i class="far fa-times-circle"></i> Email inválido</div>');
					}if(dados.success === '1'){
						$("#msgerro").html('<div class="alert alert-success text-center"><i class="far fa-check-circle"></i> Senha alterada</div>');
						input();
						load(1);
					}

				},
				complete: function(){
					$("#loader").html('');
				}
			});
		});
	});

	function input()
	{
		$(':input','#formeditardados')
		.val('')
		/*.removeAttr('checked')*/
		.removeAttr('selected');
		setTimeout(function() {
			$('.modal').modal('hide');
			$("#msgerro").html('');
		}, 1000);
	}

});