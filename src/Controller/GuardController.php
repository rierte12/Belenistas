<?php

namespace App\Controller;

use App\Entity\Guard;
use App\Repository\EventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GuardController extends AbstractController
{
    #[Route('/guardia', name: 'guard')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_event');
    }

    #[Route('/guardias/new', name: 'guard_new', methods: ['POST'])]
    public function new(EventRepository $eventRepository, EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $guard = new Guard();
        $guard->setStartTime(new DateTime($request->request->get('start')));
        $guard->setEndTime(new DateTime($request->request->get('end')));
        $guard->setTotalUsers($request->request->get('volunteers'));

        $event = $eventRepository->find($request->request->get('eventId'));
        $guard->setEvent($event);
        $entityManager->persist($guard);
        $entityManager->flush();
        return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
    }

    #[Route('/guardias/join/{guardId}', name: 'guard_join', methods: ['POST'])]
    public function join(EntityManagerInterface $entityManager, Security $security, $guardId): RedirectResponse
    {
        $user = $security->getToken()->getUser();
        $guard = $entityManager->getRepository(Guard::class)->find($guardId);
        $guard->addVolunteer($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_event_show', ['id' => $guard->getEvent()->getId()]);
    }
}