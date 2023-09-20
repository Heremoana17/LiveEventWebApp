<?php

namespace App\Controller\Admin;

use App\Entity\ImageSponsor;
use App\Entity\Logo;
use App\Entity\Sponsor;
use App\Form\SponsorFormType;
use App\Repository\SponsorRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/sponsor', name: 'app_'),IsGranted('ROLE_ADMIN')]
class SponsorController extends AbstractController
{
    #[Route('/', name:'allSponsor')]
    public function index(SponsorRepository $sponsorRepository): Response
    {
        $sponsors = $sponsorRepository->findAll();
        return $this->render('admin/sponsor/allSponsor.html.twig', [
            'sponsors' => $sponsors,
        ]);
    }

    #[Route('/edit/{id?}', name:'editSponsor')]
    public function editSponsor(Sponsor $sponsor = null, Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $new = false;
        if (!$sponsor) {
            $sponsor = new Sponsor();
            $new= true;
        }
        $form = $this->createForm(SponsorFormType::class, $sponsor);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $folder = 'sponsors';
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $fichier = $pictureService->addImages($image, $folder, 1024, 600);
                $img = new ImageSponsor();
                $img->setName($fichier);
                $sponsor->addImageSponsor($img);
            }
            $logo = $form->get('logo')->getData();
            if ($logo) {
                $newFileName = $pictureService->addFeaturedImage($logo, $folder);
                $sponsor->setLogo($newFileName);
            }
            $sponsor = $form->getData();
            $em->persist($sponsor);
            $em->flush();
            //creation des messages flash
            if ($new) {
                $this->addFlash('success', 'Nouveau sponsor crée');
            }else{
                $this->addFlash('success', 'Sponsor mis à jour');
            }
            return $this->redirectToRoute('app_allSponsor');
        }
        return $this->render('admin/sponsor/sponsorForm.html.twig', [
            'form' => $form,
            'new' =>  $new,
            'sponsor' => $sponsor
        ]);
    }

    #[Route('/delete/sponsor/{id?}', name:'deletSponsor')]
    public function deletSponsor(Sponsor $sponsor, EntityManagerInterface $em):RedirectResponse
    {
        $em->remove($sponsor);
        $em->flush();
        $this->addFlash('success','Sponsor supprimer');
        return $this->redirectToRoute('app_allSponsor');
    }

    #[Route('/delete/image/{id}', name:'delete_sponsor_image', methods:['DELETE'])]
    public function deleteImage(ImageSponsor $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'sponsors', 300, 300)){
                // On supprime l'image de la base de données
                $em->remove($image);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

    #[Route('/delete/logo/{id}', name:'delete_sponsor_logo', methods:['DELETE'])]
    public function deleteLogo(Logo $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'logo', 300, 300)){
                // On supprime l'image de la base de données
                $em->remove($image);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

    #[Route('/{id?}', name:'detailsSponsor'), IsGranted('ROLE_ADMIN')]
    public function detailsSponsors(Sponsor $sponsor):Response
    {
        return $this->render('admin/sponsor/detailsSponsor.html.twig', [
            'sponsor' => $sponsor
        ]);
    }
}
