<?php

namespace App\Controller;
use DateTime;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
use App\Entity\Utilisateur;

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


        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('livre cree '.$product->getId());
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


// recuperer les livre

}