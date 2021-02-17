<?php $this->layout("_theme", array("title" => str_replace('{site}', CONF_SITE_NAME, msg('retrieve_password_access')))); ?>

<h2><?= label('lost_your_password') ?> <?= $first_name; ?>?</h2>
<p> <?= str_replace('{site}', CONF_SITE_NAME, msg('password_recovery')) ?></p>
<p><a title="<?= label('reset_password') ?>" href="<?= $forget_link ?>"><?= msg('created_new_pass') ?></a></p>
<p style="color:red;"><b><?= label('important') ?>:</b> <?= msg('not_request') ?></p>