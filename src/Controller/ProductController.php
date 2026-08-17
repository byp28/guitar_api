<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Entity\Product;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class ProductController extends AbstractController
{


    #[Route('/api/product', name: 'product.index', methods: ["GET"])]
    public function index(Request $request, ProductRepository $repository )
    {
        $products = $repository->indexWithCategorieAndSubCategorie();
        return $this->json($products, 200);
    }

    #[Route('/api/product/{name}', name: 'product.search', methods: ["GET"])]
    public function search(string $name, ProductRepository $repository )
    {
        $products = $repository->findByName($name);
        return $this->json($products, 200);
    }

    #[Route('/api/product', name: 'product.create', methods: ["POST"])]
    public function create(Request $request, ProductRepository $repo, EntityManagerInterface $em )
    {
        $product = new Product();
        $img_file = $request->files->get("imgFile");

        $fileName = "cat".time().".png";

        $img_file->move($this->getParameter("kernel.project_dir")."/public/img/product",$fileName);

        $product->setNom($request->getPayload()->get("nom"));
        $product->setDescription($request->getPayload()->get("description"));
        $product->setPrice($request->getPayload()->get("price"));
        $product->setDescriptionTechnique($request->getPayload()->get("description_technique"));
        $product->setCategorie($em->getRepository(Categorie::class)->find($request->getPayload()->get("id_categorie")));
        $product->setSousCategorie($em->getRepository(SousCategorie::class)->find($request->getPayload()->get("id_sous_categorie")));
        $product->setImage($fileName);


        $em->persist($product);
        $em->flush();

        return $this->json([
            "message"=> "product created",
            "code"=> 201,
        ], 201);

    }

    #[Route('/api/product/{id}', name: 'product.edit', methods: ["POST"])]
    public function edit(Request $request, Product $product, ProductRepository $repo, EntityManagerInterface $em )
    {


        if($request->files->get("imgFile")){
            if(file_exists($this->getParameter("kernel.project_dir")."\\public\\img\\product\\".$product->getImage())){
                unlink($this->getParameter("kernel.project_dir")."\\public\\img\\product\\".$product->getImage());
            }
            $img_file = $request->files->get("imgFile");

            $fileName = "cat".time().".png";

            $img_file->move($this->getParameter("kernel.project_dir")."/public/img/product",$fileName);
            $product->setImage($fileName);
        }

        $product->setNom($request->getPayload()->get("nom"));
        $product->setDescription($request->getPayload()->get("description"));
        $product->setPrice($request->getPayload()->get("price"));
        $product->setDescriptionTechnique($request->getPayload()->get("description_technique"));
        $product->setCategorie($em->getRepository(Categorie::class)->find($request->getPayload()->get("id_categorie")));
        $product->setSousCategorie($em->getRepository(SousCategorie::class)->find($request->getPayload()->get("id_sous_categorie")));
        


        $em->persist($product);
        $em->flush();

        return $this->json([
            "message"=> "product edited",
            "code"=> 201,
        ], 201);

    }

    #[Route('/api/product/{id}/delete', name: 'product.delete', methods: ["POST"])]
    public function remove(EntityManagerInterface $em,Product $product)
    {
        if(file_exists($this->getParameter("kernel.project_dir")."\\public\\img\\product\\".$product->getImage())){
            unlink($this->getParameter("kernel.project_dir")."\\public\\img\\product\\".$product->getImage());
        }
        $em->remove($product);
        $em->flush();
        return $this->json([
            "message"=> "product deleted",
            "code"=> 200,
        ], 200);

    }

}
