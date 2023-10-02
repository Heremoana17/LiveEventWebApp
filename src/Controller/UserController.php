<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users', name:'app_')]
class UserController extends AbstractController
{
    #[Route('/edit/{id?}', name: 'editUser'), IsGranted('ROLE_USER')]
    public function editUser(User $user=null, ManagerRegistry $doctrine, Request $request): Response
    {
        //retrouve l'utilisateur actuelement actif
        $userActif = $this->getUser();
        $userActifRole = $userActif->getRoles()[0];
        //verifie que l'utilisataur actif est bien l'utilisateur recherché
        if ($userActifRole !== 'ROLE_ADMIN' && $userActif !== $user) {
            $this->addFlash('danger', "Une erreur est survenue");
            return $this->redirectToRoute('app_main');
        }
        $new = false;
        //si il n'y a pas d'utilisateur
        if(!$user){
            $user = new User();
            $new = true;
        }
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $user=$form->getData();
            $isSub = $form->get('newsLetter')->getData();
            if ($isSub === true) {
                $user->setIsSubscriber(true);
            }
            $manager = $doctrine->getManager();
            $manager->persist($user);
            $manager->flush();
            if ($new) {
                $this->addFlash('success', 'Nouveau compte creé');
            }else{
                $this->addFlash('success','Donées mis à jours');
            }
            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/userForm.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'userDetails'),IsGranted('ROLE_USER')]
    public function userDetails(User $user, ArticleRepository $ar): Response
    {
        //retrouve l'utilisateur actuelement actif
        $userActif = $this->getUser();
        $userActifRole = $userActif->getRoles()[0];
        //verifie que l'utilisataur actif est bien l'utilisateur recherché
        if ($userActifRole !== 'ROLE_ADMIN' && $userActif !== $user) {
            $this->addFlash('danger', "Une erreur est survenue");
            return $this->redirectToRoute('app_main');
        }
        $articles = $ar->findBy(['author' => $userActif]);
        return $this->render('user/userDetails.html.twig', [
            'user' => $user,
            'articles' => $articles
        ]);
    }

}
