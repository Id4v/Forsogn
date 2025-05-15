<?php

namespace App\Mailer;

use App\Entity\FrontUser;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkDetails;

class FrontMailer
{
    public function __construct(protected readonly MailerInterface $mailer, #[Autowire(env: "MAIL_FRONT_SENDER")] protected readonly string $genericSender)
    {
    }

    public function sendLoginLink(FrontUser $user, LoginLinkDetails $loginDetails)
    {
        $loginLink = $loginDetails->getUrl();
        $message = new TemplatedEmail();
        $message->to($user->getEmail());
        $message->from($this->genericSender);
        $message->subject('Your login link');
        $text = <<<TXT
Here is your login link:
$loginLink
TXT;

        $message->text($text);

        $this->mailer->send($message);
    }
}