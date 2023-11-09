<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Certification;
use App\Form\CertificationType;
use App\Entity\Pack;
use App\Form\PackType;


class CertificationController extends AbstractController
{
    #[Route('/certification', name: 'app_certification')]
    public function index(): Response
    {
        return $this->render('certification/index.html.twig', [
            'controller_name' => 'CertificationController',
        ]);
    }

    #[Route('/certif/add', name: 'add_certif')]

    public function add(Request $request)
    {
        $publicPath = "uploads/certification/";
        $certif = new Certification();
        $form = $this->createForm("App\Form\CertificationType", $certif);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $image = $form->get('image')->getData();
            if($image){
                $imageName = $certif->getLibelleC().'.'.$image->guessExtension();
                $image->move($publicPath,$imageName);
                $certif->setImage($imageName);
                $pack=$certif->getPack();
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($certif);
            $em->flush();
            return $this->redirectToRoute('pack',['id' => $pack->getId()]);
        }
            //utiliser la methode createView() pour que l'objet soit exploitable par la vue
        return $this->render('certif/add.html.twig',
        ['f'=>$form->createView()] );
    }

    #[Route('/certif/consult', name: 'consult_certif')]

    public function afficher(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Certification::class);
        $lesCertifications = $repo->findAll();
        $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/certification/';
        return $this->render('certif/consult.html.twig',
        ['lesCertifications'=> $lesCertifications, 'publicPath'=>$publicPath ]);
    }

    #[Route('/certif/delete/{id}', name: 'delete_certif')]

    public function delete(Request $request, $id): Response
    {
        $certif=$this->getDoctrine()
        ->getRepository(Certification::class)
        ->find($id);
        $pack=$certif->getPack();
        if(!$certif){
            throw $this->createNotfoundException(
                'No Certification found for id'.$id
            );
        }
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->remove($certif);
        $entityManager->flush();
        return $this->redirectToRoute('pack',['id' => $pack->getId()]);
    }

    #[Route('/certif/edit/{id}', name: 'edit_certif', methods: ['GET','POST'])]

    public function edit(Request $request, $id)
    { 
        $publicPath = "uploads/Certification/";
        $certif = new Certification();
        $certif = $this->getDoctrine()->getRepository(Certification::class) ->find($id);
        if (!$certif) {
            throw $this->createNotFoundException(
            'No Certification found for id '.$id
            );
        }
        $form = $this->createForm("App\Form\CertificationType", $certif);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $image = $form->get('image')->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $imageName = $certif->getLibelleC().'.'.$image->guessExtension();
            $image->move($publicPath,$imageName);
            $certif->setImage($imageName);
            $pack=$certif->getPack();
            $entityManager->flush();
            return $this->redirectToRoute('pack',['id' => $pack->getId()]);
        }
        return $this->render('certif/add.html.twig',
        ['f' => $form->createView()] );
    }

    #[Route('/pack/add', name: 'add_pack')]

    public function addP(Request $request)
    {
        $publicPath = "uploads/pack/";
        $pack = new Pack();
        $form = $this->createForm("App\Form\PackType", $pack);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $image = $form->get('image')->getData();
            if($image){
                $imageName = $pack->getLibelleP().'.'.$image->guessExtension();
                $image->move($publicPath,$imageName);
                $pack->setImage($imageName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($pack);
            $em->flush();
            return $this->redirectToRoute('consult_pack');
        }
            //utiliser la methode createView() pour que l'objet soit exploitable par la vue
        return $this->render('pack/add.html.twig',
        ['f'=>$form->createView()] );
    }

    #[Route('/certification/show/{id}', name: 'pack')]

    public function afficherById($id, Request $request)
    {
        $pack = $this->getDoctrine()
        ->getRepository(Pack::class)
        ->find($id);
        $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/certification/';
        if (!$pack)
        {
            throw $this->createNotFoundException(
                'No pack found for id '.$id
            );
        }
        $em=$this->getDoctrine()->getManager();
        $certif=$em->getRepository(Certification::class);
        $lesCertifs=$em
        ->getRepository(Certification::class)
        ->findBy(['Pack'=>$pack]);
        return $this->render('pack/pack.html.twig' , [
             'pack' =>$pack, 'certif' =>$certif, 'lesCertifs' =>$lesCertifs, 'publicPath'=>$publicPath, 
        ]);
    }

    #[Route('/certification/consult', name: 'consult_pack')]

    public function afficherP(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Pack::class);
        $lesPacks = $repo->findAll();
        $publicPath = $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/pack/';
        return $this->render('pack/consult.html.twig',
        ['lesPacks'=> $lesPacks, 'publicPath'=>$publicPath ]);
    }

    #[Route('/pack/delete/{id}', name: 'delete_pack')]

    public function deleteP(Request $request, $id): Response
    {
        $pack=$this->getDoctrine()
        ->getRepository(Pack::class)
        ->find($id);
        if(!$pack){
            throw $this->createNotfoundException(
                'No pack found for id'.$id
            );
        }
        $entityManager=$this->getDoctrine()->getManager();
        $certif=$entityManager->getRepository(Certification::class);
        $lesCertifs=$entityManager
        ->getRepository(Certification::class)
        ->findBy(['Pack'=>$pack]);

        foreach($lesCertifs as $c){
            $entityManager->remove($c);
        }
        $entityManager->remove($pack);
        $entityManager->flush();
        return $this->redirectToRoute('consult_pack');
    }

    #[Route('/pack/edit/{id}', name: 'edit_pack', methods: ['GET','POST'])]

    public function editP(Request $request, $id)
    { 
        $publicPath = "uploads/pack/";
        $pack = new Pack();
        $pack = $this->getDoctrine()->getRepository(Pack::class) ->find($id);
        if (!$pack) {
            throw $this->createNotFoundException(
            'No pack found for id '.$id
            );
        }
        $form = $this->createForm("App\Form\PackType", $pack);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $image = $form->get('image')->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $imageName = $pack->getLibelleP().'.'.$image->guessExtension();
            $image->move($publicPath,$imageName);
            $pack->setImage($imageName);
            $entityManager->flush();
            return $this->redirectToRoute('consult_pack');
        }
        return $this->render('pack/add.html.twig',
        ['f' => $form->createView()] );
    }
}

