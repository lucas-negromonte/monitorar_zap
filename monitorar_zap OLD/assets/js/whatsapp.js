function loader() {
	$("#loader").html("<section class='l-section'><p class='t-loader'><span></span></p></section>").show();
}

$(document).ready(function () {

	var datatable = {
		'searching': true,
		"lengthChange": false,
		"ordering": true,
		"paging": true,
		"autoWidth": true,
		"deferRender": true,
		/*'aLengthMenu': [
		[5, 10, 25, 50, -1],
		[5, 10, 25, 50, 'Tudo']
		],*/
		'iDisplayLength': 50,

		'language': {
			'sProcessing': 'Carregando...',
			//'sLengthMenu': 'Mostrar _MENU_ registros',
			'sZeroRecords': 'NO DATA',
			'sInfo': 'Mostrando _START_ de  _END_ a _TOTAL_ registros ',
			'sInfoEmpty': '',
			'sInfoFiltered': 'Mostrando _START_ de  _END_ a _TOTAL_ registros (filtered from  _MAX_ total entries) ',
			'sInfoPostFix': '',
			'sSearch': 'Pesquisar:',
			'searchPlaceholder': 'por contato',
			'sUrl': '',
			'oPaginate': {
				'sFirst': '1',
				'sPrevious': 'Anterior',
				'sNext': 'Próximo',
				'sLast': '2'
			}
		}
	};

	var imei = $.cookie('_hashimei');

	$.ajax({
		dataType: 'json',
		type: 'POST',
		data: {
			'txtimei': imei,
			'page': 1
		},
		url: path+'controller/whatsappController.php',
		cache: false,
		beforeSend: function () {
			loader();
		},
		success: function (dado) {
			var table = $('#tableWhatsapp').DataTable(datatable);
			var count = Object.keys(dado).length;
			var resul = "";
			if (dado.error === '2') {
				alert('2');
			}
			if (count > 0) {
				
				for(var i in dado) {
					resul = 
					'<tr data-id="'+dado[i].nome_contato+'" class="btnmessage2">'+
					'<td class="'+dado[i].nome_contato+'">' + dado[i].nome_contato + '</td>'+
					'<td><button type="button" id="btnmessage-'+dado[i].nome_contato+'" class="btn btn-sm btnbuttontable btnmessage"><i class="fa fa-envelope"></i></button></td>'+
					'</tr>';
					table.row.add($(resul)).draw();
				}

				$('#tableWhatsapp tbody').on('click', '.btnmessage2', function () {
					var table = $('#tableWhatsapp').DataTable({
						"retrieve": true
					});

					var nomeContato = $(this).data('id');
					//var nomeContato = $(this).parent().parent().data('id');

					var tr = $(this).closest('tr');
					var row = table.row(tr);

				if (row.child.isShown()) {

			        //A linha já está aberta- fecha ela
			        row.child.hide();
			        tr.removeClass('shown');
			        $(".heightDiv").parent().parent().hide('slow');
			        $(".heightDiv").parent().parent().removeClass('active');
			  	}
			    else {
			        // abre a linha
			        row.child(format(row.child, nomeContato));
			        tr.addClass('shown');
			        $(".heightDiv").parent().parent().hide('slow');
			        $(".heightDiv").parent().parent().addClass('active');

			  		}
				});
			}
		},
		error: function () {
			alert('error');
		},
		complete: function (dado) {
			$("#loader").html("");
		}
	});
});

function format (callback, nomeContato) {
	var imei = $.cookie('_hashimei');
	var div = $('<div/>');

	$.ajax({
		dataType: 'json',
		type: 'POST',
		data: {
			'txtimei': imei,
			'page': 0,
			'txtnomecontato': nomeContato
		},
		url: path+'controller/whatsappController.php',
		cache: false,
		beforeSend: function () {
			loader();
		},
		success: function (dado) {

		},
		error: function () {
			alert('error');
		},
		complete: function (dado) {
			$("#loader").html("");
			var count = Object.keys(dado).length;

			if (dado.error === '2') {
				alert('2');
			}
			if (count > 0) {
				var data = JSON.parse(dado.responseText);
				var thead = '';
				var tbody = "";
				tbody += '<div class="heightDiv" id="styleScrollbar">';

				$.each(data, function (i, d) {

					if(d.tipo === '1'){
						  tbody += '<div class="row justify-content-end chat-bubble" >';
						  tbody += '<div class="balao-2">';
						  tbody += '<p class="para-2">';
						  tbody += d.mensagem+'<br>';
						  tbody += '<small class="datamessage">'+d.data+'</small>';
						  tbody += '</p>';
						  tbody += '</div>';
						  tbody += '</div>';

						}if(d.tipo === '0'){
							tbody += '<div class="row justify-content-start chat-bubble">';
							tbody += '<div class="balao-1">';
							tbody += '<p class="para-1">';
							tbody += d.mensagem+'<br>';
							tbody += '<small class="datamessage">'+d.data+'</small>';
							tbody += '</p>';
							tbody += '</div>';
							tbody += '</div>';
						}
					});

				tbody += '</div>';
				//callback($("<div id='table2' class='table table-fixed table-striped' cellspacing='0' width='100%' >" + tbody + "</div>")).show();
				callback($(tbody)).show();

			}else{
				tbody += '<tr class="">';
				tbody += '<td class="text-center">Sem dados</td>';
				tbody += '<td></td>';
				tbody += '</tr>';
				callback($("<table id='table2' class='table table-fixed table-striped' cellspacing='0' width='100%'>" + tbody + "</table>")).show();
			}
		}

	});
	return div;
};
