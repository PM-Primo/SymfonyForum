<?php

namespace App\Controller;

use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="app_categorie")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories  = $doctrine->getRepository(Categorie::class)->findBy([],["id"=>"ASC"]);

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="show_categorie")
     */
    public function show(Categorie $categorie, Request $request, PaginatorInterface $paginator): Response
    {
        $topics=$paginator->paginate($categorie->getTopics(), $request->query->getInt("page", 1), 5);
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'topics' => $topics
        ]);
    }
}
