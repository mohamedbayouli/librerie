<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AllController extends AbstractController
{
    #[Route('/all', name: 'app_all')]
    public function showall(LivreRepository $rep,): Response
    {
        $livres = $rep->findAll();
        return $this->render('all/index.html.twig', [
            'livres' => $livres
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
