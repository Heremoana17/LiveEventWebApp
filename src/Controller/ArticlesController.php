<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles', name:'app_')]
class ArticlesController extends AbstractController
{
    #[Route('/{slug}', name:'article_details')]
    public function articleDetails(Article $article):Response
    {
        return $this->render('articles/article_details.html.twig',
            ['article' => $article]
        );
    }
}
