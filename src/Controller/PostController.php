<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="app_post")
     */
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/post/{id}/delete", name="delete_post")
     * @IsGranted("ROLE_USER")
     */
    public function delete(ManagerRegistry $doctrine, Post $post): Response
    {
        
        if($post->getAuteur()->getId() == $this->getUser()->getId()){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }
        
        $id = $post->getTopic()->getId();
        return $this->redirectToRoute('show_topic', ['id' => $id]);
    }


}
