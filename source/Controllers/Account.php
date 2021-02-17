<?php

namespace Source\Controllers;

use Source\Core\Controller;
use Source\Models\Account as ModelsAccount;


/**
 * Account Controller
 * @package Source\Controllers
 */
class Account extends Controller
{
    /**
     * Transaction Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Dados do usuário logado --- usando apenas update de idioma no topo
     * @param array|null $data
     * @return void
     */
    public function profile($data = null)
    {
        // atualizando o idioma na seção do Topo
        if (!empty($data["update"]) && !empty($data["section"])) {
            $user = new ModelsAccount();
            $user = $user->findById($this->user->id);
            $user->language = $data["language"];
            

            if (!$user->save()) {
                $json["message"] = $this->message->render();
                echo json_encode($json);
                return;
            }

            setcookie("user_language", $user->language, time() + 60 * 60 * 24 * 30, "/");

            if ($user->language != $this->user->language) {
                echo json_encode(array("reload" => true));
            } else {
                // $json["message"] = $this->message->success("coloque uma mensagem aqui")->render();
                // echo json_encode($json);
            }
            return;
        }
        
        return;
    }
}
