<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Repository\AnnoncesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/annonces", name="annonces_")
 * @package App\controller
 */

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/details/{slug}", name="details")
     */
    public function details($slug, AnnoncesRepository $annoncesRepo)
    {
        $annonce = $annoncesRepo->findOneBy(['slug' => $slug]);

        if(!$annonce){
            throw new NotFoundHttpException('pas d\'annonces trouvée');            
        }

        return $this->render('annonces/detail.html.twig', compact('annonce'));        
    }

    /**
     * @Route("/favori/ajout/{id}", name="ajout_favori")
     */
    public function ajoutFavori(Annonces $annonce)
    {       
        if(!$annonce){
            throw new NotFoundHttpException('pas d\'annonces trouvée');            
        }
        $annonce->addFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();

        return $this->redirectToRoute('app_home');        
    } 

    /**
     * @Route("/favori/retrait/{id}", name="retrait_favori")
     */
    public function retraitFavori(Annonces $annonce)
    {       
        if(!$annonce){
            throw new NotFoundHttpException('pas d\'annonces trouvée');            
        }
        $annonce->removeFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();

        return $this->redirectToRoute('app_home');        
    } 
}
