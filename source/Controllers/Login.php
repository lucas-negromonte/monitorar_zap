<?php

namespace Source\Controllers;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\TranslationLabel;
use Source\Models\TranslationMsg;

/**
 * Home Controllers
 * @package Source\Controllers
 */
class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function home()
    {
        echo $this->view->render("home", array(
            "title" => "Home"
        ));
    }

    /**
     * Entrar no Sistema
     * @param array|null $data
     * @return void
     */
    public function login($data = null)
    { 
        if (!empty($data["csrf"])) {
            $data = filter_array($data);
 
            if (!check_csrf($data)) {
                $json["message"] = $this->message->error(msg("csrf"))->render();
                echo json_encode($json);
                exit; 
            }

            if (empty($data["email"]) || empty($data["password"])) {
                $json["message"] = $this->message->warning(msg("login_fail"))->render();
                echo json_encode($json);
                exit;
            }

            $save = (!empty($data["remember-me"]) ? true : false);
            $auth = new Auth();

            $password = trim(base64_encode($data["password"]));
            $login = $auth->login($data["email"], $password, $save);

            if (!$login) {
                $json["message"] = $auth->message()->render();
                echo json_encode($json);
                exit;
            } 

            if (!empty($data["redir"])) {
                $url = $data["redir"] . (isset($data["p1"]) ? '/' . $data["p1"] : '') . (isset($data["p2"]) ? '/' . $data["p2"] : '') . (isset($data["p3"]) ? '/' . $data["p3"] : '');
                $json["redirect"] = url($url);
            } else {
                $json["redirect"] = url("/home/");
            }
            echo json_encode($json);
            exit;
        }
        
        $redir = (isset($data["redir"]) ? $data["redir"] : '') . (isset($data["p1"]) ? '/' . $data["p1"] : '') . (isset($data["p2"]) ? '/' . $data["p2"] : '') . (isset($data["p3"]) ? '/' . $data["p3"] : '');

        echo $this->view->render("auth/login", array(
            "title" => "Login",
            "redir" => $redir,
            "cookie" => filter_input(INPUT_COOKIE, "authLogin")
        )); 
    }


    /**
     * @param array|null $data
     * @return void
     */
    public function forget($data = null)
    {

        // var_dump($data);

        if (!empty($data["csrf"])) {
            if (!check_csrf($data)) {
                $json["message"] = $this->message->error(msg("csrf"))->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["email"])) {
                $json["message"] = $this->message->warning(msg('reset_password'))->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            if ($auth->forget($data["email"])) {
                $json["message"] = $this->message->success(msg('access_your_emai'))->render();
            } else {
                $json["message"] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }

        echo $this->view->render("auth/forget", array(
            "title" => label("forgot_pass")
        ));
    }


     /**
     * @param array|null $data
     * @return void
     */
    public function reset($data = null)
    {
        if (!empty($data["csrf"])) {
            if (!check_csrf($data)) {
                $json["message"] = $this->message->error(msg("csrf"))->render();
                echo json_encode($json);
                return;
            }

            // var_dump($data);

            if (empty($data["password"]) || empty($data["confirm_pass"])) {
                $json["message"] = $this->message->info(msg("new_password"))->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["code"])) {
                $json["message"] = $this->message->error(msg("invalid_code"))->render();
                echo json_encode($json);
                return;
            }
           

            $data["code"] = base64_decode($data["code"]);
          

            $urlCode = explode("|*|", $data["code"]);

            if (empty($urlCode[0]) || empty($urlCode[1])) {
                $json["message"] = $this->message->error(msg("invalid_code"))->render();
                echo json_encode($json);
                return;
            }

            $email = $urlCode[0];
            $code = $urlCode[1];
            
            $auth = new Auth();
            if ($auth->reset($email, $code, $data["password"], $data["confirm_pass"])) {
                $json["message"] = $this->message->success(msg("new_pass_done"))->flash();
                $json["redirect"] = url("/login/");
            } else {
                $json["message"] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }

        $code = !empty($data["code"]) ? $data["code"] : "";


        echo $this->view->render("auth/reset", array(
            "title" => label("reset_password"),
            "code" => $code
        ));
    }


    /**
     *  Sair do Sistema
     * @return void
     */
    public function logout()
    {
        $this->message->info(msg("logout"))->flash();
        Auth::logout();
        redirect("/login/");
    }
}
