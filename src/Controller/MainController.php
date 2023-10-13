<?php

namespace App\Controller;

use App\Form\NewsLetterFormType;
use App\Form\SearchEventFormType;
use App\Repository\BackgroundImageRepository;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\PageRepository;
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
    public function index(CategoryRepository $cr, SponsorRepository $sr, EventRepository $er, NewsLetterFormType $newsletter, Request $request, UserRepository $ur, EntityManagerInterface $em, PageRepository $pr): Response
    {
        //on recupère la page accueil pour l'image de fond
        $PageAccueil = $pr->findBy(['name' => 'accueil']);
        //on recupère les evenements
        $events = $er->findAll();
        //on cree importe un formulaire pour les evenements
        $formEvent = $this->createForm(SearchEventFormType::class);
        $formEvent->handleRequest($request);
        if ($formEvent->isSubmitted()&&$formEvent->isValid()) {
            $category = $formEvent->get('genre')->getData();
            $event = $formEvent->get('name')->getData();
            $city = $formEvent->get('city')->getData();
            $date = $formEvent->get('date')->getData();
            if ($date && $event && $category && $city) {
                $events = $er->byCatNameCityDate($event->getName(), $category->getName(), $city->getId(), $date);
            
            }else if ($event==null && $category==null && $city==null && $date) {
                $events = $er->byDate($date);
            
            }else if ($date==null && $category==null && $city==null && $event){
                $events = $er->byNameDate($event->getName());
            
            }else if ($date==null && $event==null && $city==null && $category){
                $events = $er->byCatDate($category->getName());
            
            }else if ($date==null && $event==null && $category==null && $city) {
                $events = $er->byCityDate($city->getId());

            }else if ($date==null && $event==null && $category && $city) {
                $events = $er->byCatCityDate($category->getName(), $city->getId());
                
            }else if ($date==null && $category==null && $event && $city) {
                $events = $er->byEventCityDate($event->getName(), $city->getId());
                
            }else if($date==null && $city==null && $category && $event){
                $events = $er->byCatNameDate($event->getName(), $category->getName());
            
            }else if($date==null && $category && $event && $city){
                $events = $er->byCatNameCityDate($event->getName(), $category->getName(), $city->getId());
            
            }else if($date==null && $city==null && $category && $event){
                $events = $er->byCatNameDate($event->getName(), $category->getName());
            
            }else {
                //on recupère tous les evenements
                $events = $er->findAll();
            }
        }
        //on récupère les sponsor
        $sponsors = $sr->findAll();
        //on récupère l'evenement le plus loin en date
        $lastEvent = $er->findOneBy([],['date' => 'DESC']);
        //on importe le formulaire d'inscription à la newsletter
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
            'PageAccueil' => $PageAccueil,
            'formEvent' => $formEvent,
            'sponsors' => $sponsors,
            'events' => $events,
            'lastEvent' => $lastEvent,
            'formNewsletter' => $formNewsletter->createView()
        ]);
    }
}
