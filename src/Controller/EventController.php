<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class EventController extends AbstractController
{
    #[Route('/eventos/', name: 'app_event', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAllEvents();
        return $this->render('event/index.html.twig', [
            'title' => 'Eventos',
            'events' => $events,
        ]);
    }

    #[Route('/eventos/new', name: 'app_event_new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $event = new Event();
        $event->setName($request->request->get('name'));
        $event->setDescription($request->request->get('description'));
        $event->setLocation($request->request->get('location'));
        $event->setStart(new DateTime($request->request->get('start')));
        $event->setEnd(new DateTime($request->request->get('end')));

        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('app_event');
    }

    #[Route('/eventos/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(EventRepository $eventRepository, string $id, SerializerInterface $serializer): Response
    {
        try {
            $event = $eventRepository->findById($id);
        } catch (InvalidUuidStringException $e) {
            throw new NotFoundHttpException('Evento no encontrado');
        }
        if (!$event) throw $this->createNotFoundException('Evento no encontrado');

        return $this->render('event/guards.html.twig', [
            'title' => 'Guardias - ' . $event->getName(),
            'event' => $event,
        ]);
    }

    #[Route('/eventos/{id}/json', name: 'app_event_json', methods: ['GET'])]
    public function showJson(EventRepository $eventRepository, string $id, SerializerInterface $serializer): JsonResponse
    {
        try {
            $event = $eventRepository->findById($id);
        } catch (InvalidUuidStringException $e) {
            throw new NotFoundHttpException('Evento no encontrado');
        }
        if (!$event) throw $this->createNotFoundException('Evento no encontrado');

        $json = $serializer->serialize($event, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['hidden'],
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

