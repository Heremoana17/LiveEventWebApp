<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin/categories', name:'app_'), IsGranted('ROLE_ADMIN')]
class CategoryController extends AbstractController
{
    #[Route('/', name:'category')]
    public function allCategory(CategoryRepository $categoryRepository):Response
    {
        $categories = $categoryRepository->findBy(['parent'=>null]);
        return $this->render('admin/category/allCategories.html.twig',
            ['categories' => $categories]
        );
    }

    #[Route('/edit/{id?}', name:'category_form')]
    public function editCategory(Category $category=null, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger):Response
    {
        if (!$category) {
            $category = new Category();
        }
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) { 
            $manager = $doctrine->getManager();
            $category = $form->getData();
            //on génère le slug
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);
            // on persist et flush
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('app_category');
        }
        return $this->render('admin/category/categoryForm.html.twig',[
            'form' => $form
        ]);
    }
    #[Route('/delet/{id}', name:'category_delet')]
    public function deletCategory(ManagerRegistry $doctrine, Category $category,):RedirectResponse
    {
        $manager = $doctrine->getManager();
        $manager->remove($category);
        $manager->flush();
        return $this->redirectToRoute('app_category');
    }

    #[Route('/{id}/articles/{slug}', name:'listArticle')]
    public function listArticle(int $id, string $slug, ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginatorInterface):Response
    {
        
        $articles = $paginatorInterface->paginate(
            $articleRepository->pagination($id),
            $request->query->get('page', 1),
            5
        );
        return $this->render('admin/article/allArticles.html.twig',[
            'articles' => $articles,
            'slug' => $slug,
        ]);
    }
    #[Route('/{id}/evenements/{slug}', name:'listEvenement')]
    public function listEvenement(int $id, string $slug, EventRepository $eventRepository, Request $request, PaginatorInterface $paginatorInterface):Response
    {
        $events = $paginatorInterface->paginate(
            $eventRepository->pagination($id),
            $request->query->get('page', 1),
            5
        );
        return $this->render('admin/event/allEvents.html.twig',[
            'events' => $events,
            'slug' => $slug,
        ]);
    }
}