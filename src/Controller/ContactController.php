<?php

namespace App\Controller;

use App\Entity\Request;
use App\Form\RequestFormType;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as Rq;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(RequestFormType $requestFormType, Rq $request, EntityManagerInterface $em, SendMailService $mail): Response
    {
        $requestMessage = new Request();
        $form = $this->createForm($requestFormType::class, $requestMessage);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $requestMessage=$form->getData();
            $em->persist($requestMessage);
            $em->flush();

            $context = [
                'content' => $form->get('content')->getData(),
                'lastname' => $form->get('lastname')->getData(),
                'firstname' => $form->get('firstname')->getData(),
                'from' => $form->get('email')->getData(),
                'motif' => $form->get('motif')->getData()
            ];
            $mail->send(
                $form->get('email')->getData(),
                '57brocoli@gmail.com',
                'Message utilisateur',
                'requete_utilisateur',
                $context
            );
            $this->addFlash('success', 'Votre message a bien été envoyer');
            return $this->redirectToRoute('app_main');
            
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form
        ]);
    }
}
