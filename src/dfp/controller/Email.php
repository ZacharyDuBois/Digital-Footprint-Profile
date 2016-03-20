<?php
/**
 * File: Email.php
 * User: zacharydubois
 * Date: 2016-01-02
 * Time: 02:41
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

/**
 * Class Email
 *
 * Handles email for DFP.
 *
 * @package dfp
 */
class Email {
    private
        $config,
        $to,
        $html,
        $plain;

    /**
     * Email constructor.
     *
     * Just creates a config object.
     */
    public function __construct() {
        $this->config = new Config();
    }

    /**
     * Set the To Address.
     *
     * Validates and sets who the email should be sent to.
     *
     * @param string $email
     * @return bool
     */
    public function to($email) {
        if (Validate::email($email)) {
            $this->to = $email;

            return true;
        }

        return false;
    }

    /**
     * Set Content
     *
     * Sets the body of the message with both plain text and HTML versions.
     *
     * @param string $html
     * @param string $plain
     * @return true
     */
    public function body($html, $plain) {
        $this->html = $html;
        $this->plain = $plain;

        return true;
    }

    /**
     * Sends Email
     *
     * Sends email using the configued gateway.
     *
     * @return bool
     * @throws Exception
     */
    public function send() {
        switch ($this->config->get('email', 'gateway')) {
            case 'sendgrid':
                return $this->sendgrid();
            case 'smtp':
                throw new Exception("SMTP is deprecated.");
                //return $this->smtp();
            default:
                throw new Exception("Unknown email gateway.");
        }
    }

    /**
     * Sendgrid Gateway
     *
     * Sends using Sendgrid's API.
     *
     * @return bool
     * @throws \dfp\Exception
     */
    private function sendgrid() {
        $sendgrid = new \SendGrid($this->config->get('email', 'sendgrid_key'));
        $email = new \SendGrid\Email();
        $email
            ->addTo($this->to)
            ->setFrom($this->config->get('email', 'from_address'))
            ->setFromName($this->config->get('email', 'from_name'))
            ->setSubject($this->config->get('email', 'subject'))
            ->setText($this->plain)
            ->setHtml($this->html);

        try {
            $response = $sendgrid->send($email);
        } catch (\SendGrid\Exception $e) {
            throw new Exception($e);
        }

        if ($response->getBody['message'] === 'success') {
            return true;
        }

        return false;
    }

    /**
     * SMTP Gateway
     *
     * Sends using specified SMTP information in the config.
     *
     * @return bool
     * @throws \phpmailerException
     * @deprecated Since the school is not hosting this app anymore, platform decisions are left to me.
     */
    private function smtp() {
        $email = new \PHPMailer();
        $email->isSMTP();
        $email->Host = $this->config->get('email', 'host');
        $email->SMTPAuth = true;
        $email->Username = $this->config->get('email', 'user');
        $email->Password = $this->config->get('email', 'password');;
        $email->SMTPSecure = $this->config->get('email', 'secure');;
        $email->Port = $this->config->get('email', 'port');;

        $email->setFrom($this->config->get('email', 'from_address'), $this->config->get('email', 'from_name'));
        $email->addAddress($this->to);
        $email->isHTML(true);

        $email->Subject = $this->config->get('email', 'subject');
        $email->Body = $this->html;
        $email->AltBody = $this->plain;

        return $email->send();
    }
}