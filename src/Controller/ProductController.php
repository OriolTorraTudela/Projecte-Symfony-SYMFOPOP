<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controlador de productes - gestiona totes les operacions CRUD.
 * Inclou llistat públic, detall, creació, edició, esborrat i "Els meus productes".
 */
#[Route('/product')]
class ProductController extends AbstractController
{
    /**
     * Mostra el llistat de tots els productes.
     * Accessible per a tots els usuaris (autenticats o no).
     * Els productes es mostren ordenats per data de creació (més recents primer).
     */
    #[Route('', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        // Obtenim tots els productes ordenats per data de creació descendent
        $products = $productRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'title' => 'Tots els Productes',
            'show_actions' => false,
            'show_new_button' => false,
            'empty_message' => 'No hi ha productes disponibles encara.',
        ]);
    }

    /**
     * Mostra els productes de l'usuari autenticat.
     * Requereix autenticació (ROLE_USER).
     * Reutilitza la vista index.html.twig amb paràmetres per mostrar botons d'acció.
     *
     * IMPORTANT: Aquesta ruta ha d'anar ABANS de {id} per evitar conflictes de routing.
     */
    #[Route('/my/products', name: 'app_my_products', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function myProducts(ProductRepository $productRepository): Response
    {
        // Filtrem productes per l'usuari autenticat actual
        $products = $productRepository->findBy(
            ['owner' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        // Reutilitzem la mateixa vista amb paràmetres diferents (principi DRY)
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'title' => 'Els Meus Productes',
            'show_actions' => true,
            'show_new_button' => true,
            'empty_message' => 'Encara no has publicat cap producte. Crea el teu primer producte!',
        ]);
    }

    /**
     * Crea un nou producte.
     * Requereix autenticació (ROLE_USER).
     * Assigna automàticament l'usuari autenticat com a propietari (owner).
     * Si no s'especifica imatge, en genera una amb Picsum.
     * Genera automàticament la data de creació (createdAt).
     */
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Assignem automàticament l'usuari autenticat com a propietari
            $product->setOwner($this->getUser());

            // Generem la data de creació automàticament
            $product->setCreatedAt(new \DateTime());

            // Si no s'ha especificat cap imatge, generem una amb Picsum
            if (empty($product->getImage())) {
                $product->setImage('https://picsum.photos/seed/' . uniqid() . '/400/300');
            }

            $entityManager->persist($product);
            $entityManager->flush();

            // Missatge flash de confirmació
            $this->addFlash('success', 'Producte creat correctament!');

            // Redirigim al detall del producte creat
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Mostra el detall d'un producte.
     * Utilitza ParamConverter per obtenir el producte automàticament a partir de l'ID.
     * Retorna error 404 si el producte no existeix.
     */
    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Edita un producte existent.
     * Només el propietari pot editar el seu producte.
     * Si l'usuari no és el propietari, llança AccessDeniedException (error 403).
     */
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        // Validem que l'usuari actual sigui el propietari del producte
        if ($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No pots editar aquest producte.');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Missatge flash de confirmació
            $this->addFlash('success', 'Producte actualitzat correctament!');

            // Redirigim al detall del producte
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Esborra un producte.
     * Només el propietari pot esborrar el seu producte.
     * Valida el token CSRF per seguretat (evita atacs CSRF).
     * Utilitza mètode POST per seguir bones pràctiques REST.
     */
    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        // Validem que l'usuari actual sigui el propietari del producte
        if ($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No pots esborrar aquest producte.');
        }

        // Validem el token CSRF per protegir contra atacs CSRF
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();

            // Missatge flash de confirmació
            $this->addFlash('success', 'Producte esborrat correctament!');
        }

        return $this->redirectToRoute('app_product_index');
    }
}
