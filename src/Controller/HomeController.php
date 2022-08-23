<?php

namespace App\Controller;

use App\Repository\AdvertRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // show all adverts to site visitors. Seperate in several pages, one page = six adverts
    #[Route('/', name: 'app_home')]
    public function showAll(AdvertRepository $advertRepository, PaginatorInterface $paginatorInterface, Request $request): Response
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

    // single advert page to explore details
    #[Route('/annonce/{id}', name: 'advert_details', requirements:['id' => '\d+'])]
    public function details(AdvertRepository $advertRepository, $id): Response
       {
        $advert = $advertRepository->find($id);
        // option to seperate plain text in paragraphs for convenient readibility
        $textParagraphs = explode("\r\n", $advert->getText());

        return $this->render('home/detailsAdvert.html.twig', [
            'advert' => $advert,
            'id'=>$id,
            'paragraphs'=>$textParagraphs
        ]);
    }    
}
