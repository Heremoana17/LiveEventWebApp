<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Image;
use App\Form\ArticleFormType;
use App\Form\NotificationArticleFormType;
use App\Repository\ArticleRepository;
use App\Repository\EventRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(EventRepository $er): Response
    {
        $events = $er->byCatNameCityDate('nation sound', 'festival', 1, 1672527600);
        // $events = $er->byCityDate('1');
        dd($events);
        return $this->render('test/index.html.twig', [
            'events' => $events
        ]);
    }
}
