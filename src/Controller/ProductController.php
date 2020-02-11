<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
    	$entityManager = $this->getDoctrine()->getManager();

    	$product  = new Product();
    	$product->setName('Keyboard');
    	$product->setPrice(1999);
    	$product->setDescription('Ergonomic and stylish');

    	$entityManager->persist($product);
    	$entityManager->flush();

    	return new Response('Save new product with id'.$product->getId());
        // return $this->render('product/index.html.twig', [
        //     'controller_name' => 'ProductController',
        // ]);
    }

    /**
     * @Route("/product/createProduct", name="product")
     */

    public function createProduct(ValidatorInterface $validator): Response
    {
       $product = new Product();
       // $product->setName('mouse');
       // $product->setPrice(null);
       // $product->setDescription('Hello Dhaval');

       $errors = $validator->validate($product);
       if (count($errors)> 0) {
       	 return $this->render('product/test.html.twig', [
                'errors' => $errors,
       	 ]);
       }
       return new Response('The author is valid! Yes!');
    }	
}
