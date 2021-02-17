<?php

namespace Source\Support;

use PHPMailer;
use phpmailerException;
use Source\Models\ErrorLog;

/**
 * Email Support
 * @package Source\Support
 */
class Email
{
    /** @var object */
    private $data;

    /** @var PHPMailer */
    private $mail;

    /** @var Message */
    private $message;

    /**
     * Email constructor
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->data = new \stdClass();
        $this->message = new Message();

        $this->mail->isSMTP();
        $this->mail->setLanguage(CONF_MAIL_OPTION_LANG);
        $this->mail->isHTML(CONF_MAIL_OPTION_HTML);
        $this->mail->SMTPAuth = CONF_MAIL_OPTION_AUTH;
        $this->mail->SMTPAutoTLS = false;
        // $this->mail->SMTPSecure = CONF_MAIL_OPTION_SECURE;
        $this->mail->CharSet = CONF_MAIL_OPTION_CHARSET;
        // $this->mail->SMTPDebug = true;

        /** auth */
        $this->mail->Host = CONF_MAIL_HOST;
        $this->mail->Port = CONF_MAIL_PORT;
        $this->mail->Username = CONF_MAIL_USER;
        $this->mail->Password = CONF_MAIL_PASS;
    }

    /**
     * add: monta os dados para o envio do email
     * @param string $subject
     * @param string $message
     * @param string $toEmail
     * @param string $toName
     * @return Email
     */
    public function add($subject, $message, $toEmail, $toName)
    {
        $this->data->subject = $subject;
        $this->data->message = $message;
        $this->data->toEmail = $toEmail;
        $this->data->toName = $toName;

        return $this;
    }

    public function attach($filePath, $fileName)
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    /**
     * @param string|null $fromEmail
     * @param string|null $fromName
     * @return boolean
     */
    public function send($fromEmail = null, $fromName = null)
    {
        if (empty($fromEmail)) {
            $mailSender = unserialize(CONF_MAIL_SENDER);
            $fromEmail = $mailSender["address"];
        }
        if (empty($fromName)) {
            $mailSender = unserialize(CONF_MAIL_SENDER);
            $fromName = $mailSender["name"];
        }

        if (empty($this->data)) {
            $this->message->error("Erro ao enviar, favor verifique os dados");
            return false;
        }

        if (!is_email($this->data->toEmail)) {
            $this->message->error("o e-mail destinatário não é válido");
            return false;
        }
        if (!is_email($fromEmail)) {
            $this->message->error("o e-mail remetente não é válido");
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->message);
            $this->mail->addAddress($this->data->toEmail, $this->data->toName);
            $this->mail->setFrom($fromEmail, $fromName);
            $this->mail->addBCC(CONF_ADM_EMAIL);

            if (!empty($this->data->attach)) {
                foreach ($this->data->attach as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            $this->mail->send();
            return true;
        } catch (phpmailerException $e) {
            $this->message->error($e->getMessage());
            $error = new ErrorLog();
            $error->saveErrorLog($e);
            return false;
        }
    }

    /**
     * @return \PHPMailer\PHPMailer\PHPMailer
     */
    public function mail()
    {
        return $this->mail;
    }

    /**
     * @return Message
     */
    public function message()
    {
        return $this->message;
    }
}
