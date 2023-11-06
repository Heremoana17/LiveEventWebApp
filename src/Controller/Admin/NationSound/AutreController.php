<?php

namespace App\Controller\Admin\NationSound;

use App\Entity\NationSound\Lieu;
use App\Entity\NationSound\Link;
use App\Form\LieuType;
use App\Form\LinkType;
use App\Repository\LieuRepository;
use App\Repository\LinkRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/nationsound', name: 'nationSound_'), IsGranted('ROLE_ADMIN')]
class AutreController extends AbstractController
{
    // Controller pour les lieux
    #[Route('/lieux', name: 'lieux')]
    public function lieux(LieuRepository $lr): Response
    {
        $lieux = $lr->findAll();
        return $this->render('admin/NationSound/lieux/lieux.html.twig', [
            'lieux' => $lieux,
        ]);
    }
    #[Route('/lieu/{id?}', name: 'detailLieu')]
    public function detailLieu(Lieu $lieu, LieuRepository $lr): Response
    {
        $lieux = $lr->findOneBy(['id'=>$lieu]);
        return $this->render('admin/NationSound/lieux/detailLieu.html.twig', [
            'lieu' => $lieu,
        ]);
    }
    #[Route('/add/lieu/{id?}', name: 'addLieu')]
    public function addLieu(Lieu $lieu = null, Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $new = false;
        if (!$lieu) {
            $lieu = new lieu();
            $new = true;
        }
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $lieu = $form->getData();
            $featuredImage = $form->get('featuredImage')->getData();
            $folder = 'lieu';
            if ($featuredImage) {
                $lastFeaturedImage = $lieu->getfeaturedImage();
                if ($lastFeaturedImage) {
                    $pictureService->deleteSimpleImage($lastFeaturedImage, $folder);
                }
                $newFileName = $pictureService->addFeaturedImage($featuredImage, $folder);
                $lieu->setFeaturedImage($newFileName);
            }
            $em->persist($lieu);
            $em->flush();
            if ($new) {
                $this->addFlash('success','Lieu ajouter');
            }else {
                $this->addFlash('success','Lieu mis-à-jour');
            }
            return $this->redirectToRoute('nationSound_lieux');
        }
        return $this->render('admin/NationSound/lieux/addLieu.html.twig', [
            'form' => $form,
            'new' => $new,
            'lieu' => $lieu
        ]);
    }
    #[Route('/delete/lieu/{id?}', name:"deleteLieu")]
    public function deletLieu(Lieu $lieu=null, EntityManagerInterface $em, PictureService $pictureService): RedirectResponse
    {
        if (!$lieu) {
            $this->addFlash('danger', 'Une erreur est surenu');
            return $this->redirectToRoute('nationSound_lieux');
        } else {
            $featuredImage = $lieu->getFeaturedImage();
            if ($featuredImage) {
                $pictureService->deleteSimpleImage($featuredImage, 'lieu');
            }
            $em->remove($lieu);
            $em->flush();
            $this->addFlash('success', 'Lieu supprimer');
            return $this->redirectToRoute('nationSound_lieux');
        }
        
    }
    // Controller pour la charte
    #[Route('/charte', name: 'charte')]
    public function charte(): Response
    {
        return $this->render('admin/NationSound/autre/charte.html.twig', [
            'controller_name' => 'AutreController',
        ]);
    }
    // Controller pour les liens
    #[Route('/link', name: 'links')]
    public function links(LinkRepository $lr): Response
    {
        $links = $lr->findAll();
        return $this->render('admin/NationSound/autre/link.html.twig', [
            'links' => $links,
        ]);
    }
    #[Route('/link/edit/{id?}', name: 'editLink')]
    public function editLink(Link $link=null, Request $request, EntityManagerInterface $em): Response
    {
        $new = false;
        if (!$link) {
            $link = new Link();
            $new = true;
        }
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $link = $form->getData();
            $em->persist($link);
            $em->flush();
            $this->addFlash('success','Liens enregistré');
            return $this->redirectToRoute('nationSound_links');
        }
        return $this->render('admin/NationSound/autre/editLink.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/delete/link/{id?}', name: 'deleteLink')]
    public function deletelinks(Link $link=null, EntityManagerInterface $em): RedirectResponse
    {
        if (!$link) {
            $this->addFlash('danger', 'Une erreur est surenu');
            return $this->redirectToRoute('nationSound_links');
        } else {
            $em->remove($link);
            $em->flush();
            $this->addFlash('success', 'Scene supprimer');
            return $this->redirectToRoute('nationSound_links');
        }
    }
}
