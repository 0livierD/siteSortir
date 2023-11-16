<?php

namespace App\Controller;

use App\Entity\Filtre;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\FiltreType;
use App\Form\SortieType;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;


class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie_index', methods: ['GET', 'POST'])]
    public function index(SortieRepository $sortieRepository, Request $request): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $filtre = new Filtre();
        $filtreForm = $this->createForm(FiltreType::class, $filtre);
        $filtreForm->handleRequest($request);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($filtreForm->isSubmitted() && $filtreForm->isValid()) {
            $filtre = $filtreForm->getData();
            $sorties = $sortieRepository->findSearch($filtre, $user);


            return $this->render('sortie/index.html.twig', [
                'sorties' => $sorties,
                'filtreForm' => $filtreForm->createView()
            ]);

        }


        $sorties = $sortieRepository->findAllUnder1Month();


        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'filtreForm' => $filtreForm->createView()
        ]);

    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('security/login.html.twig');

        /*
         * , [
            'sortie' => $sortie,
            'form' => $form,
        ]
         * */
    }

    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_sortie_delete')]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        dd('Fait lors du changement du bouton sur le homepage, enlever le dd var autoriser le bouton à prendre cette route');
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/inscription/{id}', name: 'app_sortie_inscription')]
    public function inscription(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        // vérifie que l'utilisateur ne soit pas déjà connecté
        $isInscrit = false;
        foreach ($sortie->getParticipants() as $participant)
            if ($participant === $this->getUser())
                $isInscrit = true;

        if (!$isInscrit && count($sortie->getParticipants()) < $sortie->getNbInscriptionMax()) {
            /**
             * @var User $user
             */
            $user = $this->getUser();
            $sortie->addParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sortie_index');
    }

    #[Route('//se-desister/{id}', name: 'app_sortie_desistement')]
    public function seDesister(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {

        // vérifie que l'utilisateur participe à la sortie
        $isInscrit = false;
        foreach ($sortie->getParticipants() as $participant)
            if ($participant === $this->getUser())
                $isInscrit = true;


        if ($sortie->getOrganisateur() !== $this->getUser() && $isInscrit) {
            /**
             * @var User $user
             */
            $user = $this->getUser();
            $sortie->removeParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_sortie_index');
    }

}
