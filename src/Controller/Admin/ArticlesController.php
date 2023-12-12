<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Image;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\PictureService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/articles', name:'app_'), IsGranted('ROLE_ADMIN')]
class ArticlesController extends AbstractController{

    #[Route('/', name:'article')]
    public function allArticle(ArticleRepository $articlerepo, PaginatorInterface $paginatorInterface, Request $request):Response
    {
        $articles = $paginatorInterface->paginate(
            $articlerepo->findBy([],['created_at'=>'DESC']),
            $request->query->get('page', 1),
            5
        );
        return $this->render('admin/article/allArticles.html.twig', [
            'articles' => $articles,
            ]
        );
    }

    #[Route('/mine', name:'article_mine')]
    public function allmineArticle(ArticleRepository $articlesrepo, PaginatorInterface $paginatorInterface, Request $request):Response
    {
        $author=$this->getUser()->getId();
        $articles = $paginatorInterface->paginate(
            $articlesrepo->findby(
                ["author" => $author],
                ['created_at' => 'DESC']
            ),
            $request->query->get('page', 1),
            5
        );
        return $this->render('admin/article/allMineArticles.html.twig',
            ['articles' => $articles]
        );
    }

    #[Route('/edit/{id?}', name: 'editArticle')]
    public function addArticle(ManagerRegistry $doctrine, Request $request, Article $article = null, PictureService $pictureService, SluggerInterface $slugger, SendMailService $mail, UserRepository $userRepository): Response
    {
        $new = false;
        if (!$article) {
            $article = new Article();
            $new = true;
        }
        $articleForm = $this->createForm(ArticleFormType::class, $article);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            //on definit le dossier de destination
            $folder = 'articles';
            //on recupère les images
            $images = $articleForm->get('images')->getData();
            foreach($images as $image){
                //on appelle le service d'ajout
                $fichier = $pictureService->addImages($image, $folder, 1024, 576);
                $img = new Image();
                $img->setName($fichier);
                $article->addImage($img);
            }
            $featuredImage = $articleForm->get('featuredImage')->getData();
            if($featuredImage){
                $newFileName = $pictureService->addFeaturedImage($featuredImage, $folder);
                $article->setFeaturedImage($newFileName);
            };
            $manager=$doctrine->getManager();
            $article = $articleForm->getData();
            //on génère le slug
            $slug = $slugger->slug($article->getTitle());
            $article->setSlug($slug);
            //on attribut l'autheur
            $article->setAuthor($this->getUser());
            //on persist
            $manager->persist($article);
            $manager->flush();
            //on envoie le mail aux abonnées
            $valider = $articleForm->get('Valider')->getData();
            if ($valider) {
                //on recupère les données du formulaire
                $Subject = $articleForm->get('Subject')->getData();
                $Content = $articleForm->get('Content')->getData();
                //on récupère les abonnées
                $abonnées = $userRepository->findBy(['isSubscriber' => true]);
                //on envoie le mail
                foreach ($abonnées as $abonnée) { 
                    $mail->send(
                        'admin@pixelevent.siter',
                        $abonnée->getEmail(),
                        $Subject,
                        'newletter',
                        [
                            'content' => $Content,
                            'abonnée' => $abonnée,
                            'article' => $article
                        ]
                    );
                }
                
            }
            if ($new === true) {
                $this->addFlash('success',"Nouvelle article crée et ajouter aux actualité");
            } else {
                $this->addFlash('success',"Article modifié et ajouter aux actualité");
            }
            return $this->redirectToRoute('app_article_mine');
        }
        return $this->render('admin/article/articles_form.html.twig', [
            'articleForm' => $articleForm->createView(),
            'new' => $new,
            'article' => $article
        ]);
    }

    #[Route('/delete/article/{id}', name:'deletArticle')]
    public function deletArticle(Article $article, EntityManagerInterface $em, PictureService $pictureService): RedirectResponse
    {
        $images = $article->getImages();
        $featuredImage = $article->getFeaturedImage();
        $pictureService->deleteAllsImages($images, $featuredImage, 'articles');
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('app_article_mine');
    }

    #[Route('/delete/image/{id}', name:'delete_article_image', methods:['DELETE'])]
    public function deleteImage(Image $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'articles')){
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
}