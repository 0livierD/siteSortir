<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ListeType;

use App\Repository\SortieRepository;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use App\services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/liste', name: 'app_user_liste')]
    #[IsGranted("ROLE_ADMIN")]
    public function ajouterListeUser(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader,
                                     UserRepository $userRepository, SiteRepository $siteRepository,
                                     UserPasswordHasherInterface $passwordHasher):Response
    {
        $listeEmailEnDouble = [];

        $form = $this->createForm(ListeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                /** @var UploadedFile $ListeFile */
                $ListeFile = $form->get('ListeFile')->getData();
                $ListeFileName = $fileUploader->upload($ListeFile, $this->getParameter('user_listes_directory'));
                $uploadsDirectory = $this->getParameter('user_listes_directory');
                $chemin = $uploadsDirectory.$ListeFileName;

                $csvFile = fopen($chemin, 'r');


                $data = [];

                while ($row = fgetcsv($csvFile)) {
                    $data[] = $row;
                }


                fclose($csvFile);

                foreach ($data as $row) {
                    foreach ($row as $item) {
                        $user = new User();
                        $tab = explode(';', $item);
                        $siteInt = intval($tab[0]);
                        $site = $siteRepository->find($siteInt);
                        $user->setSite($site);
                        $verifEmail = [$tab[1]];
                        $user->setEmail($tab[1]);
                        $motDePasseHashe = $passwordHasher->hashPassword($user, $tab[2]);
                        $user->setPassword($motDePasseHashe);
                        $user->setNom($tab[3]);
                        $user->setPrenom($tab[4]);
                        $user->setTelephone($tab[5]);
                        $user->setIsAdministrateur($tab[6]);
                        $user->setIsActif($tab[7]);
                        $user->setPhoto($tab[8]);

                        $test = $userRepository->findOneBy(['email' => $tab[1]]);

                        if ($test==null)
                        {
                            $entityManager->persist($user);
                            $entityManager->flush();
                        }else
                        {
                            $emailEnDouble = end($verifEmail);
                            $listeEmailEnDouble[] = $emailEnDouble;
                        }
                    }
                }
                $this->addFlash('success', 'La liste d\'utilisateurs a bien été importée dans la base de donnée');
            }catch(Exception $e)
            {
                $this->addFlash('erreur', 'Le fichier n\'est pas valide');
            }

        }


        return $this->renderForm('user/liste.html.twig', [
            'form' => $form,
            'emailEnDouble' => $listeEmailEnDouble
        ]);
    }


    #[Route('/creer', name: 'app_user_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/{id}/modifier', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,
                         UserPasswordHasherInterface $hashes , UserRepository $userRepository,
                         FileUploader $uploader, SortieRepository $sortieRepository): Response

    {
        // sécurisation de la route
        if($this->getUser() === $user){
            $userBase = $userRepository->find($user->getId());
            $oldPassword = $userBase->getPassword();

            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $userBase->setPassword($oldPassword);

                if ($hashes->isPasswordValid($userBase, $form->get('password')->getData()))
                {
                    if ($form->get('photoFile')->getData() != null)
                    {
                        /** @var UploadedFile $photoFile */
                        $photoFile = $form->get('photoFile')->getData();
                        $photoFileName = $uploader->upload($photoFile, $this->getParameter('user_photos_directory'));
                        $user->setPhoto($photoFileName);
                    }

                    if ($form->get('plainPassword')->getData()==null)
                    {
                        $entityManager->flush();

                        $this->addFlash('profilModif','Le profil a bien été modifié !');

                        return $this->redirectToRoute('app_user_show', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);

                    }else
                    {
                        $encodedPassword = $hashes->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        );

                        $user->setPassword($encodedPassword);

                        $entityManager->persist($user);
                        $entityManager->flush();

                        $this->addFlash('profilModif','Le mot de passe a bien été modifié !');

                        return $this->redirectToRoute('app_user_show', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);
                    }
                }else
                {
                    $this->addFlash('erreurMdp','Le mot de passe n\'est pas bon !');

                    return $this->redirectToRoute('app_user_edit', ['id'=>$user->getId()]);
                }
            }

            return $this->renderForm('user/edit.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        }

        return $this->redirectToRoute('app_sortie_index');


    }

    //routes non utilisées
 /*   #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }*/
}
