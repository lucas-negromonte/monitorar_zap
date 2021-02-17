<div class="container-b page-content">
	<div class="container-fluid">
		<br>
		<div class="main__cards">
			<div class="row">
				<div class="col-md-6">
					<div class="card">
						<div class="loader"></div>
						<div class="card__header">
							<div class="card__header-title text-light">Informação <strong>Aparelho</strong>
							</div>
						</div>
						<div class="card__main">
							<div class="pl-3 stretch-card">
								<div class="row">
									<div class="col-md-12">
										<form>
											<div class="form-group">
												<label for="exampleInputEmail1">Device</label>
												<input type="text" class="form-control" id="txtdevice" aria-describedby="emailHelp" placeholder="" readonly="readonly">
												<small id="emailHelp" class="form-text text-muted"></small>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="exampleInputPassword1">Imei</label>
														<input type="text" class="form-control" id="txtimei" readonly="readonly">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="exampleInputPassword1">Vencimento</label>
														<input type="text" class="form-control" id="txtvencimento" readonly="readonly">
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="loader"></div>
						<div class="card__header">
							<div class="card__header-title text-light"><strong>Informação </strong>Perfil</div>
						</div>
						<div class="card__main">
							<div class="pl-3 stretch-card">
								<div class="row">
									<div class="col-md-12">
										<form>
											<div class="form-group">
												<label for="exampleInputEmail1">Email address</label>
												<input type="email" class="form-control" id="txtemailaddress" aria-describedby="emailHelp" readonly="readonly">
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="exampleInputPassword1">Password</label>
														<input type="password" class="form-control" id="txtpassword" readonly="readonly">
													</div>
												</div>
												<div class="col-md-6  mb-2">
													<label for="exampleInputPassword1">&nbsp;</label>
													<button type="button" class="btnalterar form-control btn-success" data-toggle="modal" data-target="#modalalteradados">Alterar Dados</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
	</div>
</div>
<?php include_once APP_ROOT."includes/modal/altera-dados.php"; ?>
<script type="text/javascript" src="<?=BASE_URL?>assets/js/perfil.js?v=<?=$versao?>"></script>