<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Form\AnnoncesType;
use App\Form\CategoriesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 /**
  * @Route("/admin", name="admin_")
  * @package App\Controller\Admin
  */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

     /**
     * @Route("/categories/ajout", name="categories_ajout")
     */
    public function AjoutCategorie(Request $request)
    {
        $categorie = new Categories;

        $form = $this->createForm(CategoriesType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }
       
        return $this->render('admin/categories/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
     /**
     * @Route("/annonces/ajout", name="annonces_ajout")
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
}
