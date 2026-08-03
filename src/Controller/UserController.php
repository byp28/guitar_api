<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class UserController extends AbstractController
{
    #[Route('/api/user', name: 'user.index', methods: ["GET"])]
    public function index(Request $request, UserRepository $repository )
    {
        $users = $repository->findAll();
        return $this->json($users, 200, [],[
            "groups"=> ["user.index"]
        ]);
    }

    #[Route('/api/user/{id}', name: 'user.show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Request $request, int $id, UserRepository $repository )
    {
        $user = $repository->find($id);
        if($user){
            return $this->json($user, 200, [],[
                "groups"=> ["user.index", "user.show"]
            ]);
        }
         
        return $this->json([
            "message" => "user not found",
            "code"=> 404
        ], 404);
    }

    #[Route('/api/auth/login', name: 'auth.login', methods: ['POST'])]
    public function login(Request $request, UserRepository $repository, SerializerInterface $serializer, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $em )
    {
        $user = $repository->findOneByEmail($request->getPayload()->get("email"));

        if(!$user){
            return $this->json([
                "message" => "user not found",
                "code"=> 404
            ], 404);
        }
         
        if($user->getPassword() != $request->getPayload()->get("password")){
            return $this->json([
                "message" => "user not found",
                "code"=> 404
            ], 404);
        }

        $token = $jwtManager->create($user);

        return $this->json([
            'user' => [
                "id" => $user->getId(),
                "nom"=> $user->getNom(),
                "email"=> $user->getEmail(),
                "type"=> $user->getType(),
                'token' => $token,
            ]
        ]);
    }

    #[Route('/api/user/{id}', name: 'user.show', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(int $id, Request $request, UserRepository $repository, SerializerInterface $serializer, EntityManagerInterface $em )
    {
        $user = $repository->find($id);
        if($user){

            $serializer->deserialize($request->getContent(), User::class, 'json', [
                AbstractNormalizer::OBJECT_TO_POPULATE => $user,
                "groups" => ['user.edit']
            ]);

            $em->persist($user);
            $em->flush();
            
            return $this->json([
                "message" => "user updated",
                "code"=> 201
            ], 201);
        }
         
        return $this->json([
            "message" => "user not found",
            "code"=> 404
        ], 404);
    }




    #[Route('/api/auth/passport', name: 'auth.passport', methods: ['POST'])]
    public function passport(
        Request $request,
        JWTEncoderInterface $jwtEncoder,
        UserRepository $userRepository
    ){

        $header = $request->headers->get('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return $this->json([
                'message' => 'Token manquant'
            ], 200);
        }

        $token = substr($header, 7);

        try {

            $payload = $jwtEncoder->decode($token);

            if (!$payload) {
                return $this->json([
                    'message' => 'Token invalide'
                ], 200);
            }

            $user = $userRepository->findOneByEmail($payload['username']);

            if (!$user) {
                return $this->json([
                    'message' => 'Utilisateur introuvable'
                ], 404);
            }

            return $this->json([
                'user' => [
                    "id" => $user->getId(),
                    "nom"=> $user->getNom(),
                    "email"=> $user->getEmail(),
                    "type"=> $user->getType(),
                    "roles"=> $user->getRoles(),
                    'token' => $token,
                ]
            ]);

        } catch (\Exception $e) {

            return $this->json([
                'message' => 'Token invalide ou expiré'
            ], 200);
        }
    }



    #[Route('/api/user', name: 'user.create', methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $em )
    {
        $user = new User();
        $user->setType("Client");
        $user->setNom($request->getPayload()->get("nom"));
        $user->setEmail($request->getPayload()->get("email"));
        $user->setPassword($request->getPayload()->get("password"));

        //dd($user);
        $em->persist($user);
        $em->flush();
        return $this->json([
            "message"=> "user created",
            "code"=> 201,
        ], 201);

        //dd($request->getPayload()); 
        //dd($serializer->deserialize($request->getContent(), User::class, 'json'));
    }

    #[Route('/api/user/{id}/adresse', name: 'user.adresse.create', methods: ["POST"])]
    public function createNewAdresse(Request $request, User $user, EntityManagerInterface $em )
    {
        $adresse = new Adresse();
        $adresse->setCode($request->getPayload()->get("postal"));
        $adresse->setNumeros($request->getPayload()->get("number"));
        $adresse->setRue($request->getPayload()->get("rue"));
        $adresse->setVille($request->getPayload()->get("ville"));
        $adresse->setCountry($request->getPayload()->get("country"));
        $adresse->setComplement($request->getPayload()->get("complement"));

        $em->persist($adresse);
        $em->flush();

        $user->setAdresses($em->getRepository(Adresse::class)->find($adresse->getId()));

        $em->persist($user);
        $em->flush();
        return $this->json([
            "message"=> "adresse created",
            "code"=> 201,
        ], 201);
    }

    
    #[Route('/api/user/{id}/adresse', name: 'user.adresse', methods: ["GET"])]
    public function getAdresse(Adresse $adresse)
    {
       

        return $this->json([
            "id"=>$adresse->getId(),
            "rue"=> $adresse->getRue(),
            "code"=> $adresse->getCode(),
            "ville"=> $adresse->getVille(),
            "country"=> $adresse->getCountry(),
            "complement"=> $adresse->getComplement(),
        ], 201);
    }
}
