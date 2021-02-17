<div class="container-fluid">
	<br>
		<div class="row ">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<div class="card">
					<div class="card__header">
						<div class="card__header-title text-light">Informação <strong>Aparelho</strong>
						</div>
					</div>
					<div class="card__main">
						<div class="pl-3 stretch-card">
							<div class="row">
								<div class="col-md-12">
									<form>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="exampleInputEmail1">Licença</label>
													<input type="email" class="form-control txtdevice" id="txtdevice" aria-describedby="emailHelp" readonly="readonly">
													<small id="emailHelp" class="form-text text-muted"></small>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md pb-2">
												<button type="button" class="btn btn-sm  form-control btn-success" id="btnregistrar" data-toggle="modal" data-target="#modalregistrar">Registrar</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4"></div>
			<br>
		</div>
</div>
<?php include_once APP_ROOT."includes/modal/registrar.php"; ?>


