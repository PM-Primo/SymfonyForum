<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Topic;
use App\Form\PostType;
use App\Form\TopicType;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TopicController extends AbstractController
{
    /**
     * @Route("/topic", name="app_topic")
     */
    public function index(): Response
    {
        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }


    // ANCIENNE FONCTION D'AJOUT + EDITION DE POSTS COMBINES (MAINTENANT SCINDEE EN 2)

    // /**
    //  * @Route("/topic/{idtopic}/respond2", name="respond2_topic")
    //  * @Route("/topic/{idtopic}/edit/{idpost}", name="edit_post")
    //  * @ParamConverter("topic", options={"mapping" : {"idtopic": "id"}})
    //  * @ParamConverter("post", options={"mapping": {"idpost": "id"}})
    //  * @IsGranted("ROLE_USER")
    //  */
    // public function respond2(ManagerRegistry $doctrine, Post $post = null, Topic $topic = null, Request $request): Response
    // {
    //     if(!$post){
    //         $post = new Post;
    //     }

    //     $form = $this->createForm(PostType::class, $post);
    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()){
            
    //         $textePost = $form->getData()->getTextePost();

    //         $post->setTextePost($textePost);
    //         $post->setTopic($topic);
    //         $post->setDatePost(new DateTime());
    //         $auteur = $this->getUser();
    //         // $auteur =  $doctrine->getRepository(User::class)->findOneBy(['id' => '1']);
    //         $post->setAuteur($auteur);

    //         $entityManager = $doctrine->getManager();
    //         $entityManager->persist($post); //équivalent de prepare()
    //         $entityManager->flush(); //équivalent de execute()

    //         return $this->redirectToRoute('show_topic', ['id' => $post->getTopic()->getId()]);
    //     }

    //     //Vue pour afficher le formulaire d'ajout
    //     return $this->render('post/add.html.twig', [
    //         'formAddPost' =>$form->createView(),
    //         'edit' => $post->getId()
    //     ]);

    // }


    /**
     * @Route("/topic/{id}/respond", name="respond_topic")
     * @IsGranted("ROLE_USER")
     */
    public function respond(ManagerRegistry $doctrine, Topic $topic, Request $request): Response
    {
        
        $post = new Post;
        
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $textePost = $form->getData()->getTextePost();

            $post->setTextePost($textePost);
            $post->setTopic($topic);
            $post->setDatePost(new DateTime());
            $auteur = $this->getUser();
            // $auteur =  $doctrine->getRepository(User::class)->findOneBy(['id' => '1']);
            $post->setAuteur($auteur);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($post); //équivalent de prepare()
            $entityManager->flush(); //équivalent de execute()

            return $this->redirectToRoute('show_topic', ['id' => $post->getTopic()->getId()]);
        }

        return $this->render('post/add.html.twig', [
            'formAddPost' =>$form->createView(),
            'edit' => $post->getId(),
            'topic' => $topic
        ]);

    }

    /**
     * @Route("post/{id}/edit", name="edit_post")
     * @IsGranted("ROLE_USER")
     */
    public function editPost(ManagerRegistry $doctrine, Post $post, Request $request): Response
    {

        if($post->getAuteur()->getId() == $this->getUser()->getId()){

            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                
                $textePost = $form->getData()->getTextePost();

                $post->setTextePost($textePost);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($post); //équivalent de prepare()
                $entityManager->flush(); //équivalent de execute()

                return $this->redirectToRoute('show_topic', ['id' => $post->getTopic()->getId()]);
            }

            //Vue pour afficher le formulaire d'ajout
            return $this->render('post/add.html.twig', [
                'formAddPost' =>$form->createView(),
                'edit' => $post->getId(),
                'topic' => $post->getTopic(),
            ]);
        }

        return $this->redirectToRoute('show_topic', ['id' => $post->getTopic()->getId()]);

    }



    /**
     * @Route("categorie/{id}/topic/add", name="add_topic")
     * @IsGranted("ROLE_USER")
     */
    public function add(ManagerRegistry $doctrine, Request $request, Categorie $categorie): Response
    {

        $topic = new Topic;
        
        $form = $this->createForm(TopicType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $entityManager = $doctrine->getManager();

            $titreTopic = $form->get("titreTopic")->getData();
            $texteFirstPost = $form->get("texteFirstPost")->getData();
            // $auteur =  $doctrine->getRepository(User::class)->findOneBy(['id' => '1']);
            $auteur = $this->getUser();
            $date = new DateTime();

            $topic = new Topic;
            $topic->setTitreTopic($titreTopic);
            $topic->setAuteur($auteur);
            $topic->setDateTopic($date);
            $topic->setCategorie($categorie);
            

            $entityManager->persist($topic); //équivalent de prepare()
            $entityManager->flush(); //équivalent de execute()
            
            $post = new Post;
            $post->setTextePost($texteFirstPost);
            $post->setAuteur($auteur);
            $post->setDatePost($date);
            $post->setTopic($topic);
            
            $entityManager->persist($post); 
            $entityManager->flush();

            return $this->redirectToRoute('show_topic', ['id' => $topic->getId()]);
        }

        //Vue pour afficher le formulaire d'ajout
        return $this->render('topic/add.html.twig', [
            'formAddTopic' =>$form->createView(),
            'categorie' => $categorie
        ]);

    }

    /**
     * @Route("topic/{id}/edit", name="edit_topic")
     * @IsGranted("ROLE_USER")
     */
    public function edit(ManagerRegistry $doctrine, Request $request, Topic $topic): Response
    {

        if($topic->getAuteur()->getId() == $this->getUser()->getId()){
            $posts = $topic->getPosts();
            $post=$posts[0];
            
    
            $form = $this->createForm(TopicType::class, $topic);
            $form->get('texteFirstPost')->setData($post->getTextePost());
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                
                $entityManager = $doctrine->getManager();
    
                $titreTopic = $form->get("titreTopic")->getData();
                $texteFirstPost = $form->get("texteFirstPost")->getData();
    
                $topic->setTitreTopic($titreTopic);           
    
                $entityManager->persist($topic); //équivalent de prepare()
                $entityManager->flush(); //équivalent de execute()
                
                $post->setTextePost($texteFirstPost);
    
                $entityManager->persist($post); 
                $entityManager->flush();
    
                return $this->redirectToRoute('show_topic', ['id' => $topic->getId()]);
            }
    
            //Vue pour afficher le formulaire d'ajout
            return $this->render('topic/add.html.twig', [
                'formAddTopic' =>$form->createView(),
                'edit' => $topic->getId(),
                'categorie' => $topic->getCategorie(),
            ]);
        }

        return $this->redirectToRoute('show_topic', ['id' => $topic->getId()]);
    }
    
    /**
     * @Route("/topic/{id}/delete", name="delete_topic")
     * @IsGranted("ROLE_USER")
     */
    public function delete(ManagerRegistry $doctrine, Topic $topic): Response
    {
        if($topic->getAuteur()->getId() == $this->getUser()->getId()){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($topic);
            $entityManager->flush();
        }
        
        $id = $topic->getCategorie()->getId();
        return $this->redirectToRoute('show_categorie', ['id' => $id]);
    }
    
    /**
     * @Route("/topic/{id}/lock", name="lock_topic")
     * @IsGranted("ROLE_USER")
     */
    public function lock(ManagerRegistry $doctrine, Topic $topic): Response
    {
        if($topic->getAuteur()->getId() == $this->getUser()->getId()){
            $topic->setVerrouTopic(true);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('show_categorie', ['id' => $topic->getCategorie()->getId()]);
    }
    
    /**
     * @Route("/topic/{id}/unlock", name="unlock_topic")
     * @IsGranted("ROLE_USER")
     */
    public function unlock(ManagerRegistry $doctrine, Topic $topic): Response
    {
        if($topic->getAuteur()->getId() == $this->getUser()->getId()){
            $topic->setVerrouTopic(false);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('show_categorie', ['id' => $topic->getCategorie()->getId()]);
    }

    /**
     * @Route("/topic/{id}/resolve", name="resolve_topic")
     * @IsGranted("ROLE_USER")
     */
    public function resolve(ManagerRegistry $doctrine, Topic $topic): Response
    {
        if($topic->getAuteur()->getId() == $this->getUser()->getId()){
            $topic->setResoluTopic(true);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('show_categorie', ['id' => $topic->getCategorie()->getId()]);
    }

    /**
     * @Route("/topic/{id}/unresolve", name="unresolve_topic")
     * @IsGranted("ROLE_USER")
     */
    public function unresolve(ManagerRegistry $doctrine, Topic $topic): Response
    {
        if($topic->getAuteur()->getId() == $this->getUser()->getId()){
            $topic->setResoluTopic(false);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('show_categorie', ['id' => $topic->getCategorie()->getId()]);
    }


    /**
     * @Route("/topic/{id}", name="show_topic")
     */
    public function show(Topic $topic, Request $request, PaginatorInterface $paginator): Response
    {

        $posts=$paginator->paginate($topic->getPosts(), $request->query->getInt("page", 1), 5);

        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
            'posts' => $posts,
        ]);
    }
}
