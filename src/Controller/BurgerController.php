<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Form\BurgerType;
use App\Repository\BurgerRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BurgerController extends AbstractController
{
    #[Route('/burger', name: 'app_burger')]
    public function index(BurgerRepository $burgerRepository): Response
    {
        return $this->render('burger/index.html.twig', [
            'burgers' => $burgerRepository->findAll(),
        ]);
    }

    #[Route('/burger/{id}', name: 'show_burger',priority: -1)]
    public function show(Burger $burger,CommentRepository $commentRepository): Response
    {


        return $this->render('burger/show.html.twig', [
            "burger"=>$burger,
            "comments"=>$commentRepository->findAll()
        ]);
    }

    #[Route('burger/create',name: 'create_burger')]
    public function create(Request $request,EntityManagerInterface $manager):Response
    {
        $burger = new Burger();
        $formulaire = $this->createForm(BurgerType::class,$burger);
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $manager->persist($burger);

            $manager->flush();

            return $this->redirectToRoute('show_burger', ["id"=>$burger->getId()]);
        }


        return $this->render("burger/create.html.twig",[
            "formulaire"=>$formulaire->createView()
        ]);
    }

    #[Route('burger/delete/{id}',name: 'delete_burger')]
    public function delete(Burger $burger,EntityManagerInterface $manager)
    {
        $manager->remove($burger);
        $manager->flush();

        return $this->redirectToRoute("app_burger");
    }

    #[Route('burger/edit/{id}',name: 'edit_burger')]
    public function edit(Request $request,EntityManagerInterface $manager,Burger $burger):Response
    {

        $formulaire = $this->createForm(BurgerType::class,$burger);
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $manager->persist($burger);

            $manager->flush();

            return $this->redirectToRoute('show_burger', ["id"=>$burger->getId()]);
        }


        return $this->render("burger/create.html.twig",[
            "formulaire"=>$formulaire->createView()
        ]);
    }
}
