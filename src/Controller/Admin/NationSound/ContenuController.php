<?php

namespace App\Controller\Admin\NationSound;

use App\Entity\BackgroundImage;
use App\Entity\NationSound\Figure;
use App\Entity\Image;
use App\Entity\Page;
use App\Entity\NationSound\View;
use App\Form\PageFormType;
use App\Form\ViewType;
use App\Repository\PageRepository;
use App\Repository\ViewRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/nationsound', name: 'nationSound_'), IsGranted('ROLE_ADMIN')]
class ContenuController extends AbstractController
{
    // Controller pour la page d'accueil
    #[Route('/contenu', name: 'contenu_accueil')]
    public function accueil(ViewRepository $vw): Response
    {
        $page = $vw->findOneBy(['name'=>'accueil']);
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'title' => 'Accueil',
            'page' => $page,
        ]);
    }
    
    #[Route('/billetterie', name: 'contenu_billetterie')]
    public function billetterie(ViewRepository $vw): Response
    {
        $page = $vw->findOneBy(['name'=>'billetterie']);
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'title' => 'Billetterie',
            'page' => $page,
        ]);
    }
    #[Route('/programme', name: 'contenu_programme')]
    public function programme(ViewRepository $vw): Response
    {
        $page = $vw->findOneBy(['name'=>'programme']);
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'title' => 'Programme',
            'page' => $page,
        ]);
    }
    #[Route('/information', name: 'contenu_information')]
    public function information(ViewRepository $vw): Response
    {
        $page = $vw->findOneBy(['name'=>'information']);
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'title' => 'Information',
            'page' => $page,
        ]);
    }
    // Controller pour l'edition d'une page
    #[Route('/contenu/edit/{id?}', name: 'contenu_edit')]
    public function edit(View $view=null, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $new = false;
        if (!$view) {
            $view = new View();
            $new = true;
        }
        $form = $this->createForm(ViewType::class, $view);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            //on envoie les données du formulaire a l'entité view
            $view = $form->getData();
            //cree le slug et l'attribut a l'entité view
            $slug = $slugger->slug($view->getName());
            $view->setSlug($slug);
            //attribue le dosseier qui contiendra l'image de fond
            $folder='figure';
            //recupère l'image qui est dans le foprmulaire
            $headerImage = $form->get('headerImage')->getData();
            //attribue un nouveau non à l'image puis le tranfère dans le dossier
            if ($headerImage) {
                $newfilename = $pictureService->addFeaturedImage($headerImage, $folder);
                //verifie si l'entité view possede deja une figure
                $lastFigure = $view->getHeaderImage();
                // si il y'a deja une figure, elle est supprimer
                if ($lastFigure) {
                    // supprimer dans le dossier
                    $pictureService->backgropundImage($lastFigure, $folder);
                    // supprimer de la base de donées
                    $em->remove($lastFigure);
                }
                //cree une l'entité image et lui donne son nom
                $img = new Figure();
                $img->setName($newfilename);
                //cree la relation entre l'entité img et la view
                $view->setHeaderImage($img);
            }
            //envoie et enregistre sur la db le tous
            $em->persist($view);
            $em->flush();
            $this->addFlash('success','Modifications enregistrées');
            return $this->redirectToRoute("nationSound_contenu_$slug");
        }
        return $this->render('admin/NationSound/contenu/editPage.html.twig', [
            'form' => $form,
            'new' => $new,
            'view' => $view
        ]);
    }
}
