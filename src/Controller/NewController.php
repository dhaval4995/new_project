<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewController extends AbstractController
{
	  /**
      * @Route("/new/message")
      
      */

    public function message()
    {
        $message = 'Hello Dhaval';

        // return new Response(
        //     '<html><body>Message: '.$message.'</body></html>'
        // );

        return $this->render('message.html.twig', [
            'message' => $message,
        ]);
    }
}