<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use App\Entity\Groupe;
use App\Entity\Etudiant;
use App\Form\GroupeType;

class GroupeController extends AbstractController
{
    #[Route('/groupe', name: 'app_groupe')]
    public function index(): Response
    {
        return $this->render('groupe/index.html.twig', [
            'controller_name' => 'GroupeController',
        ]);
    }

    #[Route('/groupe/add', name: 'add_groupe')]

    public function add(Request $request)
    {
        $groupe = new Groupe();
        $form = $this->createForm("App\Form\GroupeType", $groupe);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();
            return $this->redirectToRoute('consult_groupe');
        }
            //utiliser la methode createView() pour que l'objet soit exploitable par la vue
        return $this->render('groupe/add.html.twig',
        ['f'=>$form->createView()] );
    }

    #[Route('/groupe/show/{id}', name: 'groupe')]

    public function afficherById($id, Request $request)
    {
        $groupe = $this->getDoctrine()
        ->getRepository(Groupe::class)
        ->find($id);
        if (!$groupe)
        {
            throw $this->createNotFoundException(
                'No groupe found for id '.$id
            );
        }
        $em=$this->getDoctrine()->getManager();
        $etudiant=$em->getRepository(Etudiant::class);
        $lesEtudiants=$em
        ->getRepository(Etudiant::class)
        ->findBy(['Groupe'=>$groupe]);

        return $this->render('groupe/groupe.html.twig' , [
             'groupe' =>$groupe,
             'etudiant'=>$etudiant,
             'lesEtudiants'=>$lesEtudiants,

        ]);
    }

    #[Route('/groupe/consult', name: 'consult_groupe')]

    public function afficher(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Groupe::class);
        $lesGroupes = $repo->findAll();
        return $this->render('groupe/consult.html.twig',
        ['lesGroupes'=> $lesGroupes]);
    }

    #[Route('/groupe/delete/{id}', name: 'delete_groupe')]

    public function delete(Request $request, $id): Response
    {
        $groupe=$this->getDoctrine()
        ->getRepository(Groupe::class)
        ->find($id);
        if(!$groupe){
            throw $this->createNotfoundException(
                'No groupe found for id'.$id
            );
        }
        $entityManager=$this->getDoctrine()->getManager();
        $etudiant=$entityManager->getRepository(Etudiant::class);
        $lesEtudiants=$entityManager
        ->getRepository(Etudiant::class)
        ->findBy(['Groupe'=>$groupe]);

        foreach($lesEtudiants as $et){
            $et->setGroupe(null);
            // $entityManager->remove($par);
        }
        $entityManager->remove($groupe);

        $entityManager->flush();
        return $this->redirectToRoute('consult_groupe');
    }

    #[Route('/groupe/edit/{id}', name: 'edit_groupe', methods: ['GET','POST'])]

    public function edit(Request $request, $id)
    { 
        $groupe = new groupe();
        $groupe = $this->getDoctrine()->getRepository(groupe::class) ->find($id);
        if (!$groupe) {
            throw $this->createNotFoundException(
            'No groupe found for id '.$id
            );
        }
        $form = $this->createForm("App\Form\GroupeType", $groupe);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('consult_groupe');
        }
        return $this->render('groupe/add.html.twig',
        ['f' => $form->createView()] );
    }
}
