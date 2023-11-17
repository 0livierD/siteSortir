<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Filtre;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\FiltreType;
use App\Form\SortieType;
use App\Repository\EtatRepository;

use App\Repository\LieuRepository;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


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
    public function new(LieuRepository $lieuRepository,
                        EtatRepository $etatRepository,
                        VilleRepository $villeRepository ,
                        Request $request,
                        EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $user = $this->getUser();
        $site = $user->getSite();

        $lieux = $lieuRepository->findAll();
        $villes = $villeRepository->findAll();
        $etat = $etatRepository->findOneBy(['libelle' => 'Créée']);


        $sortie->setSite($site);
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          //  $lieu = $lieuRepository->find($form->get('lieu')->getData());
                       // dump($lieu);
            $sortie->setEtat($etat);
            $sortie->setOrganisateur($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'user' => $user,
            'villes'=>$villes,
            'lieux'=>$lieux,

        ]);


    }

    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(LieuRepository $lieuRepository,EtatRepository $etatRepository,VilleRepository $villeRepository,Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $lieux = $lieuRepository->findAll();
        $villes = $villeRepository->findAll();
        $etat = $etatRepository->findOneBy(['libelle' => 'Créée']);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'villes'=>$villes,
            'lieux'=>$lieux,
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
    #[Route('/getLieuxByVille/{id}', name: 'app_get_lieux_by_ville')]
    public function getLieuxByVille(int $id, LieuRepository $lieuRepository): JsonResponse
    {
        // Utilisez directement l'annotation de type pour obtenir l'id


        // Utilisez le paramètre typé plutôt que get()
        $lieux = $lieuRepository->findByVille($id);

        // Convertion des lieux en un tableau au format JSON
        $lieuxArray = [];
        foreach ($lieux as $lieu) {
            $lieuxArray[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
                // ... autres propriétés du lieu ...
            ];
        }


        return $this->json($lieuxArray);
    }

    #[Route('/miseAjourStatut/{id}', name: 'app_mise_a_jour_statut')]
    public function miseAjourStatut(int $id,Sortie $sortie,Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $id = $sortie->getId();

        // Récupérez la sortie depuis la base de données
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);

        if (!$sortie) {
            return $this->json(['error' => 'Sortie non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        // Mettez à jour le statut de la sortie (vous devrez adapter cette partie en fonction de votre logique)
        $nouveauStatut = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouverte']);
        $sortie->setEtat($nouveauStatut);

        // Enregistrez les modifications en base de données
        $entityManager->flush();

        return $this->json(['success' => 'Statut mis à jour avec succès.']);
    }

    #[Route('/inscription/{id}', name: 'app_sortie_inscription')]
    public function inscription(Sortie $sortie, EntityManagerInterface $entityManager, EtatRepository $etatRepository): Response
    {
        // vérifie que l'utilisateur ne soit pas déjà connecté
        $isInscrit = false;
        foreach ($sortie->getParticipants() as $participant)
            if ($participant === $this->getUser())
                $isInscrit = true;

        $placesRestantes = $sortie->getNbInscriptionMax() - count($sortie->getParticipants());
        if (!$isInscrit && $placesRestantes > 0) {
            /**
             * @var User $user
             */
            $user = $this->getUser();
            $sortie->addParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();


            if (--$placesRestantes == 0){
                $etatComplet = $etatRepository->findOneBy(['libelle'=>'Clôturée']);
                $sortie->setEtat($etatComplet);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }

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
