<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController{

    #[Route('/article', name: 'app_article')]

    public function index(EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'titre' => 'Article',
            'article' => $article,
        ]);
    }


    #[Route('/article/creer', name: 'app_article_create')]

    public function create(EntityManagerInterface $entityManager , Request $request) : Response 
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Votre article N°' . $article->getID() . ' est bien créer');
        }

        return $this->render('article/creer.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/article/update/{id}', name: 'app_article_update')]

    public function update(EntityManagerInterface $entityManager, int $id , Request $request): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Votre article N°' . $article->getID() . ' est bien modifié');
        }

        return $this->render('article/update.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/article/delete/{id}', name: 'app_article_delete')]

    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_article');
    }
}
