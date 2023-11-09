<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Evenement;
use App\Entity\AnneeUniversitaire;
use App\Form\EvenementType;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }

    #[Route('/evenement/add', name: 'add_evenement')]

    public function add(Request $request)
    {
        $publicPath = "uploads/evenement/";
        $evenement = new Evenement();
        $form = $this->createForm("App\Form\EvenementType", $evenement);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $image = $form->get('image')->getData();
            if($image){
                $imageName = $evenement->getLibelleE().'.'.$image->guessExtension();
                $image->move($publicPath,$imageName);
                $evenement->setImage($imageName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('consult_evenement');
        }
            //utiliser la methode createView() pour que l'objet soit exploitable par la vue
        return $this->render('evenement/add.html.twig',
        ['f'=>$form->createView()] );
    }

    #[Route('/event/consult', name: 'consult_evenement')]

    public function afficher(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Evenement::class);
        $lesEvenements = $repo->findAll();
        $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/evenement/';
        return $this->render('evenement/consult.html.twig',
        ['lesEvenements'=> $lesEvenements, 'publicPath'=>$publicPath ]);
    }

    #[Route('/evenement/delete/{id}', name: 'delete_evenement')]

    public function delete(Request $request, $id): Response
    {
        $evenement=$this->getDoctrine()
        ->getRepository(Evenement::class)
        ->find($id);
        if(!$evenement){
            throw $this->createNotfoundException(
                'No evenement found for id'.$id
            );
        }
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->remove($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('consult_evenement');
    }

    #[Route('/evenement/edit/{id}', name: 'edit_evenement', methods: ['GET','POST'])]

    public function edit(Request $request, $id)
    { 
        $publicPath = "uploads/evenement/";
        $evenement = new Evenement();
        $evenement = $this->getDoctrine()->getRepository(Evenement::class) ->find($id);
        if (!$evenement) {
            throw $this->createNotFoundException(
            'No evenement found for id '.$id
            );
        }
        $form = $this->createForm("App\Form\EvenementType", $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $image = $form->get('image')->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $imageName = $evenement->getLibelleE().'.'.$image->guessExtension();
            $image->move($publicPath,$imageName);
            $evenement->setImage($imageName);
            $entityManager->flush();
            return $this->redirectToRoute('consult_evenement');
        }
        return $this->render('evenement/add.html.twig',
        ['f' => $form->createView()] );
    }
}
