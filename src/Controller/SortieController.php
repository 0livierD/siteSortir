<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Filtre;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\AnnulationType;
use App\Form\FiltreType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use App\services\EtatSortie;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie_index', methods: ['GET', 'POST'])]
    public function index(SortieRepository $sortieRepository, Request $request, EtatSortie $etatSortie,
                          EtatRepository   $etatRepository, EntityManagerInterface $entityManager): Response
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

        //on récupère les filtres


        $filtres = $request->get("filtre");
        $sorties = $sortieRepository->findSearch($user, $filtres);

        foreach ($sorties as $sortie){

            $sortie = $etatSortie->miseAJourEtatDeSortie($entityManager,$etatRepository,$sortie);
        }

        $ajaxValue = $request->get('ajax');
        if (isset($ajaxValue)) {
            if($ajaxValue == 1){
                return new JsonResponse([
                    'content' => $this->renderView('sortie/_sorties.html.twig', [
                        'sorties' => $sorties,
                        'filtreForm' => $filtreForm->createView()
                    ])
                ]);
            }else if ($ajaxValue == 2)
            {
                foreach ($sorties as $sortie){

                    $sortie = $etatSortie->miseAJourEtatDeSortie($entityManager,$etatRepository,$sortie);
                }
                return new JsonResponse([
                    'content' => $this->renderView('sortie/_sorties.html.twig', [
                        'sorties' => $sorties,
                        'filtreForm' => $filtreForm->createView()
                    ])
                ]);

            }

        }


        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'filtreForm' => $filtreForm->createView()
        ]);

    }

    #[Route('/creer-sortie', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER")]
    public function new(LieuRepository         $lieuRepository,
                        EtatRepository         $etatRepository,
                        VilleRepository        $villeRepository,
                        Request                $request,
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
            $sortie->setEtat($etat);
            $sortie->setOrganisateur($user);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie a été créée avec succès!');


            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'user' => $user,
            'villes' => $villes,
            'lieux' => $lieux,

        ]);


    }

    #[Route('/{id}', name: 'app_sortie_show')]
    #[IsGranted("ROLE_USER")]
    public function show(Request $request,SortieRepository $sortieRepository, EtatSortie $etatSortie,
                         EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {

        $idSortie = $request->get('id');
        $sortie = $sortieRepository->find($idSortie);
        $sortie = $etatSortie->miseAJourEtatDeSortie($entityManager,$etatRepository,$sortie);


        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER")]
    public function edit(LieuRepository $lieuRepository, EtatRepository $etatRepository, VilleRepository $villeRepository, Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        /*
         * verification de la route
         * */


        if ($this->getUser()->getId() !== $sortie->getOrganisateur()->getId()|| $sortie->getEtat()->getLibelle() == "En cours" ||$sortie->getEtat()->getLibelle() == 'Passée' )
            return $this->redirectToRoute('app_sortie_index');

        if ($sortie->getEtat()->getLibelle() == 'Annulée')
            return $this->redirectToRoute('app_sortie_index');

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
            'villes' => $villes,
            'lieux' => $lieux,
        ]);
    }

    /*
     * Route non utilisée
     * #[Route('/delete/{id}', name: 'app_sortie_delete')]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {


        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }*/

    #[Route('/lieux-par-villes/{id}', name: 'app_get_lieux_by_ville')]
    #[IsGranted("ROLE_USER")]
    public function getLieuxByVille(int $id, LieuRepository $lieuRepository): JsonResponse
    {


        $lieux = $lieuRepository->findByVille($id);

        // Convertion des lieux en un tableau au format JSON
        $lieuxArray = [];
        foreach ($lieux as $lieu) {
            $lieuxArray[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
            ];
        }


        return $this->json($lieuxArray);
    }


    #[Route('/inscription/{id}', name: 'app_sortie_inscription')]
    #[IsGranted("ROLE_USER")]
    public function inscription(Sortie $sortie, EntityManagerInterface $entityManager, EtatRepository $etatRepository, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($sortie->getId());

        // vérifie que l'utilisateur ne soit pas déjà connecté
        $isInscrit = false;
        foreach ($sortie->getParticipants() as $participant)
            if ($participant === $this->getUser())
                $isInscrit = true;

        $placesRestantes = $sortie->getNbInscriptionMax() - count($sortie->getParticipants());
        if (!$isInscrit && $placesRestantes > 0 && $sortie->getEtat()->getLibelle() == "Ouverte") {
            /**
             * @var User $user
             */
            $user = $this->getUser();
            $sortie->addParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            if (--$placesRestantes == 0) {
                $etatComplet = $etatRepository->findOneBy(['libelle' => 'Clôturée']);
                $sortie->setEtat($etatComplet);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }



        }else{
            return $this->redirectToRoute('app_sortie_index');
        }

        return new JsonResponse(['message' => 'Inscription réussie']);
    }

    #[Route('/se-desister/{id}', name: 'app_sortie_desistement')]
    #[IsGranted("ROLE_USER")]

    public function seDesister(Sortie $sortie, EntityManagerInterface $entityManager, SortieRepository $sortieRepository, EtatSortie $etatSortie,
                               EtatRepository $etatRepository): Response

    {

        $laSortie = $sortieRepository->findUneSortieAvecParticipant($sortie);
        $sortie = $laSortie[0];

        // vérifie que l'utilisateur participe à la sortie
        $isInscrit = false;
        foreach ($sortie->getParticipants() as $participant){
            if ($participant === $this->getUser()) {
                $isInscrit = true;
            }
        }


        if ($sortie->getOrganisateur() !== $this->getUser() && $isInscrit && $sortie->getEtat()->getLibelle() != "En cours" && $sortie->getEtat()->getLibelle() != "Annulée" && $sortie->getEtat()->getLibelle() != "Passée") {

            $user = $this->getUser();
            $sortie->removeParticipant($user);
            $etatSortie = new EtatSortie();
            $sortie = $etatSortie->miseAJourEtatDeSortie($entityManager,$etatRepository,$sortie);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }else{
            return $this->redirectToRoute('app_sortie_index');
        }


        return new JsonResponse(['message' => 'desistement']);

    }

    #[Route('/annuler/{id}', name: 'app_sortie_annuler')]
    #[IsGranted("ROLE_USER")]
    public function annuler(Sortie $sortie, Request $request, EntityManagerInterface $entityManager): Response
    {
        /*
        * Vérification de route
        * User session = organisateur
        *
        * Etat de sortie antérieure à en cours
        * */
        $libelleEtat = $sortie->getEtat()->getLibelle();
        if ($this->getUser() !== $sortie->getOrganisateur() || $libelleEtat = !"En cours" || $libelleEtat = !"Annulée" || $libelleEtat = !"Passée") {
            return $this->redirectToRoute('app_sortie_index');
        }

        $form = $this->createForm(AnnulationType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('app_sortie_index');
        }


        return $this->render('sortie/annulation.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView()
        ]);

    }


}
