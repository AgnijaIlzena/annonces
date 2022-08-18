<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertFormType;
use App\Repository\AdvertRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AdvertRepository $advertRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $adverts = $paginatorInterface->paginate(
            $advertRepository->findAll(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('home/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }

    #[Route('/annonce/nouvelle', name: 'new_advert')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, AdvertRepository $advertRepository): Response
    {
		$advert = new Advert();
		$form = $this->createForm(AdvertFormType::class, $advert);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {			
            $advertRepository->add($advert, true);
            $this->addFlash('success', 'L\'annonce à bien été enregistré');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/newadvert.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/annonce/{id}', name: 'advert_details', requirements:['id' => '\d+'])]
    public function details(AdvertRepository $advertRepository, $id): Response
       {
        $advert = $advertRepository->find($id);
        $textParagraphs = explode("\r\n", $advert->getText());

        return $this->render('home/detailsAdvert.html.twig', [
            'advert' => $advert,
            'id'=>$id,
            'paragraphs'=>$textParagraphs
        ]);
    }

    #[Route('/annonce/edit/{id}', name: 'advert_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Advert $advert, Request $request, AdvertRepository $advertRepository): Response
    {
        $form = $this->createForm(AdvertFormType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advertRepository->add($advert, true);
            $this->addFlash('success', 'L\'annonce à bien été modifié');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/editadvert.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/annonce/delete/{id}', name:'advert_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Advert $advert, Request $request, AdvertRepository $advertRepository): Response
    {
	    $tokenCsrf = $request->request->get('token');

		// Vérifie si le jeton est correct avant d'effectuer une suppression
        if ($this->isCsrfTokenValid('delete-advert-'. $advert->getId(), $tokenCsrf)) {
			 $advertRepository->remove($advert, true);
	
            $this->addFlash('success', 'L\'annonce à bien été supprimé');
        }

        return $this->redirectToRoute('app_home');
    }
}
