
<!-- Ajoutez une route permettant de faire une demande d’emprunt de livre 
Ajoutez une route permettant de rendre un livre 
Ajoutez une route permettant de savoir pour un utilisateur combien d’emprunts il a en 
cours (l’affichage sera trié par date de la plus ancienne à la plus récente) 

// Ajoutez une route pour récupérer tous les livres d’un auteur emprunté entre 2 dates 
// définies  -->
<?php
// src/Controller/LuckyController.php
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
//use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;




class EmpruntController extends AbstractController
{
     #[Route('/retourLivre/{idLivre}/{idUtilisateur}', name: 'retour_livre', methods: ['PATCH', 'PUT'])]
    public function retourLivre(EntityManagerInterface $entityManager, Request $request, int $idLivre, int $idUtilisateur): Response
    {
        try{
            $content = json_decode($request->getContent(), true);

            $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['livre' => $idLivre,'utilisateur' => $idUtilisateur]);
            if (!$emprunt) {
                return new JsonResponse(
                    ['message' => 'Aucun emprunt pour ce livre et cet utilisateur.'],
                    JsonResponse::HTTP_NOT_FOUND // 404
                );
            }
            $emprunt->setDateRetour(new DateTime());

            $entityManager->persist($emprunt);
            $entityManager->flush();

            return new Response('Emprunt édité'.$emprunt->getId());
        } catch (\Exception $e){
            return new JsonResponse(
                ['error' => 'Erreur serveur : '.$e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

    #[Route('/allEmpruntByUser/{idUser}', name: 'all_emprunt_by_user', methods: ['GET'])]
    public function allProduct(EntityManagerInterface $entityManager, Request $request, int $idUser): Response
    {
        $emprunt = $entityManager->getRepository(Emprunt::class)->findAllEmpruntById($idUser);
        $entityManager->flush();
        return new Response('Nombre d\'emprunts : '.$emprunt);
    }

}

