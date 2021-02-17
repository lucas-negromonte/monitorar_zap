<?php

namespace Source\Core;

use Source\Core\View;
use Source\Models\Auth;
use Source\Models\User;
use Source\Support\Message;

class Controller
{
    /** @var User */
    protected $user;

    /** @var View */
    protected $view;

    /** @var Message */
    protected $message;

    /** @var array = armazena a rota atual explodida */
    protected $route;

    /** @var string = armazena a rota atual com query_string se houver */
    protected $url;

    /** @var string  = armazena tudo que houver na URL após o {?} */
    protected $queryString;

    /**
     * @param string $viewPath 
     * @return void
     */
    public function __construct($viewPath = null)
    {

        $viewPath = (empty($viewPath) ? CONF_VIEW_PATH . "/" . CONF_VIEW_THEME : $viewPath);

        $this->queryString = explode("?", $_SERVER["REQUEST_URI"]);
        $this->queryString = !empty($this->queryString[1]) ? "?" . $this->queryString[1] : "";

        $this->url = $_GET["route"] . $this->queryString;

        $this->route = route($_GET["route"]);

        $this->group = $this->route[0];
        // $route = "/" . implode("/", $this->route) . "/";

        $this->message = new Message();



        $this->user = Auth::user();

        //Convert filtro via get para objeto, filtro via query string
        $this->filter = str_replace('?', '', $this->queryString);
        parse_str($this->filter, $this->filter);
        $this->filter = (object)(!empty($this->filter) ? $this->filter : null);

        $group = $this->group;

        if (
            $group != "login"
            && $group != "register"
            && $group != "confirm"
            && $group != "forget"
            && $group != "recuperar"
            && $group != "reset"
            && $group != "logout"
            && $group != "faq"
        ) {
            // se o usuario não estiver logado redireciona ele para Login
            if (!$this->user) {
                $this->message->warning(msg("login"))->flash();
                $redir = $this->url;
                redirect("login" . $redir);
            }
        }

        // Redireciona usuario para home se estiver logado
        if ($group == "login") {
            $this->user = session()->user;
            if ($this->user) {
                $session = new Session();
                $session->clearSession('flash');
                $redirect = str_replace('/login/', '', $this->url);
                $redirect = (!empty($redirect) ? $redirect : '/home/');
                redirect($redirect);
            }
        }

        // Verifica licença vencida - se usuario estiver logado 
        if ($this->user) {
            $uso = session()->user->uso_lic;
            $data_vencimento = session()->user->vencimento;

            if (
                $group != "login"
                && $group != "register"
                && $group != "confirm"
                && $group != "forget"
                && $group != "recuperar"
                && $group != "reset"
                && $group != "logout"
                && $group != "faq"
                && $group != "account"

                
            ) {

                if (!stristr($_SERVER["REQUEST_URI"], 'registro')) {
                    if (strtotime(date('Y-m-d')) > strtotime($data_vencimento)) {
                        redirect("/configuracao/registro/1/");
                    } elseif ($uso == 0) {
                        redirect("/configuracao/registro/0/1/");
                    } elseif ($this->group == 'registro') {
                        redirect("/configuracao/registro/");
                    }
                }
            }
        }

        $this->view = new View($viewPath);
    }
}
