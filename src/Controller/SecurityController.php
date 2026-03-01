<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controlador de seguretat - gestiona el login i logout dels usuaris.
 */
class SecurityController extends AbstractController
{
    /**
     * Mostra el formulari de login i gestiona els errors d'autenticació.
     * Si l'usuari ja està autenticat, el redirigeix al llistat de productes.
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'usuari ja està autenticat, redirigim al llistat
        if ($this->getUser()) {
            return $this->redirectToRoute('app_product_index');
        }

        // Obtenim l'error d'autenticació (si n'hi ha)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Últim email introduït per l'usuari (per pre-emplenar el formulari)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Ruta de logout - gestionada automàticament pel firewall de Symfony.
     * El codi dins d'aquest mètode mai s'executarà.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony intercepta aquesta ruta abans que arribi al controlador
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
