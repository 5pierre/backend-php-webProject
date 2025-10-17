
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
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;




class EmpruntController extends AbstractController
{
    //route retour de livre
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
            $emprunt->setDateRetour(new \DateTime());

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

    //route nombre de livre emprunté par un utilisateur
    #[Route('/allEmpruntByUser/{idUser}', name: 'all_emprunt_by_user', methods: ['GET'])]
    public function allProduct(EntityManagerInterface $entityManager, Request $request, int $idUser): Response
    {
        $nbEmrpunts = $entityManager->getRepository(Emprunt::class)->findAllEmpruntById($idUser);
        return new Response('Nombre d\'emprunts en cours : '.$nbEmprunts);
    }

    //route demande d'emprunt
    #[Route('/demandeEmprunt/{idLivre}/{idUtilisateur}', name: 'demande_emprunt', methods: ['PATCH', 'PUT'])]
    public function empruntLivre(EntityManagerInterface $entityManager, Request $request, int $idLivre, int $idUtilisateur): Response
    {
        try{
            $content = json_decode($request->getContent(), true);

            $emprunts = $entityManager->getRepository(Emprunt::class)->findOneBy(['livre' => $idLivre,'utilisateur' => $idUtilisateur]);
           if (!$emprunts || $emprunts->getDateRetour() !== null) {
                $livre = $entityManager->getRepository(Livre::class)->find($idLivre);
                $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($idUtilisateur);

                if (!$livre || !$utilisateur) {
                    return new JsonResponse(['message' => 'Livre ou utilisateur introuvable'], 404);
                }

                $emprunt = new Emprunt();
                $emprunt->setDateEmprunt(new \DateTime());
                $emprunt->setDateRetour(null);
                $emprunt->setDateRetourSuppose($content['retourSuppose']);
                $emprunt->setLivre($livre);
                $emprunt->setUtilisateur($utilisateur);

                $entityManager->persist($emprunt);
                $entityManager->flush();

                return new Response('Emprunt créé'.$emprunt->getId());
            }else{
                return new JsonResponse(
                    ['message' => 'Ce livre est déjà emprunté.'],
                    JsonResponse::HTTP_NOT_FOUND // 404
                );
            }
            
        } catch (\Exception $e){
            return new JsonResponse(
                ['error' => 'Erreur serveur : '.$e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR 
            );
        }
    }

}

