<?php

include_once '../includes/config.php';
//session_start();

class LogoutController
{
  public static function getLogout()
  {
      // Se o usuário estiver logado, exclua a sessão vars para desconectá-los
    if (isset($_COOKIE['_hashimei'])) {  
    // Exclui a sessão vars limpando o array $ _SESSION
      $_COOKIE = array();

    // Exclua o cookie da sessão definindo sua expiração para uma hora atrás (3600)
      if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600);
      }

    // Exclua os cookies de ID de usuário e nome de usuário definindo suas expirações para uma hora atrás (3600)
      setcookie($_SESSION['_hashimei'], '', time() - 3600);

      setcookie('_hashimei', '', time() - 3600, '/');

    }

    //DESTROI AS SESSÕES
    unset($_COOKIE['_hashimei']);
    //unset($_SESSION['error_login']);

    // Destrua a sessão
    session_destroy();

    header("Location:../?page=home");

  }
}
$logout = new LogoutController();
$logout->getLogout();
