<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\ArticleRepository;
use App\Repository\EventRepository;
use App\Repository\PageRepository;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/event', name: 'app_')]
class EventController extends AbstractController
{
    #[Route('/', name: 'event')]
    public function index(EventRepository $eventRepository, ArticleRepository $articleRepository, SponsorRepository $sponsorRepository, PageRepository $pr): Response
    {
        $pageEvent = $pr->findBy(['name'=>'event']);
        //on recupère la liste des evenements par date de creation dans l'ordre inversé
        $events = $eventRepository->findBy([],['created_at' => 'DESC'],4);
        //on récupè le dernier article resumée de la liste
        $lastArticleResumes = $articleRepository->findByCategory(119);
        $articles = $articleRepository->findBy([],['created_at' => 'DESC'], 3);
        $sponsors = $sponsorRepository->findAll();
            return $this->render('event/accueil.html.twig', [
                'pageEvent' => $pageEvent,
                'events' => $events,
                'lastArticleResumes' => $lastArticleResumes,
                'articles' => $articles,
                'sponsors' => $sponsors
            ]);
    }
    
    #[Route('/{slug}', name:'detailEvent')]
    public function detailEvent(Event $event, EventRepository $er):Response
    {
        return $this->render('event/eventDetails.html.twig', [
            'event' => $event,
        ]);
    }
}