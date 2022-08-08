<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\Persistence\ManagerRegistry;
use LDAP\Result;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class ArticlesController extends AbstractController
{

    /**
     * @Route("api/articles", methods={"POST"})
    */
    public function createArticle(HttpFoundationRequest $request, ManagerRegistry $mr, CategoriesRepository $categoriesRepository){
        $manager = $mr->getManager();
        $article = new Articles();
        $requestArticle = json_decode($request->getContent());             
        $categorie = $categoriesRepository->findBy(array('name'=>$requestArticle->{'categorie'}));
       

        $article->setTitle($requestArticle->{'titre'});
        $article->setDescription($requestArticle->{'description'});
        $article->setCategorie($categorie[0]);
        $manager->persist($article);
        $manager->flush();

        $newArticle = new stdClass();
        $newArticle->id = $article->getId();
        $newArticle->titre = $article->getTitle();
        $newArticle->description = $article->getDescription();
        $newArticle->categorie = $article->getCategorie()->getName();
      


        return new Response(json_encode($newArticle), Response::HTTP_OK);

    }

    /**
     * @Route("api/articles", methods={"GET"})
    */
    public function getArticles(ArticlesRepository $repo){
        $findAllArticles = $repo->findAll();
        // dd($findAllArticles);
        $result = [];

        for($i = 0; $i<count($findAllArticles); $i++){
            $article = new stdClass(); // {}
            $article->id = $findAllArticles[$i]->getId();  // id : 1
            $article->titre = $findAllArticles[$i]->getTitle();
            $article->description = $findAllArticles[$i]->getDescription();
            $article->categorie = $findAllArticles[$i]->getCategorie()->getName();
            array_push($result, $article);
        }

        return new Response(json_encode($result), Response::HTTP_OK);
    }

    /**
     * @Route("api/articles/{id}", methods={"PUT"})
    */

    public function modificationArticle(HttpFoundationRequest $request, ManagerRegistry $mr, ArticlesRepository $repoArticle,
     CategoriesRepository $repoCategorie, int $id){
        $manager = $mr->getManager();
        $requestArticle = json_decode($request->getContent());
        $article = $repoArticle->find($id);
        $categorie = $repoCategorie->findBy(array('name' => $requestArticle->{"categorie"}));

        $article->setTitle($requestArticle->{'titre'});
        $article->setDescription($requestArticle->{'description'});
        $article->setCategorie($categorie[0]);
        $manager->persist($article);
        $manager->flush();
        return new Response('ok', Response::HTTP_OK);

     }
    
}
