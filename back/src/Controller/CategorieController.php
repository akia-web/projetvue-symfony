<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class CategorieController extends AbstractController
{
 
    /**
     * @Route("api/categories", methods={"POST"})
    */
    public function createCategorie(HttpFoundationRequest $request, ManagerRegistry $mr){
        $manager = $mr->getManager();
        $categorie = new Categories;
        $requestCategorie = json_decode($request->getContent());
       

        $categorie->setName($requestCategorie->{'name'});
        

        $manager->persist($categorie);
        $manager->flush();

        $newCategorie = new stdClass();
        $newCategorie->id = $categorie->getId();
        $newCategorie->name = $categorie->getName();
       

        return new Response(json_encode($newCategorie) , Response::HTTP_OK);

    }


    /**
     * @Route("api/categories", methods={"GET"})
    */
    public function getAllCategories(CategoriesRepository $repo){
        $findAllCategorie = $repo->findAll();
        // dd($findAllCategorie);
        $result = [];

        for($i = 0; $i<count($findAllCategorie); $i++){
            $categorie = new stdClass(); // {}
            $categorie->id = $findAllCategorie[$i]->getId();  // id : 1
            $categorie->name = $findAllCategorie[$i]->getName();
            array_push($result, $categorie);
        }

        return new Response(json_encode($result), Response::HTTP_OK);
    }

    /**
     * @Route("api/categories/{id}", methods={"DELETE"})
    */
    public function deleteCategory(ManagerRegistry $mr, Categories $categorie){
        $em = $mr->getManager();
        $em->remove($categorie);
        $em->flush();
 
        return new Response('ok', Response::HTTP_OK);

    }

    /**
     * @Route("api/categories/{id}", methods={"PUT"})
    */
    public function modifyCategoryName(ManagerRegistry $mr, HttpFoundationRequest $request, int $id,
     CategoriesRepository $repo ){
        $manager = $mr->getManager();
        $categorie = $repo->find($id);
        $requestCategorie = json_decode($request->getContent());
        $categorie->setName($requestCategorie->{'name'});

        $manager->persist($categorie);
        $manager->flush();
        
        return new Response('ok', Response::HTTP_OK);

    }
}
