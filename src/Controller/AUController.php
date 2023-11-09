<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Groupe;
use App\Entity\Etudiant;
use App\Entity\Pack;
use App\Entity\Evenement;
use App\Entity\AnneeUniversitaire;


class AUController extends AbstractController
{
    #[Route('/annee', name: 'app_annee')]
    public function index(): Response
    {
        return $this->render('au/index.html.twig', [
            'controller_name' => 'AUController',
        ]);
    }

    #[Route('/annee/add', name: 'add_annee')]

    public function add(Request $request)
    {
        $annee = new AnneeUniversitaire();
        $fb = $this ->createFormBuilder($annee)
                    ->add('libelleAU')
                    ->add('Valider', SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($annee);
            $em->flush();
            return $this->redirectToRoute('consult_annee');
        }
            //utiliser la methode createView() pour que l'objet soit exploitable par la vue
        return $this->render('annee/add.html.twig',
        ['f'=>$form->createView()] );
    }

    #[Route('/annee/show/{id}', name: 'annee')]

    public function afficherById($id, Request $request)
    {
        $annee = $this->getDoctrine()
        ->getRepository(AnneeUniversitaire::class)
        ->find($id);
        if (!$annee)
        {
            throw $this->createNotFoundException(
                'No annee found for id '.$id
            );
        }
        $em=$this->getDoctrine()->getManager();
        $groupe=$em->getRepository(Groupe::class);
        $lesGroupes=$em
        ->getRepository(Groupe::class)
        ->findBy(['AnneeUniversitaire'=>$annee]);

        return $this->render('annee/annee.html.twig' , [
             'Annee' =>$annee,
             'groupe'=>$groupe,
             'lesGroupes'=>$lesGroupes,

        ]);
    }

    #[Route('/annee/consult', name: 'consult_annee')]

    public function afficher(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(AnneeUniversitaire::class);
        $lesAnnees = $repo->findAll();
        return $this->render('annee/consult.html.twig',
        ['lesAnnees'=> $lesAnnees]);
    }

    #[Route('/annee/delete/{id}', name: 'delete_annee')]

    public function delete(Request $request, $id): Response
    {
        $annee=$this->getDoctrine()
        ->getRepository(AnneeUniversitaire::class)
        ->find($id);
        if(!$annee){
            throw $this->createNotfoundException(
                'No annee found for id'.$id
            );
        }
        $entityManager=$this->getDoctrine()->getManager();
        $groupe=$entityManager->getRepository(Groupe::class);
        $lesGroupes=$entityManager
        ->getRepository(Groupe::class)
        ->findBy(['AnneeUniversitaire'=>$annee]);

        foreach($lesGroupes as $g){
            $etudiant=$entityManager->getRepository(Etudiant::class);
            $lesEtudiants=$entityManager
            ->getRepository(Etudiant::class)
            ->findBy(['Groupe'=>$g]);

            foreach($lesEtudiants as $et){
                $et->setGroupe(null);
            }
                $entityManager->remove($g);
        }

        $lesEvents=$entityManager
        ->getRepository(Evenement::class)
        ->findBy(['AnneeUniversitaire'=>$annee]);
        foreach($lesEvents as $e){
            $e->setAnneeUniversitaire(null);
        }

        $lesPacks=$entityManager
        ->getRepository(Pack::class)
        ->findBy(['AnneeUniversitaire'=>$annee]);
        foreach($lesPacks as $p){
            $p->setAnneeUniversitaire(null);
        }
        
        $entityManager->remove($annee);

        $entityManager->flush();
        return $this->redirectToRoute('consult_annee');
    }

    #[Route('/annee/edit/{id}', name: 'edit_annee', methods: ['GET','POST'])]

    public function edit(Request $request, $id)
    { 
        $annee = new AnneeUniversitaire();
        $annee = $this->getDoctrine()->getRepository(AnneeUniversitaire::class) ->find($id);
        if (!$annee) {
            throw $this->createNotFoundException(
            'No annee found for id '.$id
            );
        }
        $fb = $this ->createFormBuilder($annee)
                    ->add('libelleAU')
                    ->add('Valider', SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('consult_annee');
        }
        return $this->render('annee/add.html.twig',
        ['f' => $form->createView()] );
    }
}