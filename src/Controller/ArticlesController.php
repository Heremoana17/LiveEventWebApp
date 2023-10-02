<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles', name:'app_')]
class ArticlesController extends AbstractController
{
    #[Route('/{slug}', name:'article_details')]
    public function articleDetails(Article $article, Request $request, EntityManagerInterface $em):Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted()&&$commentForm->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setRelatedArticle($article);
            $comment = $commentForm->getData();
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'commentaire ajoutée');
            return $this->redirectToRoute('app_article_details',['slug' => $article->getSlug()]);
        }
        return $this->render('articles/article_details.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm
        ]
        );
    }
}
