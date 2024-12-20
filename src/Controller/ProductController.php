<?php

// src/Controller/ProductController.php
namespace App\Controller;

// ...
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'create_product')]
    public function createProduct(EntityManagerInterface $entityManager): Response
    {
        $producto = new Producto();
        $producto->setNombre('Screen');
        $producto->setPrecio(2000);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($producto);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id ' . $producto->getId());
    }

    #[Route('/product/{id}', name: 'producto_id', requirements: ['id' => '\d+'])]
    public function verProducto(EntityManagerInterface $entityManager, int $id): Response
    {
        $producto = $entityManager->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        //return new Response('Check out this great product: '.$producto->getNombre());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('product/producto.html.twig', ['producto' => $producto]);
    }

    #[Route('/product/todos', name: 'producto_todos')]
    public function listarProductos(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        // Obtener todos los productos de la base de datos
        $query = $entityManager->getRepository(Producto::class)->createQueryBuilder('p')->getQuery();

        // Aplicar la paginación
        $productos = $paginator->paginate(
            $query, // Consulta
            $request->query->getInt('page', 1), // Número de página (por defecto 1)
            5 // Límite de productos por página
        );

        // Renderizar la vista Twig pasando los productos
        return $this->render('product/productos.html.twig', [
            'productos' => $productos,
        ]);
    }
}
