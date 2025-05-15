<?php

namespace App\Controller\Front;

use App\Mailer\FrontMailer;
use App\Repository\FrontUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class SecurityController extends AbstractController
{
    public function __construct(private readonly FrontMailer $frontMailer)
    {
    }

    #[Route(path: '/login', name: 'login')]
    public function login(Request $request, LoginLinkHandlerInterface $loginLinkHandler, FrontUserRepository $userRepository): Response
    {
        $error = null;
        $email = null;
        $mailSent = false;

        if ($request->getMethod() === 'POST') {
            // load the user in some way (e.g. using the form input)
            $email = $request->getPayload()->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);
            if ($user) {
                // create a login link for $user this returns an instance
                // of LoginLinkDetails
                $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
                $this->frontMailer->sendLoginLink($user, $loginLinkDetails);

                $mailSent = true;
            }else {
                $error = "User not found";
            }
        }

        return $this->render('security/front_login.html.twig', ['mailSent' => $mailSent, 'last_username' => $email, 'error' => $error]);
    }

    #[Route(path:'/login_check', name: 'login_check')]
    public function loginCheck()
    {

    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
