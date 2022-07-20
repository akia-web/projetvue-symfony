<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class ArticlesController extends AbstractController
{
    // #[Route('/articles', name: 'app_articles')]
    // public function index(): Response
    // {
    //     return $this->render('articles/index.html.twig', [
    //         'controller_name' => 'ArticlesController',
    //     ]);
    // }



    /**
     * @Route("api/articles", methods={"POST"})
    */
    public function createArticle(HttpFoundationRequest $request, ManagerRegistry $mr){
        $manager = $mr->getManager();
        $article = new Articles();
        $requestArticle = json_decode($request->getContent());
       

        $article->setTitle($requestArticle->{'titre'});
        $article->setDescription($requestArticle->{'description'});

        $manager->persist($article);
        $manager->flush();

        return new Response('ok', Response::HTTP_OK);

    }

    
}
