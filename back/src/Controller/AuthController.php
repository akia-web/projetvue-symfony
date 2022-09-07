<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    // #[Route('/auth', name: 'app_auth')]
    // public function index(): Response
    // {
    //     return $this->render('auth/index.html.twig', [
    //         'controller_name' => 'AuthController',
    //     ]);
    // }
 
    /**
     * @Route("api/inscription", methods={"POST"})
    */
    public function inscription (HttpFoundationRequest $request, ManagerRegistry $mr, UserPasswordHasherInterface $passwordHash, UserRepository $repo){

        $requestUser = json_decode($request->getContent());
        $password = $requestUser->{'password'};
        $email = $requestUser->{'email'};

        // isset = est ce que cela existe
        if(!isset($email)){
            return new Response('email non renseigne', Response::HTTP_BAD_REQUEST); // Renvoi une erreur
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ // filter valide email a la meme utiliter que regex
            return new Response("email non valide", Response::HTTP_BAD_REQUEST);
        }elseif(!isset($password)){
            return new Response("Mon de passe non renseigne", Response::HTTP_BAD_REQUEST);
        }

        // verifier si un utilisateur possede deja un compte avec l'adresse email

        $findUser = $repo->findOneBy(['email' => $email]); // Si utilisateur existe, je le recupere Sinon il me donne un user vide
        if($findUser != null){
            return new Response (" Adresse mail déjà utilise", Response::HTTP_BAD_REQUEST);
        }

        $manager = $mr->getManager();
        $user = new User();

        $hashPassword = $passwordHash->hashPassword($user, $password);
        $user->setEmail($email);
        $user->setPassword($hashPassword);
        $user->setRoles(['user']); // je definie le role de mon utilisateur

        $manager->persist($user);
        $manager->flush();

        return new Response('ok', Response::HTTP_OK);
     }

    /**
     * @Route("api/connexion", methods={"POST"})
    */
    public function connexion(UserRepository $repo, HttpFoundationRequest $request, ManagerRegistry $mr){
        
        $requestUser = json_decode($request->getContent());
        $password = $requestUser->{'password'};
        $email = $requestUser->{'email'};

        if(!isset($email)){
            return new Response('email non renseigné', Response::HTTP_BAD_REQUEST);
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return new Response("email invalide", Response::HTTP_BAD_REQUEST);
        }else if(!isset($password)){
            return new Response("mont de passe non renseigné", Response::HTTP_BAD_REQUEST);
        }

        $maybeUser = $repo->findOneBy(['email'=>$email]);

        if($maybeUser == null){ 
            return new Response('utilisateur non existant', Response::HTTP_NOT_FOUND);
        }

        $passwordVerify = password_verify($password, $maybeUser->getPassword());

        if(!$passwordVerify){
            return new Response('mot de passe incorrect', Response::HTTP_FORBIDDEN);
        }else{
            $token = uniqid();
            $manager = $mr->getManager();
            $maybeUser->setToken($token);
            $manager->persist($maybeUser);
            $manager->flush();

            return new Response($token, Response::HTTP_OK);
        }
    }

    /**
     * @Route("api/user", methods={"GET"})
     */
    public function getUserByToken(HttpFoundationRequest $request, UserRepository $repo){


        if($request->query->get("token") == null){
            return new Response("vous n'etes pas connecté", Response::HTTP_FORBIDDEN);
        }

        $maybeUser = $repo->findOneBy(['token'=> $request->query->get('token')]);
       
        if($maybeUser == null){
        return new Response("vous n'etes pas connecté");
        }  

        $infoUser = new stdClass();
        $infoUser->email = $maybeUser->getEmail();
        $infoUser->role = $maybeUser->getRoles()[0];
        $infoUser->image ="http://localhost:8000/uploads/profil/".$maybeUser->getImage();

        return new Response(json_encode($infoUser), Response::HTTP_OK);

    }

    /**
     * @Route("api/deconnexion", methods={"GET"})
     */
    public function deconnexion(HttpFoundationRequest $request, UserRepository $repo, ManagerRegistry $mr){
        if($request->query->get("token") == null){
            return new Response("vous n'etes pas connecter", Response::HTTP_BAD_REQUEST);
        }
        
        $user= $repo->findOneBy(['token'=> $request->query->get("token")]);

        if($user == null){
            return new Response("vous n'etes pas connecté", Response::HTTP_FORBIDDEN);
        }

        $user->setToken(null);
        $manager = $mr->getManager();
        $manager->persist($user);
        $manager->flush();

        return new Response('ok', Response::HTTP_OK);
    }

       /**
     * @Route("api/image-profil", methods={"POST"})
     */
    public function changeImageProfil (HttpFoundationRequest $request, UserRepository $repo, ManagerRegistry $mr){
    //    dd($request);
        $token = $request->request->get('token');
        $image = $request->files->get('image');
        
        $user = $repo->findOneBy(['token'=> $token]);
        if($user->getImage() != null){
            unlink($this->getParameter('image_profil')."/".$user->getImage()); 
        }
        $fichier = md5(uniqid()).'.'.$image->guessExtension();
        $image->move(
            $this->getParameter('image_profil'), $fichier
        );

        $user->setImage($fichier);

        $manager = $mr->getManager();
        $manager->persist($user);
        $manager->flush();

        return new Response("http://localhost:8000/uploads/profil/".$user->getImage(), Response::HTTP_OK);
    }
}
