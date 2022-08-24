<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AdvertRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // show all adverts to site visitors. Seperate in several pages, one page = six adverts
    #[Route('/', name: 'app_home')]
    public function showAll(UserRepository $userRepository, AdvertRepository $advertRepository, PaginatorInterface $paginatorInterface, Request $request): Response
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
     public function details(AdvertRepository $advertRepository, $id, UserRepository $userRepository): Response
       {
        $advert = $advertRepository->find($id);
        // option to seperate plain text in paragraphs for convenient readibility
        if ($advert) {
        $textParagraphs = explode("\r\n", $advert->getText());
       
        // show usersPhone number with advert
        $userPhone = $advert->getUser()->getPhoneNo();
    }
        return $this->render('home/detailsAdvert.html.twig', [
            'advert' => $advert,
            'id'=>$id,
            'paragraphs'=>$textParagraphs,
            'userPhone' =>$userPhone,
        ]);
    }    
}
