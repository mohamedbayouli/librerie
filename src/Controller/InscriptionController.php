<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;

final class InscriptionController extends AbstractController{
    #[Route('/inscription', name: 'app_inscription')]
    public function index()
    {
        return $this->render('inscription/inscription.html.twig', ['etat' => '0']);
        
    }
    #[Route('/inscription/psw', name: 'app_inscription_psw')]
    public function psw(){
        $pass=$_POST['password'];
        if ( strlen($pass) <8 or !(preg_match('/\d/', $pass)))
        {
            
            $etat=1;
            return $this->render('inscription/inscription.html.twig', [
                'etat' => $etat ]);
        }
        else if ($_POST['password_confirmation'] !=$pass){
            return $this->render('inscription/inscription.html.twig', [
                'etat' => 2,
            ]);
        } else {
            return $this->render('inscription/inscription.html.twig', [
                'etat' => 0,
            ]);
        }
    }
}
