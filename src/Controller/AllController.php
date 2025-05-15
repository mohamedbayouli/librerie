<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;

final class AllController extends AbstractController
{
    #[Route('/all', name: 'app_all')]
    public function showall(Request $request, LivreRepository $rep, CategoryRepository $categoryRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $categoryFilter = $request->query->get('category_filter');
        $categoryId = null;
        $subCategoryId = null;

        if ($categoryFilter) {
            if (str_starts_with($categoryFilter, 'category_')) {
                $categoryId = (int) str_replace('category_', '', $categoryFilter);
            } elseif (str_starts_with($categoryFilter, 'subcategory_')) {
                $subCategoryId = (int) str_replace('subcategory_', '', $categoryFilter);
            }
        }
        
        $livres = $rep->findByFilters($searchTerm, $categoryId, $subCategoryId);
        
        $categories = $categoryRepository->findAll();
        
        return $this->render('all/index.html.twig', [
            'livres' => $livres,
            'categories' => $categories,
            'current_search' => $searchTerm,
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
