<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;

final class AllController extends AbstractController
{
    #[Route('/all', name: 'app_all')]
    public function showall(Request $request, LivreRepository $rep, CategoryRepository $categoryRepository): Response
    {
        $searchTerm = $request->query->get('search'); // Nouveau paramètre de recherche
        $categoryFilter = $request->query->get('category_filter');
        
        // Utilisez une nouvelle méthode du repository pour gérer les deux filtres
        $livres = $rep->findByFilters($searchTerm, $categoryFilter);
        
        $categories = $categoryRepository->findAll();
        
        return $this->render('all/index.html.twig', [
            'livres' => $livres,
            'categories' => $categories,
            'current_search' => $searchTerm, // Pour pré-remplir le champ
        ]);
    }
    #[Route('/livre/detail/{id}', name: 'app_livre_detail')]
    public function showone(Livre $livre): Response
    {
        if (!$livre) {
            throw $this->createNotFoundException('Livre de l\'id {$livre->getId()} non trouvé');
        }
        return $this->render('all/detail.html.twig', [
            'livre' => $livre
        ]);
    }


}
