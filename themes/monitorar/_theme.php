<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">

    <title><?= (isset($title) ? $title . " :: " : "") ?>Monitorar Mobile </title>

    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.ico"); ?>" />
    <link type="text/css" href="<?= theme("/assets/style.css?".date('YmdHis')) ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <?= $this->section("styles"); ?>


</head>

<body>
    <div class="wrapper">

        <?= $this->insert("includes/sidebar"); ?>

        <div class="main">

            <?= $this->insert("includes/top"); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <!-- Inicio da Seção content -->
                    <?= $this->section("content"); ?>

                </div>
            </main>

            <!-- start footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-left">
                            <?= $title ?>
                        </div>
                        <div class="col-6 text-right">
                            <p class="mb-0">
                                &copy; <?= date('Y') ?> • <span class="text-muted">Monitorar Mobile</span>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end footer -->

        </div>
    </div>

    <div class="main-loading">
        <span class="spinner spinner-border text-primary" role="status">
            <span class="sr-only"><?= label("loading") ?>...</span>
        </span>
    </div> 



    <script src="<?= theme("/assets/scripts.js?".date('YmdHis')) ?>"></script>
    <?php include('includes/filter.php');    ?>

    <?= $this->section('scripts') ?>
    
    <div class="ajax_response"><?= flash_message() ?></div>

</body>

</html>