<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Etudiant;
use App\Entity\User;
use App\Entity\Groupe;
use App\Form\EtudiantType;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
    #[Route('/etudiant/add', name: 'add_etudiant')]

    public function add(Request $request)
    {
        $publicPath = "uploads/etudiant/";
        $etudiant = new Etudiant();
        $form = $this->createForm("App\Form\EtudiantType", $etudiant);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            if($image){
                $imageName = $etudiant->getMatricule().'.'.$image->guessExtension();
                $image->move($publicPath,$imageName);
                $etudiant->setImage($imageName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();
            return $this->redirectToRoute('consult_etudiant');
        }
            //utiliser la methode createView() pour que l'objet soit exploitable par la vue
        return $this->render('etudiant/add.html.twig',
        ['f'=>$form->createView()] );
    }

    #[Route('/etudiant/show/{id}', name: 'etudiant')]

    public function afficherById($id, Request $request)
    {
        $etudiant = $this->getDoctrine()
        ->getRepository(Etudiant::class)
        ->find($id);
        $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/etudiant/';
        if (!$etudiant)
        {
            throw $this->createNotFoundException(
                'No etudiant found for id '.$id
            );
        }
        return $this->render('etudiant/etudiant.html.twig' , [
             'etudiant' =>$etudiant, 'publicPath'=>$publicPath
        ]);
    }

    #[Route('/etudiant/consult', name: 'consult_etudiant')]

    public function afficher(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Etudiant::class);
        $lesEtudiants = $repo->findAll();
        return $this->render('etudiant/consult.html.twig',
        ['lesEtudiants'=> $lesEtudiants]);
    }

    #[Route('/etudiant/delete/{id}', name: 'delete_etudiant')]

    public function delete(Request $request, $id): Response
    {
        $etudiant=$this->getDoctrine()
        ->getRepository(Etudiant::class)
        ->find($id);
        if(!$etudiant){
            throw $this->createNotfoundException(
                'No etudiant found for id'.$id
            );
        }
        $entityManager=$this->getDoctrine()->getManager();
        $etudiant->setUser(null);
        $entityManager->remove($etudiant);
        $entityManager->flush();
        return $this->redirectToRoute('consult_etudiant');
    }

    #[Route('/etudiant/edit/{id}', name: 'edit_etudiant', methods: ['GET','POST'])]

    public function edit(Request $request, $id)
    { 
        $publicPath = "uploads/etudiant/";
        $etudiant = new Etudiant();
        $etudiant = $this->getDoctrine()->getRepository(Etudiant::class) ->find($id);
        if (!$etudiant) {
            throw $this->createNotFoundException(
            'No etudiant found for id '.$id
            );
        }
        $form = $this->createForm("App\Form\EtudiantType", $etudiant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $imageName = $etudiant->getMatricule().'.'.$image->guessExtension();
            $image->move($publicPath,$imageName);
            $etudiant->setImage($imageName);
            $entityManager->flush();
            return $this->redirectToRoute('consult_etudiant');
        }
        return $this->render('etudiant/add.html.twig',
        ['f' => $form->createView()] );
    }

    #[Route('/profile/{id}', name: 'profile_etudiant')]

    public function profile($id, Request $request)
    {
        $id = $this->getUser()->getId();
        $etudiant = $this->getDoctrine()
        ->getRepository(Etudiant::class)
        ->findBy(['User'=>$id]);
        $et=array_values($etudiant)[0];
        $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/etudiant/';
        if (!$et)
        {
            throw $this->createNotFoundException(
                'No etudiant found for id '.$id
            );
        }
        return $this->render('etudiant/profile.html.twig',[
             'etudiant' =>$et, 'publicPath'=>$publicPath
        ]);
    }
    
}
