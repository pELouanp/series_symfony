<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/serie", name="serie_")
 */
class SerieController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SerieRepository $serieRepository): Response
    {
        // TODO: Récupérer la liste des séries en base de données
        // $serie = $serieRepository->findBy([], ['popularity' => 'DESC', 'vote' => 'DESC'], 30);
        $serie = $serieRepository->findBest();
        return $this->render('serie/index.html.twig', ["series" => $serie]);
    }

    /**
     * @Route("/{id}", name="show", requirements={"id"="\d+"})
     */
    public function show(Serie $serie): Response
    {
        if ($serie === null) {
            throw $this->createNotFoundException("Cette série n'existe pas !");
        }

        // TODO: Récupérer en base de données la série ayant l'id $id
        return $this->render('serie/show.html.twig', ['serie' => $serie]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        // $serie->setName('Mon nouveau titre');
        $serie->setDateCreated(new \DateTime());
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'La série est bien enregistrée');

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }
        return $this->render('serie/new.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }
}
