<?php

namespace App\Controller\Admin;

use App\Entity\Request as EntityRequest;
use App\Form\ReponseRequeteFormType;
use App\Repository\RequestRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('admin/requete', name: 'app_'), IsGranted('ROLE_ADMIN')]
class RequeteController extends AbstractController
{
    #[Route('/', name: 'requete')]
    public function index(RequestRepository $rr, Request $request, PaginatorInterface $paginator): Response
    {

        $paginations = $paginator->paginate(
            $rr->pagination(),
            $request->query->get('page', 1),
            5
        );
        return $this->render('admin/requete/index.html.twig', [
            'paginations' => $paginations
        ]);
    }
    #[Route('/{id?}', name: 'detailsRequete')]
    public function detailsRequete(EntityRequest $requete, EntityManagerInterface $em): Response
    {
        $requete->setOuvert(true);
        $em->persist($requete);
        $em->flush();
        return $this->render('admin/requete/detailsRequete.html.twig', [
            'requete' => $requete
        ]);
    }
    #[Route('/reponse/{id}', name:'reponseRequete')]
    public function reponseRequete(EntityRequest $requete, ReponseRequeteFormType $reponseRequeteFormType, Request $request, SendMailService $mail, EntityManagerInterface $em):Response
    {
        $reponse = $this->createForm(reponseRequeteFormType::class);
        $reponse->handleRequest($request);
        $email = $requete->getEmail();
        if ($reponse->isSubmitted()&&$reponse->isValid()) {
            $motif = $reponse->get('motif')->getData();
            $context = ['content' => $reponse->get('content')->getData()];
            $mail->send(
                '57brocoli@gmail.com',
                $email,
                $motif,
                'reponseRequete',
                $context
            );
            $requete->setStatut(true);
            $em->persist($requete);
            $em->flush();
            $this->addFlash('success', 'Reponse envoyer à $email');
            return $this->redirectToRoute('app_requete');
        }
        return $this->render('admin/requete/reponseRequete.html.twig',[
            'requete' => $requete,
            'response' => $reponse
        ]);
    }
}
