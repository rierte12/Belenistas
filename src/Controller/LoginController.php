<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('login/login.html.twig', [
            'title' => 'Login',
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Security $security) : Response
    {
        $security->logout(false);
        return $this->render('login/logout.html.twig', [
            'title' => 'Sesión cerrada'
        ]);
    }
}
