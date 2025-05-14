<?php

namespace App\Controller\Front;

use App\Repository\FrontUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandler;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(Request $request, LoginLinkHandler $loginLinkHandler, FrontUserRepository $userRepository): Response
    {
        if ($request->getMethod() === 'POST') {
            // load the user in some way (e.g. using the form input)
            $email = $request->getPayload()->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            // create a login link for $user this returns an instance
            // of LoginLinkDetails
            $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
            $loginLink = $loginLinkDetails->getUrl();

            //TODO: Send Email and redirect to a "Mail Sent Check your inbox"

        }

        return $this->render('security/front_login.html.twig');
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
