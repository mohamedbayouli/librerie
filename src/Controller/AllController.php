<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Category;
use App\Entity\SubCategory;
use Psr\Log\LoggerInterface;
use App\Repository\LivreRepository;
use App\Service\GeminiBookSuggester;
use App\Repository\EmpruntRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AllController extends AbstractController
{
    #[Route('/all', name: 'app_all')]
    public function showall(Request $request, LivreRepository $rep, CategoryRepository $categoryRepository, PaginatorInterface $paginator, GeminiBookSuggester $geminiSuggester, LoggerInterface $logger): Response
    {
        
        $searchTerm = $request->query->get('search');
        $categoryFilter = $request->query->get('category_filter');
        $categoryId = null;
        $subCategoryId = null;

        if ($request->isXmlHttpRequest()) {
            $searchTerm = $request->query->get('search', '');
            
            $suggestions = $rep->createQueryBuilder('l')
                ->where('l.titre LIKE :search')
                ->orWhere('l.editeur LIKE :search') // Searching string field directly
                ->orWhere('l.tags LIKE :search')
                ->setParameter('search', '%'.$searchTerm.'%')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
            
            return $this->json(array_map(function($book) {
                return [
                    'id' => $book->getId(),
                    'titre' => $book->getTitre(),
                    'editeur' => $book->getEditeur() // Returns the string directly
                ];
            }, $suggestions));
        }

        if ($categoryFilter) {
            if (str_starts_with($categoryFilter, 'category_')) {
                $categoryId = (int) str_replace('category_', '', $categoryFilter);
            } elseif (str_starts_with($categoryFilter, 'subcategory_')) {
                $subCategoryId = (int) str_replace('subcategory_', '', $categoryFilter);
            }
        }
        
        $livres = $rep->findByFilters($searchTerm, $categoryId, $subCategoryId);

        $livres = $paginator->paginate($livres,$request->query->getInt('page', 1), 8);
        
        $categories = $categoryRepository->findAll();
        
        return $this->render('all/index.html.twig', [
            'livres' => $livres,
            'categories' => $categories,
            'current_search' => $searchTerm,
        ]);
    }

    #[Route('/livre/detail/{id}', name: 'app_livre_detail')]
    public function showone(Livre $livre,EmpruntRepository $rep): Response
    {
        if (!$livre) {
            throw $this->createNotFoundException('Livre de l\'id {$livre->getId()} non trouvé');
        }
        if($livre->getQte()==0){
            $livre->setDateDispo($rep->findEarliest());

        }
        else
        {
            $livre->setDateDispo(null);
        }
        return $this->render('all/detail.html.twig', [
            'livre' => $livre
        ]);
    }


}