<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">

    <title><?= (isset($title) ? $title . " :: " : "") ?>Monitorar Mobile</title>
    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.ico"); ?>" />
    <link type="text/css" href="<?= theme("/assets/style.css") ?>" rel="stylesheet">

</head>

<body>
    <div class="wrapper">
        <?= $this->section("content"); ?>
    </div>
    <div class="main-loading">
        <span class="spinner spinner-border text-primary" role="status">
            <span class="sr-only"><?= label("loading") ?>...</span>
        </span>
    </div>

    <script src="<?= theme("/assets/scripts.js"); ?>"></script>
    <?= $this->section("scripts"); ?>

    <div class="ajax_response"><?= flash_message() ?></div>

</body>

</html>