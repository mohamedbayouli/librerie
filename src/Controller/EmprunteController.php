<?php

namespace App\Controller;

use App\Entity\Livre;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EmprunteController extends AbstractController
{
    #[Route('/user/emprunte', name: 'app_emprunte')]
    public function emprunte(Livre $livre): Response
    {
        if($livre->getDateDispo() != null){
            $this->addFlash(
                'danger', 
                'livre sera disponible '.$livre->getDateDispo()->format('d/m/Y')
            );
            return $this->redirect('emprunte/index.html.twig');
        }
        else{
            
        }
        return $this->render('emprunte/index.html.twig', [
            'controller_name' => 'EmprunteController',
        ]);
    }
}
