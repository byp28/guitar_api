<?php

namespace App\Controller;


use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CategorieController extends AbstractController
{
    #[Route('/api/categorie', name: 'categorie.index', methods: ["GET"])]
    public function index(Request $request, CategorieRepository $repository )
    {
        $categories = $repository->findAll();
        return $this->json($categories, 200, [],[
            "groups"=> ["categorie.index"]
        ]);
    }

    #[Route('/api/categorie', name: 'categorie.create', methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $em )
    {
        
        $categorie = new Categorie();
        $img_file = $request->files->get("imgFile");
    
        $fileName = "cat".time().".png";

        $img_file->move($this->getParameter("kernel.project_dir")."/public/img/categorie",$fileName);

        $categorie->setDesignation($request->getPayload()->get("designation"));
        $categorie->setImg($fileName);

        // $serializer->deserialize($request->getContent(), Categorie::class, 'json', [
        //     AbstractNormalizer::OBJECT_TO_POPULATE => $categorie,
        //     "groups" => ['categorie.create']
        // ]);

        //dd($user);
        $em->persist($categorie);
        $em->flush();

        return $this->json([
            "message"=> "categorie created",
            "code"=> 201,
        ], 201);

        //dd($request->getPayload()); 
        //dd($serializer->deserialize($request->getContent(), User::class, 'json'));
    }

    #[Route('/api/categorie/{id}', name: 'categorie.edit', methods: ["POST"])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $em )
    {
        if($request->files->get("imgFile")){
            if(file_exists($this->getParameter("kernel.project_dir")."\\public\\img\\categorie\\".$categorie->getImg())){
                unlink($this->getParameter("kernel.project_dir")."\\public\\img\\categorie\\".$categorie->getImg());
            }
            $img_file = $request->files->get("imgFile");
            $fileName = "cat".time().".png";
            $img_file->move($this->getParameter("kernel.project_dir")."/public/img/categorie",$fileName);
            $categorie->setImg($fileName);
        }
        
        $categorie->setDesignation($request->getPayload()->get("designation"));
        

        $em->persist($categorie);
        $em->flush();

        return $this->json([
            "message"=> "categorie edited",
            "code"=> 201,
        ], 201);
    }

    #[Route('/api/categorie/{id}/delete', name: 'categorie.delete', methods: ["POST"])]
    public function remove(EntityManagerInterface $em, Categorie $categorie)
    {
        if(file_exists($this->getParameter("kernel.project_dir")."\\public\\img\\categorie\\".$categorie->getImg())){
            unlink($this->getParameter("kernel.project_dir")."\\public\\img\\categorie\\".$categorie->getImg());
        }
        $em->remove($categorie);
        $em->flush();
        return $this->json([
            "message"=> "categorie deleted",
            "code"=> 200,
        ], 200);

    }


}
