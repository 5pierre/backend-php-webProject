<?php

namespace App\Controller;
use DateTime;
 
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class BookController extends AbstractController
{

//CRUD


// crer un livre
    #[Route('/Book/create', name: 'create_Book', methods: ['POST'])]
    public function createBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $content = json_decode($request->getContent(), true);

        $product = new Livre();
        $product->setTitre($content["titre"]);
        $product->setDatePublication(new DateTime);
        $product->setDisponible($content["disponible"]);
        $product->setCategorie($content["categorie_id"]);
        $product->setUtilisateur($content["utilisateur_id"]);
        $product->setAuteur($content["auteur_id"]);

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('livre cree '.$product->getId());
    }

// recuperer les livre

        #[Route('/Book/GET', name: 'allBook', methods:['GET'])]
        public function allBook(EntityManagerInterface $entityManager, Request $request): Response
        {
        $products = $entityManager->getRepository(Livre::class)->findAll();

        return new JsonResponse($products, JsonResponse::HTTP_OK);

    }



// supprimer un livre
    #[Route('/Book/delet/{id}', name: 'delet_Book', methods:['DELETE'])]
    public function deletBook(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Livre::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();

        return new Response('deled Book with id ');
    }

// modifier un livre

    #[Route('/Book/update', name: 'update_Book', methods: ['PATCH','PUT'])]
    public function editBook(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $content = json_decode($request->getContent(), true);

        $product = $entityManager->getRepository(Livre::class)->find($id);
        
        // $product->setName($content['name']);
        // $product->setUnitPrice($content['prix']);
        // $product->setDescription($content['description']);
        // $product->setStock($content["stock"]);

        $product->setTitre($content["titre"]);
        $product->setDatePublication(new DateTime);
        $product->setDisponible($content["disponible"]);
        $product->setCategorie($content["categorie_id"]);
        $product->setUtilisateur($content["utilisateur_id"]);
        $product->setAuteur($content["auteur_id"]);


        $entityManager->persist($product);
        $entityManager->flush();

         return new Response(content: 'book édité'.$product->getId().$product->getTitre());


    }


}
