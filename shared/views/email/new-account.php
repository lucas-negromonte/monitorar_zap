<?php $this->layout("_theme", array("title" => "Novo Afiliado :: " . CONF_SITE_NAME)); ?>
<h2>Uma nova conta de Afiliado foi criada</h2>
<p><?= label('name') ?>: <?= $name; ?></p>
<p><?=label('email')?>: <?= $email; ?></p>
<p><?=label('company')?>: (ID: <?= $affId; ?>) <?= $company; ?></p>
<p class="red"><em>É preciso liberar a conta para ele ter todos os acessos</em></p>