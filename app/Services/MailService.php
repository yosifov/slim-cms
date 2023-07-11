<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    /**
     * The PHP Mailer instance
     *
     * @var PHPMailer
     */
    private PHPMailer $mailer;

    /**
     * The body parameters
     *
     * @var array
     */
    private array $body;

    public function __construct(array $body)
    {
        $this->mailer = new PHPMailer(true);
        $this->body   = $body;

        $this->setConfig();
        $this->setRecipients();
        $this->setContent();
    }

    /**
     * Sets main configuration
     *
     * @return void
     */
    private function setConfig(): void
    {
        $this->mailer->isSMTP();
        $this->mailer->Host       = $_ENV['MAIL_HOST'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $_ENV['MAIL_USERNAME'];
        $this->mailer->Password   = $_ENV['MAIL_PASSWORD'];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = $_ENV['MAIL_PORT'];
        $this->mailer->Encoding   = $_ENV['MAIL_ENCODING'];
        $this->mailer->CharSet    = $_ENV['MAIL_CHARSET'];
    }

    /**
     * Sets from, reply and admin emails
     *
     * @return void
     */
    private function setRecipients(): void
    {
        $replyAddress = array_get($this->body, 'email', $_ENV['MAIL_FROM_ADDRESS']);
        $replyName    = array_get($this->body, 'name', $_ENV['MAIL_FROM_NAME']);

        $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $this->mailer->addAddress($_ENV['MAIL_TO_ADDRESS'], $_ENV['MAIL_TO_NAME']);
        $this->mailer->addReplyTo($replyAddress, $replyName);
    }

    /**
     * Sets the content of the email
     *
     * @param bool $isHtml  Whether to use HTML or not
     * @param bool $useBodyContent  Use the form body or provided content
     * @param string $subject   The email subject
     * @param string $body  The email body
     * @return void
     */
    public function setContent(
        bool $isHtml = true,
        bool $useBodyContent = true,
        string $subject = '',
        string $body = ''
    ): void {
        $this->mailer->isHTML($isHtml);
        $this->mailer->Subject = $useBodyContent ? $this->body['subject'] : $subject;
        $this->mailer->Body    = $useBodyContent ? $this->body['message'] : $body;
        $this->mailer->AltBody = strip_tags($useBodyContent ? $this->body['message'] : $body);
    }

    /**
     * Adds a "To" address
     *
     * @param string $name
     * @param string $email
     * @return void
     */
    public function addRecipient(string $name, string $email): void
    {
        $this->mailer->addAddress($email, $name);
    }

    /**
     * Create a message and send it.
     *
     * @return bool Returns false on error
     */
    public function send(): bool
    {
        return $this->mailer->send();
    }
}
