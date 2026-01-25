<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
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

    #[Route('/api/user', name: 'user.create', methods: ["POST"])]
    public function create(Request $request, UserRepository $repository, SerializerInterface $serializer )
    {
        dd($request->getPayload()); 
        //dd($serializer->deserialize($request->getContent(), User::class, 'json'));
    }
}
