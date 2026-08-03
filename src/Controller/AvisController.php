<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class AvisController extends AbstractController
{
    #[Route('/api/avis',  name: 'avis.index', methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
        ]);
    }

    #[Route('/api/avis', name: 'avis.create', methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $em )
    {
        $avis = new Avis();
        $avis->setContenu($request->getPayload()->get("data"));
        $avis->setUser($em->getRepository(User::class)->find($request->getPayload()->get("id_user")));
        $avis->setProduct($em->getRepository(Product::class)->find($request->getPayload()->get("id_product")));
        $avis->setLikes(0);
        $avis->setDisLikes(0);


        $em->persist($avis);
        $em->flush();

        return $this->json([
            "message"=> "avis created",
            "code"=> 201,
        ], 201);

    }

    #[Route('/api/avis/{id}', name: 'avis.edit', methods: ["POST"])]
    public function edit(Request $request, Avis $Avis, EntityManagerInterface $em )
    {
        $Avis->setContenu($request->getPayload()->get("data"));
        $em->persist($Avis);
        $em->flush();

        return $this->json([
            "message"=> "comment edite",
            "code"=> 201,
        ], 201);
    }

    #[Route('/api/avis/{id}/delete', name: 'avis.delete', methods: ["POST"])]
    public function remove(EntityManagerInterface $em, Avis $avis)
    {
        $em->remove($avis);
        $em->flush();
        return $this->json([
            "message"=> "avis deleted",
            "code"=> 202,
        ], 200);

    }

    #[Route('/api/avis/product/{id}', name: 'avis.product', methods: ["POST"])]
    public function getAvisByProduct(int $id,Request $request, AvisRepository $repository,EntityManagerInterface $em): Response
    {
        $avis = $repository->findByProduct($id);
        return $this->json($avis, 200);
    }

}
