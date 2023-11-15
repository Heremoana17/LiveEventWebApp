<?php

namespace App\Controller\Admin\NationSound;

use App\Entity\Article;
use App\Entity\BackgroundImage;
use App\Entity\Billet;
use App\Entity\FAQ;
use App\Entity\NationSound\Figure;
use App\Entity\Image;
use App\Entity\ImageSponsor;
use App\Entity\NationSound\PageSection;
use App\Entity\Page;
use App\Entity\NationSound\View;
use App\Entity\Sponsor;
use App\Form\BilletType;
use App\Form\FAQType;
use App\Form\PageFormType;
use App\Form\PageSectionType;
use App\Form\SponsorFormType;
use App\Form\ViewType;
use App\Repository\ArticleRepository;
use App\Repository\BilletRepository;
use App\Repository\DayRepository;
use App\Repository\EventRepository;
use App\Repository\FAQRepository;
use App\Repository\PageRepository;
use App\Repository\SponsorRepository;
use App\Repository\ViewRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/nationsound', name: 'nationSound_'), IsGranted('ROLE_ADMIN')]
class ContenuController extends AbstractController
{
    // Controller pour la page d'accueil
    #[Route('/accueil', name: 'contenu_accueil')]
    public function accueil(ViewRepository $vw): Response
    {
        $page = $vw->findOneBy(['name'=>'accueil']);
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'pageDefaultName' => 'accueil',
            'title' => 'Accueil',
            'page' => $page,
        ]);
    }
    
    #[Route('/billetterie', name: 'contenu_billetterie')]
    public function billetterie(ViewRepository $vw, EventRepository $er, BilletRepository $br): Response
    {
        //on récupère la page qui a pour name billetterie
        $page = $vw->findOneBy(['name'=>'billetterie']);
        //on recupère les billet qui sont liés a l'evenement Nations Sound
        $nationSound = $er->findOneBy(['name'=>'Nation Sound']);
        $billets = $br->findBy(['event'=>$nationSound->getId()]);
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'pageDefaultName' => 'billetterie',
            'title' => 'Billetterie',
            'page' => $page,
            'billets' => $billets,
        ]);
    }

    #[Route('/billetterie/edit/{id?}', name: 'contenu_billetterie_edit')]
    public function billetEdit(Billet $billet=null, EventRepository $er, Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $new = false;
        if (!$billet) {
            $billet = new Billet();
            $new = true;
        }
        $nationSound = $er->findOneBy(['name'=>'Nation Sound']);
        $form = $this->createForm(BilletType::class, $billet);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $billet = $form->getData();
            $billet->setEvent($nationSound);
            $folder='billet';
            $featuredImage = $form->get('featuredImage')->getData();
            if ($featuredImage) {
                $lastFeaturedImage = $billet->getfeaturedImage();
                if ($lastFeaturedImage) {
                    $pictureService->deleteSimpleImage($lastFeaturedImage, $folder);
                }
                $newFileName = $pictureService->addFeaturedImage($featuredImage, $folder);
                $billet->setFeaturedImage($newFileName);
            }
            $em->persist($billet);
            $em->flush();
            if ($new) {
                $this->addFlash('succes','Billet crée');
            } else {
                $this->addFlash('succes','Billet mis-à-jour');
            }
            return $this->redirectToRoute('nationSound_contenu_billetterie');
        }
        return $this->render('admin/NationSound/contenu/addBillet.html.twig', [
            'form' => $form,
            'event' => $nationSound,
            'billet' => $billet,
            'new' => $new
        ]);
    }

    #[Route('/billetterie/{id?}', name: 'contenu_billetterie_details')]
    public function billetDetails(Billet $billet=null, BilletRepository $br): Response
    {
        $billet = $br->findOneBy(['id'=>$billet]);
        return $this->render('admin/NationSound/contenu/pagedetails.html.twig', [
            'data' => $billet,
            'folder' => 'assets/uploads/billet/',
            'file' => $billet->getFeaturedImage(),
        ]);
    }

    #[Route('/billetterie/delete/{id?}', name: 'contenu_billetterie_delete')]
    public function billetDelete(Billet $billet=null, EntityManagerInterface $em, PictureService $pictureService): RedirectResponse
    {
        if ($billet) {
            $featuredImage = $billet->getFeaturedImage();
            $pictureService->deleteSimpleImage($featuredImage, 'billet');
            $em->remove($billet);
            $em->flush();
            $this->addFlash('success','billet supprimer');
            return $this->redirectToRoute('nationSound_contenu_billetterie');
        }else{
            $this->addFlash('error','Une erreur est apparu');
            return $this->redirectToRoute('nationSound_contenu_billetterie');
        }
    }

    // Controller pour la page Programme
    #[Route('/programme', name: 'contenu_programme')]
    public function programme(ViewRepository $vw, DayRepository $dr): Response
    {
        //on recup-re le contenu de la page programme
        $page = $vw->findOneBy(['name'=>'programme']);
        //on recupère le contenu du programme de nation sound
        $days = $dr->findAll();

        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'pageDefaultName' => 'programme',
            'title' => 'Programme',
            'page' => $page,
            'days' => $days
        ]);
    }

    // Controller pour la page actualité faq
    #[Route('/actualite', name: 'contenu_actualite')]
    public function information(ViewRepository $vw, FAQRepository $fr, EventRepository $er, ArticleRepository $ar): Response
    {
        //on recupère le contenu de la page
        $page = $vw->findOneBy(['name'=>'actualite']);
        //on récupère les articles liés à l'évènement nationsound
        $nationSound = $er->findOneBy(['name'=>'Nation Sound']);
        $articles = $ar->findBy(['relatedEvent'=>$nationSound->getId()]);
        //on recupère la faq
        $FAQs = $fr->findAll();
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'pageDefaultName' => 'actualite',
            'title' => 'Actualite',
            'page' => $page,
            'articles' => $articles,
            'FAQs' => $FAQs
        ]);
    }

    #[Route('/actualite/edit/{id?}', name: 'contenu_actualite_edit')]
    public function informationEdit(FAQ $faq=null, Request $request, EntityManagerInterface $em): Response
    {
        $new = false;
        if (!$faq) {
            $faq = new faq();
            $new = true;
        }
        $form = $this->createForm(FAQType::class, $faq );
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $faq = $form->getData();
            $em->persist($faq);
            $em->flush();
            if ($new) {
                $this->addFlash('success','Question et réponse ajoutées');
            }else{
                $this->addFlash('success','Question/réponse mis-à-jour');
            }
            return $this->redirectToRoute("nationSound_contenu_actualite");
        }
        return $this->render('admin/NationSound/contenu/addFAQ.html.twig', [
            'form'=>$form
        ]);
    }

    #[Route('/actualite/withdraw/article/{id?}', name:'contenu_actualite_article_withdraw')]
    public function withdrawArticle(Article $article=null, EntityManagerInterface $em):Response
    {
        $article->setRelatedEvent(null);
        $em->persist($article);
        $em->flush();
        $this->addFlash('success','Article retiré');
        return $this->redirectToRoute('nationSound_contenu_actualite');
    }

    #[Route('/actualite/delete/{id?}', name: 'contenu_actualite_delete')]
    public function informationDelete(FAQ $faq=null, EntityManagerInterface $em): RedirectResponse
    {
        if ($faq) {
            $em->remove($faq);
            $em->flush();
            $this->addFlash('success','question/reponse supprimer');
            return $this->redirectToRoute('nationSound_contenu_actualite');
        }else{
            $this->addFlash('error','Une erreur est apparu');
            return $this->redirectToRoute('nationSound_contenu_actualite');
        }
    }

    // Controller pour la page sponsor
    #[Route('/sponsor', name: 'contenu_sponsor')]
    public function sponsor(ViewRepository $vw, SponsorRepository $sr, EventRepository $er): Response
    {
        //on récupère la page qui a pour name sponsor
        $page = $vw->findOneBy(['name'=>'sponsor']);
        //on recupère les sponsors qui sponsorise l'évnement avec le nom Nations Sound
        $nationSound = $er->findOneBy(['name'=>'Nation Sound']);
        $sponsors = $sr->findBy(['event'=>$nationSound->getId()]);
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'pageDefaultName' => 'sponsor',
            'title' => 'Sponsors',
            'page' => $page,
            'sponsors' => $sponsors,
        ]);
    }

    #[Route('/sponsor/edit/{id?}', name: 'contenu_sponsor_edit')]
    public function sponsorEdit(Sponsor $sponsor=null, Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $new = false;
        if (!$sponsor) {
            $sponsor = new Sponsor();
            $new = true;
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
            if ($new) {
                $this->addFlash('success','Sponsor crée');
            }else{
                $this->addFlash('success','Sponsor mis-à-jour');
            }
            return $this->redirectToRoute('nationSound_contenu_sponsor');
        }
        return $this->render('admin/NationSound/contenu/addSponsor.html.twig', [
            'sponsor' => $sponsor,
            'new' => $new,
            'form' => $form
        ]);
    }

    #[Route('/sponsor/{id?}', name: 'contenu_sponsor_details')]
    public function sponsorDetails(Sponsor $sponsor=null, SponsorRepository $sr): Response
    {
        $sponsor = $sr->findOneBy(['id'=>$sponsor]);
        return $this->render('admin/NationSound/contenu/pagedetails.html.twig', [
            'data' => $sponsor,
            'folder' => 'assets/uploads/sponsors/',
            'file' => $sponsor->getLogo(),
        ]);
    }

    #[Route('/sponsor/delete/{id?}', name: 'contenu_sponsor_delete')]
    public function sponsorDelete(Sponsor $sponsor=null, EntityManagerInterface $em, PictureService $pictureService): RedirectResponse
    {
        if ($sponsor) {
            $images = $sponsor->getImageSponsors();
            $featuredImage = $sponsor->getLogo();
            $pictureService->deleteAllsImages($images, $featuredImage, 'sponsors');
            $em->remove($sponsor);
            $em->flush();
            $this->addFlash('success','Sponsor supprimer');
            return $this->redirectToRoute('nationSound_contenu_sponsor');
        }else{
            $this->addFlash('error','Une erreur est apparu');
            return $this->redirectToRoute('nationSound_contenu_sponsor');
        }
    }

    // Controller pour la page a propos contact
    #[Route('/apropos', name: 'contenu_a-propos')]
    public function apropos(ViewRepository $vw): Response
    {
        //on récupère la page qui a pour name sponsor
        $page = $vw->findOneBy(['name'=>'a-propos']);
        return $this->render('admin/NationSound/contenu/page.html.twig', [
            'pageDefaultName' => 'a-propos',
            'title' => 'a-propos',
            'page' => $page,
        ]);
    }

    // Controller pour l'edition d'une page
    #[Route('/contenu/edit/{name?}/{id?}', name: 'contenu_edit')]
    public function edit(View $view=null, $name=null, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
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
            //attribue le nom de la page
            $view->setName($name);
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
            'view' => $view,
            'name' => $name
        ]);
    }

    // Controler pour les sections supplementaires d'une page
    #[Route('/pagesection/edit/{name?}/{id?}', name: 'contenu_edit_section')]
    public function editSection(PageSection $pageSection=null, $name=null, Request $request, ViewRepository $vr, PictureService $pictureService ,EntityManagerInterface $em): Response
    {
        $page = $vr->findOneBy(['name'=>$name]);
        $slug = $page->getSlug();
        $new = false;
        if (!$pageSection) {
            $pageSection = new PageSection();
            $new = true;
        }
        $form = $this->createForm(PageSectionType::class, $pageSection);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $pageSection = $form->getData();
            $pageSection->setView($page);
            //on ajoute les images
            $folder = 'figure';
            $images = $form->get('images')->getData();
            foreach ($images as $image) { 
                $fichier = $pictureService->addFeaturedImage($image, $folder);
                $img = new Figure();
                $img->setName($fichier);
                $pageSection->addImage($img);
                $em->persist($img);
            }
            $em->persist($pageSection);
            $em->flush();
            if ($new) {
                $this->addFlash('success','Nouvelle section ajouté à la page');
            } else {
                $this->addFlash('success','Section mis-à-jour');
            }
            return $this->redirectToRoute("nationSound_contenu_$slug", [
            ]);
        }
        return $this->render('admin/NationSound/contenu/editSection.html.twig', [
            'form' => $form,
            'name' => $name,
            'new' => $new,
            'section' => $pageSection
        ]);
    }
    #[Route('/pagesection/delete/image/{pagename}/{id}', name:'contenu_delete_section_image', methods:['DELETE'])]
    public function deleteImage(Figure $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'figure')){
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
    
    #[Route('/pagesection/delete/{name?}/{id?}', name: 'contenu_edit_section_delete')]
    public function deleteSection(PageSection $pageSection=null, ViewRepository $vr, EntityManagerInterface $em, $name=null): RedirectResponse
    {
        $page = $vr->findOneBy(['name'=>$name]);
        $slug = $page->getSlug();
        if ($pageSection) {
            $em->remove($pageSection);
            $em->flush();
            $this->addFlash('success','Section supprimer');
            return $this->redirectToRoute("nationSound_contenu_$slug");
        }
        $this->addFlash('danger','Une erreur est survenu');
        return $this->redirectToRoute("nationSound_contenu_$slug");
        
    }
    

}
