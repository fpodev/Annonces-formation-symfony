<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnonceContactType;
use App\Repository\AnnoncesRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @Route("/annonces", name="annonces_")
 * @package App\controller
 */

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/details/{slug}", name="details")
     */
    public function details($slug, AnnoncesRepository $annoncesRepo, Request $request, MailerInterface $mailer)
    {
        $annonce = $annoncesRepo->findOneBy(['slug' => $slug]);

        if(!$annonce){
            throw new NotFoundHttpException('pas d\'annonces trouvée');            
        }

        $form = $this->createForm(AnnonceContactType::class);

        $contact = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = (new TemplatedEmail())
             ->from($contact->get('email')->getData())
             ->to($annonce->getUsers()->getEmail())
             ->subject('contact au sujet de votre annonce"' . $annonce->getTitle() . '"')
             ->htmlTemplate('emails/contact_annonce.html.twig')
             ->context([
                'annonce' => $annonce,
                'mail' =>$contact->get('email')->getData(),
                'message' => $contact->get('message')->getdata()    
             ]);
            $mailer->send($email); 

            $this->addFlash('message', 'Votre e-mail à bien été envoyé');
            return $this->redirectToRoute('annonces_details', [
                'slug' => $annonce->getSlug()
                ]);
        }

        return $this->render('annonces/detail.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);        
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
