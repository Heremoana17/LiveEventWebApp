<?php

namespace App\Controller\Admin;

use App\Entity\BackgroundImage;
use App\Form\BackgroundImageFormType;
use App\Repository\BackgroundImageRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/background/image', name: 'app_background_image_'), IsGranted('ROLE_ADMIN')]
class BackgroundImageController extends AbstractController
{
    #[Route('/all', name:'all')]
    public function index(BackgroundImageRepository $bgr): Response
    {
        $backgroundImages = $bgr->findAll();
        return $this-> render('admin/background_image/allBackgroundImage.html.twig', [
            'backgroundImages' => $backgroundImages
        ]);
    }

    #[Route('/edit', name:'edit')]
    public function edit(Request $request, PictureService $pictureService, EntityManagerInterface $em): Response
    {
        $backgroundImage = new BackgroundImage();
        $form = $this->createForm(BackgroundImageFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $backgroundImage = $form->getData();
            //on créé le nom du dossier
            $folder = 'backgroundImages';
            //on récupère l'image l'image
            $image = $form->get('name')->getData();
            $newFileName = $pictureService->addFeaturedImage($image, $folder);
            //on ajout l'image & l'entité
            $backgroundImage->setName($newFileName);
            //on persist et flush
            $em->persist($backgroundImage);
            $em->flush();
            return $this->redirectToRoute('app_background_image_all');
        }
        return $this->render('admin/background_image/backgroundimageForm.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/delete/{id?}', name:'delete')]
    public function delete(BackgroundImage $backgroundImage, PictureService $pictureService, EntityManagerInterface $em) : RedirectResponse
    {
        if ($backgroundImage) {
            $folder = 'backgroundImages';
            $pictureService->backgropundImage($backgroundImage, $folder);
            $em->remove($backgroundImage);
            $em->flush();
            $this->addFlash('success', 'Image supprimée');
            return $this->redirectToRoute('app_background_image_all');
        }
        $this->addFlash('error', 'Une erreur est survenue');
        return $this->redirectToRoute('app_background_image_all');
    }
}
