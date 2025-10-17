
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


}