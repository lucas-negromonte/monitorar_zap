<?php

namespace Source\Controllers;

use Source\Core\Controller;

/**
 * Home Controllers
 * @package Source\Controllers 
 */
class Error extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /** Página de Erros
     * @param array $data
     * @return void
     */
    public function error($data = null)
    {
        if (!empty($data['errorcode'])) {
            switch ($data['errorcode']) {
                case '404':
                    $descricao = msg('page_not_exist');
                    break;
                case '405':
                    $descricao = msg('page_not_implemented');
                    break;
                default:
                    $descricao = msg('page_not_load');
                    break;
            }
        }

        echo $this->view->render("error", array(
            "title" => "Ooops! ",
            "message" => "{$descricao}", //msg("not_found"),
            "noFilter" => true
        ));
    }

    /**
     * @param array $data
     * @return void
     */
    public function maintenance()
    {
        echo $this->view->render("error", array(
            "title" => msg('maintenance'),
            "message" => msg('maintenance_msg'),
            "noFilter" => true
        ));
    }
}
