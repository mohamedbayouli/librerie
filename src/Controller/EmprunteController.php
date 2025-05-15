<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EmprunteController extends AbstractController
{
    #[Route('/calendar/reserve', name: 'app_calendar_reserve', methods: ['POST'])]
public function reserveBook(Request $request, EntityManagerInterface $em,EmpruntRepository $rep): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $livre = $em->getRepository(Livre::class)->find($data['livre_id']);
    $user = $this->getUser();


    if ($livre->getQteDispo() <= 0) {
        return new JsonResponse(['success' => false, 'message' => 'Aucune copie disponible'], 400);
    }
    $limit = $rep->findBy(['user' => $user, 'retour' => false]);
    if (count($limit) >= 1) {
        return new JsonResponse(['success' => false, 'message' => 'Vous ne pouvez réserver qu\'un seul livre à la fois'], 400);
    }
  
    $emprunt = new Emprunt();
    $emprunt->setLiv($livre);
    $emprunt->setUser($user);
    $emprunt->setDateEmprunt(new \DateTime($data['start']));
    $emprunt->setDateRetourMax(new \DateTime($data['end']));
    $emprunt->setRetour(false);
    $livre->setQteDispo($livre->getQteDispo() - 1);
    $em->persist($emprunt);
    $em->flush();

    return new JsonResponse([
        'success' => true,
        'event' => [
            'id' => $emprunt->getId(),
            'title' => 'Your Reservation',
            'start' => $data['start'],
            'end' => $data['end'],
            'color' => '#4CAF50'
        ]
    ]);
}
    #[Route('/user/emprunts', name: 'app_emprunte_all')]
    public function emprunts(EntityManagerInterface $em): Response
    { 
        
        $user = $this->getUser();
       if($user == null){
            return $this->render('security/login.html.twig');
        }
        else{
            $emprunts = $em->getRepository(Emprunt::class)->findBy(['user' => $user , 'retour' => true]);
            $emprunt = $em->getRepository(Emprunt::class)->findOneBy(['user' => $user , 'retour' => false]);

            return $this->render('emprunte/index.html.twig', [
                'controller_name' => 'EmprunteController',
                'emprunts' => $emprunts,'emprunt' => $emprunt
            ]);
        }
    }
    #[Route('/admin/emprunt/retour/{id}', name: 'app_emprunte_retour')]
    public function retour(EntityManagerInterface $em,Emprunt $emprunte): Response
    {
                $livre = $emprunte->getLiv();
                $livre->setQteDispo($livre->getQteDispo()+1); 
                $livre->setDateDispo(null);
                $emprunte->setDateRetour(new \DateTime());
                $emprunte->setRetour(true);
                $em->persist($livre);
                $em->persist($emprunte);
            
            $em->flush();
            return $this->redirectToRoute('app_emprunt');
        
    }
    #[Route('/admin/emprunt', name: 'app_emprunt')]
    public function emprunt(EmpruntRepository $rep): Response
    {
       
        return $this->render('emprunte/admin.html.twig', [
            
            'emprunts' => $rep->findAll()
        ]);
    }
#[Route('/calendar/{id}', name: 'app_calendar')]
    public function index(Livre $livre): Response
    {
        return $this->render('emprunte/calendar.html.twig',[
            'livre' => $livre,
        ]); 
    }
   #[Route('/calendar/load/{id}', name: 'app_calendar_load')]
public function loadEvents(EmpruntRepository $repository, $id): JsonResponse
{
    $reservations = $repository->findBy([
        'liv' => $id, 
        'retour' => false
    ]);
    
    $events = [];
    foreach ($reservations as $reservation) {
        $events[] = [
            'id' => $reservation->getId(),
            'title' => "Réservé par " . $reservation->getUser()->getNom(), // More informative
            'start' => $reservation->getDateEmprunt()->format('Y-m-d'),
            'end' => $reservation->getDateRetourMax()->modify('+1 day')->format('Y-m-d'), // +1 day to include end date
            'color' => '#4CAF50', // Nicer green
            'textColor' => '#ffffff',
            'borderColor' => '#388E3C',
            'extendedProps' => [
                'user' => $reservation->getUser()->getNom(),
                'book' => $reservation->getLiv()->getTitre(),
                'period' => $reservation->getDateEmprunt()->format('M d') . ' - ' . 
                            $reservation->getDateRetourMax()->format('M d')
            ]
        ];
        
    }

    return new JsonResponse($events);
}
    #[Route('/user/emprunt/delete/{id}', name: 'app_reserve_annuler')]
public function deleteEvent(Emprunt $emprunt, EntityManagerInterface $em)
{
    $livre = $emprunt->getLiv();
    $livre->setQteDispo($livre->getQteDispo() + 1);
    $livre->setDateDispo(null);
    $em->persist($livre);
    $em->remove($emprunt);
    $em->flush();

    return $this->redirectToRoute('app_all');
}

}