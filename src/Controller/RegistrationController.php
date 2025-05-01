<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\EtudiantRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager,
        EtudiantRepository $etudiantRepository
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'etudiant_repository' => $etudiantRepository // Injection du repository dans le formulaire
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            // Validation des mots de passe
            if ($plainPassword !== $confirmPassword) {
                $form->get('confirmPassword')->addError(new FormError('Les mots de passe ne correspondent pas.'));
            }

            // Si le formulaire est valide
            if ($form->isValid()) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
                $user->setRoles(['ROLE_USER']); // Assigner le rôle par défaut
                // Persister l'utilisateur
                $entityManager->persist($user);
                $entityManager->flush();

                // Connexion après l'inscription
                return $security->login($user, LoginFormAuthenticator::class, 'main');
            }
        }

        // Retourner la vue
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
