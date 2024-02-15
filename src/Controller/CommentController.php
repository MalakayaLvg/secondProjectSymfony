<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    #[Route('/burger/deleteComment/{id}',name: 'delete_comment')]
    public function delete(Comment $comment, EntityManagerInterface $manager): Response
    {
        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute("app_burger");
    }

    #[Route("burger/{id}/createComment",name: "create_comment")]
    public function create(Burger $burger,Request $request,EntityManagerInterface $manager):Response
    {
        $comment = new Comment();
        $comment->setBurger($burger);
        $formulaire = $this->createForm(CommentType::class,$comment);
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid()) {
            $manager->persist($comment);

            $manager->flush();
            return $this->redirectToRoute('show_burger', ["id"=>$burger->getId()]);
        }
            return $this->render("comment/create.html.twig",[
            "formulaire"=>$formulaire->createView()
        ]);
    }

}
