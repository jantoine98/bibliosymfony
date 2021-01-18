<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(): Response
    {
        /*return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);*/
        return $this->render("base.html.twig");
    }
    /**
     * @Route("/test/salutations", name="test_salutation")
     */
    public function salutations()
    {
        return $this->render("test/salutations.html.twig");
    }

    /*Exo : créer une nouvelle route "/test/calcul"
    //      pour l'affichage d'une nouvelle test/calcul.html.twig
    //          title : calcul
                 h1 : Résultat du calcul
                contenu : 5 * 3 = 15
    */

    /**
     * Route paramétrée : {a} : partie de l'URL  pourra prendre n'importe quelle valeur. Pour pouvoir utiliser cette valeur, on doit l'indiquer comme paramètre     
     * 
     * * @Route("/test/calcul/{a}/{b}", name="test_calcul")
     */
    public function calcul($a,$b)
    {
        //$a = 5;
        //$b = 2;
        //Exo : afficher aussi le résultat de a + b , a/b et a - b
        return $this->render("test/calcul.html.twig", [ "a" => $a, "b" => $b ]);
    }

    /**
     * @Route("/test/affichage", name="test_affichage")
     */
    public function affichage()
    {
        $tableau = ["nom" => "Menfin", "prenom" => "gérard", "age" => 30, "ville" => "Paris"];
        $tableau2 = ["bonjour", 5, true,46,false];
        //dump($tableau); dd($tableau);
        return $this->render("test/affichage.html.twig",["tab" => $tableau]);
        
    }
    /**
     * @Route("/test/affichage/objet", name="test_affichage_objet")
     */
    public function affichageObjet()
    {
        $obj = new stdClass;
        $obj->nom = "Cérien";
        $obj->prenom = "Jean";
        $obj->age = "16";
        $obj->ville = "Marseille";
        return $this->render("test/affichage.html.twig",["tab" => $obj]);
    }
}
