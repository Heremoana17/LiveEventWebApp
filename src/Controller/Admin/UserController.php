<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('admin/users', name:'app_'),IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $users = $paginatorInterface->paginate(
            $userRepository->findAll(),
            $request->query->get('page', 1),
            12
        );
        return $this->render('admin/user/allUsers.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/edit/{id?}', name: 'adminEditUser')]
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
            $manager = $doctrine->getManager();
            $user=$form->getData();
            $isSub = $form->get('newsLetter')->getData();
            if ($isSub === true) {
                $user->setIsSubscriber(true);
            }else {
                $user->setIsSubscriber(false);
            }
            $manager->persist($user);
            $manager->flush();
            if ($new) {
                $this->addFlash('success', 'Nouveau compte creé');
            }else{
                $this->addFlash('success','Donées mis à jours');
            }
            return $this->redirectToRoute('app_user');
        }
        return $this->render('admin/user/userForm.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delet/{id}', name: 'deletUser')]
    public function deletUser(ManagerRegistry $doctrine, User $user): RedirectResponse
    {
        $manager = $doctrine->getManager();
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'Utilisateur supprimé');
        return $this->redirectToRoute('app_user', [
            'user' => $user,
        ]);
    }

}