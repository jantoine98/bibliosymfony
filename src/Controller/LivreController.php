<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivreRepository;
use App\Form\LivreType;

/**
 * @Route("/admin")
 */
class LivreController extends AbstractController
{
    /**
     * @Route("/livre", name="livre")
     */
    public function index(LivreRepository $livreRepository): Response
    {
        $liste_livres = $livreRepository->findAll();
        return $this->render('livre/index.html.twig', [
            'livres' => $liste_livres,
        ]);
    }

    /**
     * @Route("/livre/ajouter", name="livre_ajouter")
     */
    public function nouveau(Request $request, EntityManagerInterface $em)
    {
        $livre = new Livre;
        $formLivre = $this->createForm(LivreType::class, $livre);
        $formLivre->handleRequest($request);
        if($formLivre->isSubmitted() && $formLivre->isValid())
        {
            if($fichier = $formLivre->get("couverture")->getData())
            {
                $destination = $this->getParameter("dossier_images");
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $nomFichier);
                $nouveauNom .= "_" . uniqid() . "." . $fichier->guessExtension();
                $fichier->move($destination, $nouveauNom);
                $livre->setCouverture($nouveauNom);
            }
            $em->persist($livre);
            $em->flush();
            $this->addFlash("success", "Le nouveau livre a bien été ajouté");
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/ajouter.html.twig", ["formLivre" => $formLivre->createView()]);
    }

    /**
     * @Route("/livre/modifier/{id}", name="livre_modifier")
     */
    public function maj(Request $request,EntityManagerInterface $em,livreRepository $lr,$id)
    {
        $livre = $lr->find($id);
        $formLivre = $this->createForm(LivreType::class, $livre);
        $formLivre->handleRequest($request);
        if($formLivre->isSubmitted() && $formLivre->isValid())
        {
            if($fichier = $formLivre->get("couverture")->getData())
            {
                $destination = $this->getParameter("dossier_images");
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $nomFichier);
                $nouveauNom .= "_" . uniqid() . "." . $fichier->guessExtension();
                $fichier->move($destination, $nouveauNom);
                $livre->setCouverture($nouveauNom);
            }
            $em->persist($livre);
            $em->flush();
            $this->addFlash("success", "Le livre a bien été modifié");
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/ajouter.html.twig", ["formLivre" => $formLivre->createView()]);

    }

    /**
     * @Route("/livre/ajouter", name="livre_ajouter_v1")
     */
    public function ajouter(Request $request, EntityManagerInterface $em)
    {
        if($request->request->has("titre"))
        {
            $titre = $request->request->get("titre");
        }
        if($request->request->has("auteur"))
        {
            $auteur = $request->request->get("auteur");
        }


        if(!empty($titre) && !empty($auteur))
        {
            $nouveauLivre = new Livre;
            $nouveauLivre->setTitre($titre);
            $nouveauLivre->setAuteur($auteur);
            
            

            $em->persist($nouveauLivre);
            $em->flush();

            $this->addFlash("success","Le nouveau livre a bien été enregistré");
            return $this->redirectToRoute("livre");
        }

        return $this->render("livre/formulaire.html.twig");
    }
    
    public function modifier(LivreRepository $livreRepository,Request $request,EntityManagerInterface $em,$id)
    {
        $livreAmodifier = $livreRepository->find($id);
        if($request->isMethod("POST"))
        {
            $titre = $request->get("titre");
            $auteur = $request->get("auteur");
            if(!empty($titre) && !empty($auteur))
            {
                $livreAmodifier->setTitre($titre);
                $livreAmodifier->setAuteur($auteur);

                $em->flush();
                return $this->addFlash("success","Le livre n°$id a bien été modifié");
                return $this->redirectToRoute("livre");
            }
            else
            {
                $this->addFlash("danger","Le titre et/ou l'auteur ne peuvent pas être vide");
            }
        }
        return $this->render("livre/formulaire.html.twig",["livre" => $livreAmodifier]);
    }
    /**
     * @Route("/livre/supprimer/{id}", name="livre_supprimer")
     */
    public function supprimer(LivreRepository $livreRepository,Request $request,EntityManagerInterface $em,$id)
    {
        $livreAsupprimer = $livreRepository->find($id);
        if($request->isMethod("POST"))
        {
            $em->remove($livreAsupprimer);
            $em->flush();
            $this->addFlash("success","Le livre n°$id a bien été supprimé");
            return $this->redirectToRoute("livre");
            
            
        }
        return $this->render("livre/supprimer.html.twig", ["livre" => $livreAsupprimer]);
    }
    /**
     * @Route("/livre/fiche/{id}", name="livre_fiche")
     */
    public function fiche(LivreRepository $livreRepository, $id)
    {
        $livre = $livreRepository->find($id);
        return $this->render("livre/fiche.html.twig",["livre" => $livre]);
    }
}
