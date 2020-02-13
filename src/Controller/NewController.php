<?php

namespace App\Controller;
use App\Entity\Product;
use App\Form\ProductType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class NewController extends AbstractController
{
	  /**
      * @Route("/new/message", name="data")

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

    /**
    * @Route("/new/add", name="addproduct")
    */

    public function createproduct(Request $request)
    {
       $product = new Product();

       // $form = $this->createForm(ProductType::class);
       $form = $this->createFormBuilder($product)
                     ->add('name',TextType::class, array('attr'=>array('class'=>'form-control')))
                     ->add('price',TextType::class, array('attr'=>array('class'=>'form-control')))
                     ->add('description',TextareaType::class, array('attr'=>array('class'=>'form-control')))
                     ->add('brochureFilename',FileType::class, array('label'=>'Photo(png,jpeg)','mapped'=>false,'required'=>false))
                     // ->add('brochure',FileType::class, array(
                     //    'label'=>'Brochure (PDF file)',
                     //    'mapped'=>false,
                     //    'required'=>false,
                     //    'constraints'=>array(
                     //        new File([
                     //            'mimeTypes'=> [
                     //                'application/pdf',
                     //                'application/x-pdf',
                     //            ],
                     //            'mimeTypesMessage'=>'Please upload a valid PDF document',
                     //        ])
                     //      ))
                     //    )
                     ->add('save',SubmitType::class, array('label'=>'Save','attr'=>array('class'=>'btn btn-primary')))
                     
                     ->getForm();
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $brochurefilename =  $form->get('brochureFilename')->getData();


            if ($brochurefilename) {
                $originalFilename = pathinfo($brochurefilename->getClientOriginalName(), PATHINFO_FILENAME);
                // $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',$originalFilename);
                $newFilename = $originalFilename.'-'.uniqid().','.$brochurefilename->guessExtension();
                $brochurefilename->move($this->getParameter('brochures_directory'),$newFilename);
                //  try {
                //     $brochurefilename->move(
                //         $this->getParameter('brochures_directory'),$newFilename);
                // } catch (FileException $e) {
                //     // ... handle exception if something happens during file upload
                // }
            }
            dump($newFilename);
            exit();
            $product->setbrochurefilename($newFilename);
            $em->persist($product);
            $em->flush();

           return $this->redirectToRoute('show');
       }    
        
        return $this->render('product/add.html.twig',array(
            'form'=>$form->createView()
        ));     
    }

     /**
    * @Route("/new/show", name="show")
    */

    public function showAllData(){
        // $product = new Product();
        // $em = $this->getDoctrine()->getManager();
        // $prod = $em->findAll();
        $product = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render('product/index.html.twig', [
                    'products'=>$product
        ]);
    }

    /**
     * @Route("/new/getdata/{id}", name="getdatabyid")
    */

    public function getdata($id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        
        return $this->render('product/show.html.twig', [
                  'product'=>$product
        ]);
    }

    /**
     * @Route("/new/updatedata/{id}", name="getupdatedata")
     * Method ({"GET", "POST"})
    */

    public function updatedata(Request $request, $id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        
        $form = $this->createFormBuilder($product)
                     ->add('name',TextType::class, array('attr'=>array('class'=>'form-control')))
                     ->add('price',TextType::class, array('attr'=>array('class'=>'form-control')))
                     ->add('description',TextareaType::class, array('attr'=>array('class'=>'form-control')))
                     ->add('save',SubmitType::class, array('label'=>'Update','attr'=>array('class'=>'btn btn-primary')))

                     ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
          
            $product = $form->getData();
            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($product);
            $entitymanager->flush();

            return $this->redirectToRoute('show');
        }

        return $this->render('product/update.html.twig',[
                     'form'=>$form->createView(),
        ]);             

    }

    /**
     * @Route("/new/delete/{id}", name="deleteproductdata")
    */

    public function delete($id)
    {
       $em = $this->getDoctrine()->getManager();
       $product = $em->getRepository(Product::class)->find($id);
       $em->remove($product);
       $em->flush();
       return $this->redirectToRoute('show');
    }
}