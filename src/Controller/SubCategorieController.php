<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class SubCategorieController extends AbstractController
{

    #[Route('/api/subCategorie', name: 'subcategorie.index', methods: ["GET"])]
    public function index(Request $request, SousCategorieRepository $repository )
    {
        $SousCategories = $repository->indexWithCategorie();
        return $this->json($SousCategories, 200);
    }

    #[Route('/api/subCategorie', name: 'subcategorie.create', methods: ["POST"])]
    public function create(Request $request, CategorieRepository $Crepo, EntityManagerInterface $em )
    {
        $SousCategorie = new SousCategorie();
        $img_file = $request->files->get("imgFile");

        $fileName = "cat".time().".png";

        $img_file->move($this->getParameter("kernel.project_dir")."/public/img/categorie",$fileName);
        $SousCategorie->setDesignation($request->getPayload()->get("designation"));
        $SousCategorie->setCategorie($em->getRepository(Categorie::class)->find($request->getPayload()->get("id_categorie")));
        $SousCategorie->setImg($fileName);


        $em->persist($SousCategorie);
        $em->flush();

        return $this->json([
            "message"=> "subcategorie created",
            "code"=> 201,
        ], 201);

    }

    #[Route('/api/subCategorie/{id}', name: 'subcategorie.edit', methods: ["POST"])]
    public function edit(Request $request, SousCategorie $Souscategorie, CategorieRepository $Crepo, EntityManagerInterface $em )
    {
        if($request->files->get("imgFile")){
            if(file_exists($this->getParameter("kernel.project_dir")."\\public\\img\\categorie\\".$Souscategorie->getImg())){
                unlink($this->getParameter("kernel.project_dir")."\\public\\img\\categorie\\".$Souscategorie->getImg());
            }

            $img_file = $request->files->get("imgFile");
            $fileName = "cat".time().".png";
            $img_file->move($this->getParameter("kernel.project_dir")."/public/img/categorie",$fileName);

            $Souscategorie->setImg($fileName);
        }

        $Souscategorie->setDesignation($request->getPayload()->get("designation"));
        $Souscategorie->setCategorie($em->getRepository(Categorie::class)->find($request->getPayload()->get("id_categorie")));
        

        $em->persist($Souscategorie);
        $em->flush();

        return $this->json([
            "message"=> "souscategorie edite",
            "code"=> 201,
        ], 201);
    }

    #[Route('/api/subCategorie/{id}/delete', name: 'subcategorie.delete', methods: ["POST"])]
    public function remove(EntityManagerInterface $em, SousCategorie $Souscategorie)
    {
        if(file_exists($this->getParameter("kernel.project_dir")."\\public\\img\\categorie\\".$Souscategorie->getImg())){
            unlink($this->getParameter("kernel.project_dir")."\\public\\img\\categorie\\".$Souscategorie->getImg());
        }
        $em->remove($Souscategorie);
        $em->flush();
        return $this->json([
            "message"=> "Subcategorie deleted",
            "code"=> 202,
        ], 200);

    }


    
}
