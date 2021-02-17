<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Core\Session;
use Source\Core\View;
use Source\Support\Email;

/**
 * Auth Model
 * @package Source\Models
 */
class Auth extends Model
{
    /** @var string */
    protected $entity;

    /** @var string = Main Entity ID */
    protected $fieldId;

    /** @var array */
    protected $protected;

    /** @var array */
    protected $required;

    /** @var string */
    protected $class;

    /**
     * Auth Constructor
     */
    public function __construct()
    {
        $this->entity = "account";
        $this->fieldId = "id";
        $this->protected = array("id");
        $this->required = array("email", "senha");
        $this->class = __CLASS__;

        parent::__construct($this->entity, $this->fieldId, $this->protected, $this->required, $this->class);
    }


    /**
     * @return object|null
     */
    public static function user()
    {
        $session = new Session();
        (!empty($_COOKIE[CONF_COOKIE_AUTH]) ? $session->set(CONF_SESSION_AUTH, $_COOKIE[CONF_COOKIE_AUTH]) : '');

        if (!$session->has(CONF_SESSION_AUTH)) {
            return null;
        }

        $user = new Account();
        $userActive = $user->find("id = :id", "id={$session->auth}")->fetch();

        if ($userActive) {
            $user = (array) $userActive->data();
            $session->set(CONF_SESSION_USER, $user);
            return (object) $user;
        }
        return null;
        // return (new Account())->findById($session->auth);
    }


    /**
     * @return void
     */
    public static function logout()
    {
        $session = new Session();
        $session->clearSession(CONF_SESSION_AUTH);
        $session->clearSession(CONF_SESSION_USER);
        setCookie(CONF_COOKIE_AUTH, null, time() - 1, "/");
        // setCookie('user_language', null, time() - 1, "/");
    }

    /**
     * @param [type] $user
     * @param [type] $password
     * @param boolean $save
     * @return void
     */
    public function login($user, $password, $save = false)
    {
        $objUser = new Account();
        $user = $objUser->find('email=:email',"email={$user}")->fetch();


       

        if (empty($user->email)) {
            $this->message->error(msg('email_not'));
            return false;
        }

        // var_dump($password,$user->senha);
        // exit;
        if ($password != $user->senha) {
            $this->message->error(msg('login_fail'));
            return false;
        }

        // if ($user->ativo == 0) {
        //     $this->message->error(msg("inactive"));
        //     return false;
        // }


        // // criando os arquivos de tradução
        // $label = new TranslationLabel();
        // $label->all();

        // $message = new TranslationMsg();
        // $message->all();


        // Setting login
        $session = new Session();
        $fieldId = $this->fieldId;

        $session->set(CONF_SESSION_AUTH, $user->$fieldId);


        $time = 3600 * 24 * 30 * 12; // 12 meses
        if ($save) {
            setCookie(CONF_COOKIE_AUTH, $user->$fieldId, time() + $time, "/");
        } else {
            setCookie(CONF_COOKIE_AUTH, null, time() - $time, "/");
        }

        setcookie("user_language", $user->language, time() + 60 * 60 * 24 * 30, "/");

        // $this->message->success("Login efetuado com sucesso")->flash();
        return true;
    }


    /**
     * @param string $email
     * @return bool
     */
    public function forget($email)
    {
        $objUser = new Account();
        $user =   $objUser->find("email = :email", "email={$email}")->fetch();

        if (!$user) {
            $this->message->warning(msg('email_not'));
            return false;
        }

        $user->forget = md5(uniqid(rand(), true));
        $user->save();

        $view = new View(__DIR__ . "/../../shared/views/email");
        $message = $view->render("forget", array(
            "first_name" => $user->nome,
            "forget_link" => url("/recuperar/".base64_encode("{$user->email}|*|{$user->forget}"))
        ));


        $email = new Email();
        $email->add(
            str_replace('{site}', CONF_SITE_NAME, msg('retrieve_password_access')),
            $message,
            $user->email,
            "{$user->nome} "
        )->send();

        return true;
    }

    /**
     * @param string $email
     * @param string $code
     * @param string $password
     * @param string $passwordRe
     * @return bool
     */
    public function reset($email, $code, $password, $passwordRe)
    {

        // $email = base64_decode($email);

        // var_dump($email);
        // exit;

        $objUser = new Account();
        $user =   $objUser->find("email = :email", "email={$email}")->fetch();

        if (!$user) {
            $this->message->warning(msg('email_recover_not'));
            return false;
        }

        if ($user->forget != $code) {
            $this->message->error(msg('invalid_code'));
            return false;
        }

        if (strlen($password) < CONF_PASS_MIN_LEN || strlen($password) > CONF_PASS_MAX_LEN) {
            $min = CONF_PASS_MIN_LEN;
            $max = CONF_PASS_MAX_LEN;
            $this->message->info(str_replace(array("{minlen}", "{maxlen}"), array($min, $max), msg("password_len")));
            return false;
        }

        // var_dump($password,$passwordRe);
        if ($password != $passwordRe) {
            $this->message->warning(msg('pass_not_match'));
            return false;
        }

        $user->senha = base64_encode($password);
        $user->forget = null;
        $user->save();
        return true;
    }
}
