<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controlador de la pàgina principal.
 * Redirigeix a la llista de productes com a pàgina d'inici.
 */
class HomeController extends AbstractController
{
    /**
     * Pàgina d'inici - redirigeix al llistat de productes.
     * Accessible per a tots els usuaris (autenticats o no).
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_product_index');
    }
}
