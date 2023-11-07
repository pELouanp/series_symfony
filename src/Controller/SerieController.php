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
 * @Route("/serie")
 */
class SerieController extends AbstractController
{
    /**
     * @Route("/", name="serie_index")
     */
    public function index(SerieRepository $serieRepository): Response
    {
        // Récupérer la liste des séries en base de données
        //$series = $serieRepository->findBy([], ['popularity' => 'DESC', 'vote' => 'DESC'], 30); // SELECT * FROM serie ORDER BY popularity DESC, vote DESC LIMIT 30
        $series = $serieRepository->findBest();

        return $this->render('serie/index.html.twig', [
            'series' => $series
        ]);
    }

    /**
     * @Route("/{id}", name="serie_show", requirements={"id"="\d+"})
     */
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        // Récupérer en base de données la série ayant l'id $id
        $serie = $serieRepository->find($id); // SELECT * FROM serie WHERE id = $id

        if ($serie === null) {
            throw $this->createNotFoundException("Cette série n'existe pas !");
        }

        return $this->render('serie/show.html.twig', [
            'serie' => $serie
        ]);
    }

    /**
     * @Route("/new", name="serie_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serie->setDateCreated(new \DateTime());
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if($serieForm->isSubmitted() && $serieForm->isValid()) {
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'La série est bien enregistrée');

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/new.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="serie_delete", requirements={"id"="\d+"})
     */
    public function delete(Serie $serie, SerieRepository $serieRepository)
    {
        $serieRepository->remove($serie, true);
        return $this->redirectToRoute('serie_index');
    }


}
