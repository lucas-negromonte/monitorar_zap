<?php $this->layout('_theme', array('title' => $title)) ?>
<div class="row mb-2 mb-xl-4">
    <div class="col-auto d-sm-block">
        <h1><?= $title ?></h1>
        <!-- <small>Dash > Home</small> -->
    </div>
</div>

<div class="alert alert-warning alert-dismissible" role="alert">
    <div class="alert-icon">
        <i class="fas fa-frown"></i>
    </div>
    <div class="alert-message">
        <strong><?= $message ?></strong>
    </div>
</div>

<?php $this->start("scripts") ?>
<?php /*$this->insert("includes/datepicker");*/ ?>
<?php $this->stop() ?>