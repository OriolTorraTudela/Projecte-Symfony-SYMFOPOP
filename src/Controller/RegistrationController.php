<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controlador de registre - gestiona la creació de nous comptes d'usuari.
 */
class RegistrationController extends AbstractController
{
    /**
     * Mostra el formulari de registre i processa la creació de nous usuaris.
     * Després del registre exitós, fa login automàtic i redirigeix al llistat.
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        // Si l'usuari ja està autenticat, redirigim al llistat
        if ($this->getUser()) {
            return $this->redirectToRoute('app_product_index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashejem la contrasenya amb l'algoritme configurat (bcrypt/argon2)
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // Missatge flash de benvinguda
            $this->addFlash('success', 'Compte creat correctament! Benvingut/da a SymfoPop!');

            // Login automàtic després del registre (millor UX)
            $security->login($user, 'form_login', 'main');

            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
