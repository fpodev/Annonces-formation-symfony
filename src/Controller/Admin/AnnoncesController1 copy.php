<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 /**
  * @Route("/admin/annonces", name="admin_annonces_")
  * @package App\Controller\Admin
  */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnoncesRepository $anRepo)
    {
        return $this->render('admin/annonces/index.html.twig', [
            'annonces' => $anRepo->findAll()
        ]);
    }

     /**
     * @Route("/ajout", name="ajout")
     */
    public function ajoutAnnonces(Request $request)
    {
        $annonce = new Annonces;

        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('admin_annonces_home');
        }
       
        return $this->render('admin/annonces/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifierAnnonce(Annonces $annonce, Request $request)
    {
    
        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('admin_annonces_home');
        }
       
        return $this->render('admin/annonces/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
