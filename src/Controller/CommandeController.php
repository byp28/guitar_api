<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CommandeProductRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class CommandeController extends AbstractController
{
    #[Route('/api/commande',  name: 'commande.index', methods: ["GET"])]
    public function index(CommandeRepository $cmd): Response
    {
        $commades = $cmd->findAllCommande();
        return $this->json($commades, 200);
    }

    #[Route('/api/commande/{id}/user',  name: 'commande.user', methods: ["GET"])]
    public function findCommandeUser(int $id,CommandeRepository $cmd, CommandeProductRepository $cmdr): Response
    {
        $commades = $cmd->findCommandeByUserId($id);
        $allcommandes = [];
        foreach($commades as $command){
            $products = $cmdr->findAllProductByCommandeId($command["id"]);
            $allcommandes[] = [
                "commande" => $command,
                "product" => $products
            ];
        }
        return $this->json($allcommandes, 200);
    }


    #[Route('/api/commande', name: 'commande.create', methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $em )
    {
        $commande = new Commande();

        $commande->setCommandeNumber(time());
        $commande->setStatus("en preparation");
        $commande->setUser($em->getRepository(User::class)->find($request->getPayload()->get("user_id")));
        $commande->setDateCmd(new \DateTime());

        $em->persist($commande);
        $em->flush();

        return $this->json([
            "id_cmd"=> $commande->getId(),
            "message"=> "commande created",
            "code"=> 201,
        ], 201);
    }

    #[Route('/api/commande/{id}/add', name: 'commande.add', methods: ["POST"])]
    public function addProductInCommande(Request $request, Commande $commande,  EntityManagerInterface $em){
        $commandeProduct = new CommandeProduct();

        $commandeProduct->setCommandeId($commande);
        $commandeProduct->setProductId($em->getRepository(Product::class)->find($request->getPayload()->get("product_id")));
        $commandeProduct->setQuantity($request->getPayload()->get("qte"));

        $em->persist($commandeProduct);
        $em->flush();

        return $this->json([
            "message"=> "product add",
            "code"=> 201,
        ], 201);

    }

    #[Route('/api/commande/{id}', name: 'commande.edit', methods: ["POST"])]
    public function editCommande(Request $request, Commande $commande,  EntityManagerInterface $em){
    
        $commande->setStatus($request->getPayload()->get("status"));

        $em->persist($commande);
        $em->flush();
        return $this->json([
            "message"=> "commande deleted",
            "code"=> 200,
        ], 200);
    }

    #[Route('/api/commande/{id}/delete', name: 'commande.delete', methods: ["POST"])]
    public function deleteCommande(Request $request, Commande $commande,  EntityManagerInterface $em){

        $em->remove($commande);
        $em->flush();
        return $this->json([
            "message"=> "commande deleted",
            "code"=> 200,
        ], 200);

    }

}
