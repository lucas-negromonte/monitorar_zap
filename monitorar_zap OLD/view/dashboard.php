<div class="container-fluid">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="main-overview">
				<div class="overviewCard">
					<div class="overviewCard-icon overviewCard-icon--document">
						<i class="fa fa-comments"></i>
					</div>
					<div class="overviewCard-description">
						<h3 class="overviewCard-title text-dark">Total <strong>Mensagens</strong></h3>
						<p class="overviewCard-subtitle subtitlemensagem">Carregando...</p>
					</div>
				</div>
				<div class="overviewCard">
					<div class="overviewCard-icon overviewCard-icon--calendar">
						<i class="fa fa-id-card"></i>
					</div>
					<div class="overviewCard-description">
						<h3 class="overviewCard-title text-dark">Total <strong>Contatos</strong></h3>
						<p class="overviewCard-subtitle subtitlecontato">Carregando...</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="main__cards">
		<div class="row">
			<div class="col-md-12">
				<div class="card ">
					<div class="loader"></div>
					<div class="card__header">
						<div class="card__header-title text-light">Mensagens nos últimos 7 dias</div>
					</div>
					<div class="card__main ">
						<div style="width:100%;">
							<div class="pl-2 dashboardgraph">
								<canvas id="canvas" height="400" width="1200"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
</div>
<script type="text/javascript" src="<?=BASE_URL?>assets/js/dashboard.js?v=<?=$versao?>"></script>