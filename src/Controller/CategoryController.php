<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/categories', name:'app_'), IsGranted('ROLE_ADMIN')]
class CategoryController extends AbstractController
{

}
