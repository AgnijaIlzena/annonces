<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertFormType;
use App\Repository\AdvertRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{  
    // show intro text for user in users accounts' space 
    #[Route('/account', name: 'app_account')]
    #[IsGranted('ROLE_USER')]
    public function show(): Response
    {
        $greeting = 'Bonjour, ';        

        return $this->render('account/index.html.twig', [
            'greeting' => $greeting,
         ]);
    }

    // create new advert, attach it to particular user 
    #[Route('/account/annonce/nouvelle', name: 'new_advert')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, AdvertRepository $advertRepository): Response
    {
		$advert = new Advert();
		$form = $this->createForm(AdvertFormType::class, $advert);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {	
            $advert->setUser($this->getUser());		
            $advertRepository->add($advert, true);
            $this->addFlash('success', 'L\'annonce à bien été enregistré');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/newadvert.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // edit existing advert, by user who owns it
    #[Route('/account/annonce/edit/{id}', name: 'advert_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Advert $advert, AdvertRepository $advertRepository, Request $request): Response
    {
        $form = $this->createForm(AdvertFormType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advertRepository->add($advert, true);
            $this->addFlash('success', 'L\'annonce à bien été modifié');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/editadvert.html.twig', [
            'form' => $form->createView()
        ]);
    }

     // delete existing advert, by user who owns it
    #[Route('/account/annonce/delete/{id}', name:'advert_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Advert $advert, Request $request, AdvertRepository $advertRepository): RedirectResponse
    {
	    $tokenCsrf = $request->request->get('token');

		// Check if jeton is relevant before deleting
        if ($this->isCsrfTokenValid('delete-advert-'. $advert->getId(), $tokenCsrf)) {
			 $advertRepository->remove($advert, true);
	
            $this->addFlash('success', 'L\'annonce à bien été supprimé');
        }

        return $this->redirectToRoute('app_account');
    }
}
