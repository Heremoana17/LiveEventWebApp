<?php

namespace App\Controller;

use App\Form\NewsLetterFormType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\SponsorRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name:'app_')]
class MainController extends AbstractController
{
    #[Route('', name: 'main')]
    public function index(CategoryRepository $cr, SponsorRepository $sr, EventRepository $er, NewsLetterFormType $newsletter, Request $request, UserRepository $ur, EntityManagerInterface $em): Response
    {
        $categoriesSection1 = $cr->findBy(['parent' => 70]);
        $events = $er->findAll();
        $sponsors = $sr->findAll();
        $lastEvent = $er->findOneBy(["name"=>'Astronote']);
        $formNewsletter = $this->createForm($newsletter::class);
        $formNewsletter->handleRequest($request);
        if ($formNewsletter->isSubmitted()&&$formNewsletter->isValid()) {
            $email = $formNewsletter->get('email')->getData();
            $user = $ur->findOneByEmail($email);
            if ($user) {
                $isSub = $user->isIsSubscriber();
                if ($isSub === false) {
                    $userId = $user->getId();
                    $user->setIsSubscriber(true);
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success','Felicitation, vous êtes maintenant abonné à la newletter');
                    return $this->redirectToRoute('app_userDetails', ['id' => $userId]);
                } else {
                    $this->addFlash('error', 'vous êtes deja inscrit à la newsletter.');
                    return $this->redirectToRoute('app_main');
                }
            } else {
                $this->addFlash('error', 'vous devez avoir un compte pour être abonner à la newsletter.');
                return $this->redirectToRoute('app_main');
            }
        }
        return $this->render('main/index.html.twig',[
            'categoriesSection1' =>$categoriesSection1,
            'sponsors' => $sponsors,
            'events' => $events,
            'lastEvent' => $lastEvent,
            'formNewsletter' => $formNewsletter->createView()
        ]);
    }
}
