<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class BrithDateAuth extends AbstractAuthenticator
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    /* Check if the route is app_login and the method is POST */
    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') == 'app_login' && $request->isMethod('POST');
    }

    /* This method is called when the supports() method returns true */
    public function authenticate(Request $request): Passport
    {
        $birthdate = $request->request->get('birthdate');
        $idNumber = $request->request->get('idNumber');

        $user = $this->userRepository->findOneBy([
            'idNumber' => $idNumber
        ]);

        if (!$user) {
            throw new AuthenticationException('No user found for this idNumber.');
        }

        if ($user->getBirthdate()->format('Y-m-d') !== $birthdate) {
            throw new AuthenticationException('The birthdate is not valid.');
        }

        return new SelfValidatingPassport(new UserBadge($idNumber));

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse('/eventos');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        echo $exception->getMessage();
        return null;
    }
}