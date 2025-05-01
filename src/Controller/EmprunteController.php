<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EmprunteController extends AbstractController
{
    #[Route('/user/emprunte', name: 'app_emprunte')]
    public function emprunte(Livre $livre,EntityManager $em): Response
    {
        if($livre->getDateDispo() != null){
            $this->addFlash(
                'danger', 
                'livre sera disponible '.$livre->getDateDispo()->format('d/m/Y')
            );
            return $this->redirect('emprunte/index.html.twig');
        }
        // waiting registration
       /* else if($this->getUser() ==null){


            return $this->render('register/login.html.twig');
        }
        else if($this->getUser()->getLivId() != null){
            $this->addFlash(
                'danger', 
                'vous avez deja un livre emprunté'
            );
            return $this->redirect('emprunte/index.html.twig');
        }*/
        else if($livre->getQteDispo() == 0){
            $this->addFlash(
                'danger', 
                'livre non disponible'  
            );
            return $this->redirect('emprunte/index.html.twig');
        }
        else{  
            $livre->setQteDispo($livre->getQteDispo()-1);
            if($livre->getQteDispo() == 0){
                $livre->setDateDispo(new \DateTime('+15 days'));
                
            }
            $emprunt=new Emprunt();
            $emprunt->setLiv($livre);
            $emprunt->setUser($this->getUser());
            $emprunt->setDateEmprunt(new \DateTime());
            $emprunt->setDateRetourMax(new \DateTime('+15 days'));
            $emprunt->setDateRetour(null);
            $em->persist($emprunt);
            $em->flush();
            $em->persist($livre);
            $em->flush();
             return $this->render('emprunte/index.html.twig', [
            'controller_name' => 'EmprunteController',
        ]);
        }
        return $this->render('emprunte/index.html.twig', [
            'controller_name' => 'EmprunteController',
        ]);
    }
    #[Route('/user/emprunts', name: 'app_emprunte_all')]
    public function emprunts(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
       /* if($user == null){
            return $this->render('register/login.html.twig');
        }
        else*/{
            $emprunts = $em->getRepository(Emprunt::class)->findBy(['user' => $user , 'retour' => true]);
            $emprunt = $em->getRepository(Emprunt::class)->findOneBy(['user' => $user , 'retour' => false]);

            return $this->render('emprunte/index.html.twig', [
                'controller_name' => 'EmprunteController',
                'emprunts' => $emprunts,'emprunt' => $emprunt
            ]);
        }
    }
    #[Route('/user/emprunt/retour', name: 'app_emprunt_retour')]
    public function retour(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        
            $emprunt = $em->getRepository(Emprunt::class)->findOneBy(['user' => $user , 'retour' => false]);
            
                $livre = $emprunt->getLiv();
                $livre->setQteDispo($livre->getQteDispo()+1); 
                $livre->setDateDispo(null);
                $emprunt->setDateRetour(new \DateTime());
                $emprunt->setRetour(true);
                $em->persist($livre);
                $em->persist($emprunt);
            
            $em->flush();
            return $this->redirect('index.html.twig');
        
    }
}
