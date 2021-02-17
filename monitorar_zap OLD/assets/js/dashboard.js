$(document).ready(function(){

	function loader() {
		$(".loader").html("<section class='l-section'><p class='t-loader'><span></span></p></section>").show();
	}
	var page = 'card';
	var imei = $.cookie("_hashimei");
	$.ajax({
		type: 'get',
		dataType: 'json',
		data:{
			'txtimei': imei,	
			'page': page
		},
		url: path+'controller/dashboardController.php',
		cache:false,
		beforeSend: function(){
			loader();
		},
		success: function(dado){
			for(var i in dado){

				$(".subtitlemensagem").html(dado[i].mensagem);
				$(".subtitlecontato").html(dado[i].contato);
			}
		}, 
		error: function(){
			$("#loader").html("");
		},
		complete: function(){
			$("#loader").html("");
		}
	});

	var page = 'graph';
	var imei = $.cookie("_hashimei");
	$.ajax({
		type: 'get',
		dataType: 'json',
		data:{
			'txtimei': imei,	
			'page': page
		},
		url: path+'controller/dashboardController.php',
		cache:false,
		beforeSend: function(){
			loader();
		},
		success: function(dado){
			
			if(dado.retorno == 'sucesso'){

		        var datas  = [];
		        var msg  = [];

			   for (let x in dado.dataGraph){
 					datas.push(dado.dataGraph[x]);
 					msg.push(dado.dadoMsg[x]);
		        }

		        graph(msg,datas);
		      
			}else{
				$(".loader").html("<section><p>Problema para gerar o gráfico</p></section>").show();
			}	
		}, 
		error: function(){
			$(".loader").html("");
			alert('2')
		},
		complete: function(){
			$(".loader").html("");
		}
	});
});

function graph(msg, datas){
	var ctx = document.getElementById('canvas').getContext('2d');
	var chart = new Chart(ctx, {
	// The type of chart we want to create
	type: 'line', // also try bar or other graph types

	// The data for our dataset
	data: {
		labels: datas,
	    datasets: [{
	    	borderColor: 'rgba(7,94,84,1)',
	    	pointBackgroundColor: 'rgba(7,94,84,1)',
	    	pointHoverBackgroundColor: 'rgba(7,94,84,1)', 
		    backgroundColor: 'rgba(7,94,84,0.2)', 
		    borderWidth: 3,
	        fill: true,  
		    data: msg
		}],
	},
	// Configuration options
	options: {
		legend: {
			display: false
		},
		responsive: false,
		maintainAspectRatio: false,
		layout: {
			padding: 10,
		}
	}

});

}



