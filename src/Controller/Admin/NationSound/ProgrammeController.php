<?php

namespace App\Controller\Admin\NationSound;

use App\Entity\NationSound\Artiste;
use App\Entity\NationSound\Day;
use App\Entity\NationSound\Episode;
use App\Entity\NationSound\Lieu;
use App\Form\ArtisteType;
use App\Form\DayType;
use App\Form\EpisodeType;
use App\Form\LieuType;
use App\Repository\ArtisteRepository;
use App\Repository\DayRepository;
use App\Repository\EpisodeRepository;
use App\Repository\LieuRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/nationsound', name: 'nationSound_'), IsGranted('ROLE_ADMIN')]
class ProgrammeController extends AbstractController
{
    //controller dune journée
    #[Route('/jour', name: 'jours')]
    public function jours(DayRepository $dr): Response
    {
        $days = $dr->findAll();
        return $this->render('admin/NationSound/journee/journees.html.twig', [
            'days' => $days
        ]);
    }
    
    #[Route('/jour/edit/{id?}', name: 'editJour')]
    public function editJour(Day $day = null, Request $request, EntityManagerInterface $em): Response
    {
        $new = false;
        if (!$day) {
            $day = new day();
            $new = true;
        }
        $form = $this->createForm(DayType::class, $day);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $day = $form->getData();
            $em->persist($day);
            $em->flush();
            if ($new) {
                $this->addFlash('success', 'Nouvelle journée programée');
            }else {
                $this->addFlash('success', 'Journée mis-à-jour');
            }
            return $this->redirectToRoute('nationSound_jours');
        }
        return $this->render('admin/NationSound/journee/addJournee.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/jour/episode/edit/{id?}', name: 'joureditEpisode')]
    public function joureditProgramme(Episode $episode = null, Request $request, EntityManagerInterface $em): Response
    {
        $new = false;
        if (!$episode) {
            $episode = new episode();
            $new = true;
        }
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $episode = $form->getData();
            $em->persist($episode);
            $em->flush();
            if ($new) {
                $this->addFlash('success','Nouvelle episode programée');
            } else {
                $this->addFlash('success','Episode mis-à-jour');
            }
            return $this->redirectToRoute('nationSound_jours');
        }
        return $this->render('admin/NationSound/episode/editEpisode.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/jour/delete/{id?}', name: 'deleteJour')]
    public function deleteJour(Day $day = null, EntityManagerInterface $em): Response
    {
        if (!$day) {
            $this->addFlash('danger','Une erreur est apparut');
            return $this->redirectToRoute('nationSound_jours');
        }else{
            $episode = $day->getEpisode();
            if (empty($episode)) {
                $em->remove($day);
                $em->flush();
                $this->addFlash('success','Journée supprimer');
                return $this->redirectToRoute('nationSound_jours');
            } else {
                foreach ($episode as $epi) {
                    $epi->setDay(null);
                }
                $em->remove($day);
                $em->flush();
                $this->addFlash('success','Journée supprimer');
                return $this->redirectToRoute('nationSound_jours');
            }
        }
    }
    #[Route('/jour/{id}/add/episode', name: 'addEpisodeJour')]
    public function editEpisode(Day $day, Request $request, EntityManagerInterface $em): Response
    {
        $episode = new episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $episode = $form->getData();
            $episode->setDay($day);
            $em->persist($episode);
            $em->flush();
            $this->addFlash('success','Nouvelle episode programée et ajoutée');
            return $this->redirectToRoute('nationSound_jours');
        }
        return $this->render('admin/NationSound/episode/editEpisode.html.twig', [
            'form' => $form,
            'day' => $day
        ]);
    }
    //controller des episodes
    #[Route('/episode', name: 'episodes')]
    public function programme(EpisodeRepository $episode): Response
    {
        $episodes = $episode->findAll();
        return $this->render('admin/NationSound/episode/episodes.html.twig', [
            'episodes' => $episodes
        ]);
    }
    #[Route('/episode/edit/{id?}', name: 'editEpisode')]
    public function editProgramme(Episode $episode = null, Request $request, EntityManagerInterface $em): Response
    {
        $new = false;
        if (!$episode) {
            $episode = new episode();
            $new = true;
        }
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $episode = $form->getData();
            $em->persist($episode);
            $em->flush();
            if ($new) {
                $this->addFlash('success','Nouvelle episode programée');
            } else {
                $this->addFlash('success','Episode mis-à-jour');
            }
            return $this->redirectToRoute('nationSound_episodes');
        }
        return $this->render('admin/NationSound/episode/editEpisode.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/episode/withdraw/{id?}', name: 'withdrawEpisode')]
    public function withdrawEpisode(Episode $episode, EntityManagerInterface $em): Response
    {
        $episode->setDay(null);
        $em->persist($episode);
        $em->flush();
        $this->addFlash('success','Episode retiré de la journée');
        return $this->redirectToRoute('nationSound_jours');
    }
    #[Route('/episode/delete/{id?}', name: 'deleteEpisode')]
    public function deleteEpisode(Episode $episode=null, EntityManagerInterface $em): Response
    {
        if (!$episode) {
            $this->addFlash('danger', 'Une erreur est surenu');
            return $this->redirectToRoute('nationSound_episodes');
        } else {
            $em->remove($episode);
            $em->flush();
            $this->addFlash('success', 'Episode supprimer');
            return $this->redirectToRoute('nationSound_episodes');
        }
    }

    //controller des artistes
    #[Route('/artistes', name: 'artistes')]
    public function artistes(ArtisteRepository $ar): Response
    {
        $artistes = $ar->findAll();
        return $this->render('admin/NationSound/artiste/artistes.html.twig', [
            'artistes' => $artistes,
        ]);
    }
    #[Route('/artiste/{id}', name: 'detailsArtiste')]
    public function detailsArtiste(ArtisteRepository $ar, Artiste $artiste): Response
    {
        $artiste = $ar->findOneBy(['id'=>$artiste]);
        return $this->render('admin/NationSound/pagedetails.html.twig', [
            'data' => $artiste,
            'folder' => 'assets/uploads/artiste/',
            'file' => $artiste->getFeaturedImage(),
        ]);
    }
    #[Route('/add/artiste/{id?}', name: 'addArtiste')]
    public function addArtistes(Artiste $artiste = null, Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $new = false;
        if (!$artiste) {
            $artiste = new artiste();
            $new = true;
        }
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $artiste = $form->getData();
            $folder = 'artiste';
            $featuredImage = $form->get('featuredImage')->getData();
            if($featuredImage){
                $lastFeaturedImage = $artiste->getfeaturedImage();
                if ($lastFeaturedImage) {
                    $pictureService->deleteSimpleImage($lastFeaturedImage, $folder);
                }
                $newFileName = $pictureService->addFeaturedImage($featuredImage, $folder);
                $artiste->setFeaturedImage($newFileName);
            }
            $em->persist($artiste);
            $em->flush();
            if ($new) {
                $this->addFlash('success', 'Artiste ajouter');
            }
            else {
                $this->addFlash('success', 'Artiste modifier');
            }
            return $this->redirectToRoute('nationSound_artistes');
        }
        return $this->render('admin/NationSound/artiste/addArtiste.html.twig', [
            'form' => $form,
            'new' => $new,
            'artiste' => $artiste
        ]);
    }
    #[Route('/delete/artiste/{id?}', name:'deleteArtiste')]
    public function deleteArtiste(Artiste $artiste=null, EntityManagerInterface $em, PictureService $pictureService): RedirectResponse
    {
        if (!$artiste) {
            $this->addFlash('danger', 'Une erreur est surenu');
            return $this->redirectToRoute('nationSound_artistes');
        } else {
            $episodes = $artiste->getEpisodes();
            if ($episodes) {
                foreach($episodes as $episode){
                    $artiste->removeEpisode($episode);
                }
            }
            $links = $artiste->getLinks();
            if ($links) {
                foreach($links as $link){
                    $em->remove($link);
                }
            }
            $featuredImage = $artiste->getFeaturedImage();
            if ($featuredImage) {
                $pictureService->deleteSimpleImage($featuredImage, 'artiste');
            }
            $em->remove($artiste);
            $em->flush();
            $this->addFlash('success', 'Artiste supprimer');
            return $this->redirectToRoute('nationSound_artistes');
        }
    }

    //controller des scenes
    #[Route('/scene', name: 'scenes')]
    public function scenes(LieuRepository $sr): Response
    {
        $scenes = $sr->findBy(['category'=>'Scene']);
        return $this->render('admin/NationSound/scene/scenes.html.twig', [
            'scenes' => $scenes,
        ]);
    }
    #[Route('/add/scene/{id?}', name: 'addScene')]
    public function addScene(Request $request, Lieu $lieu=null, EntityManagerInterface $em, PictureService $pictureService): Response
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
                $this->addFlash('success','Nouvelle scene ajouter');
            }else {
                $this->addFlash('success','Scene mis-à-jour');
            }
            return $this->redirectToRoute('nationSound_scenes');
        }
        return $this->render('admin/NationSound/scene/addScene.html.twig', [
            'form' => $form,
            'new' => $new,
            'scene' => $lieu
        ]);
    }
    #[Route('/delete/scene/{id?}', name:'deleteScene')]
    public function deleteScene(Lieu $lieu=null, EntityManagerInterface $em, PictureService $pictureService): RedirectResponse
    {
        if (!$lieu) {
            $this->addFlash('danger', 'Une erreur est surenu');
            return $this->redirectToRoute('nationSound_scenes');
        } else {
            $featuredImage = $lieu->getFeaturedImage();
            if ($featuredImage) {
                $pictureService->deleteSimpleImage($featuredImage, 'lieu');
            }
            $em->remove($lieu);
            $em->flush();
            $this->addFlash('success', 'Scene supprimer');
            return $this->redirectToRoute('nationSound_scenes');
        }
    }
    
}
