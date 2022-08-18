<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AudioList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;

class IndexController extends AbstractController
{
    #[Route('/{collection?}/{folder?}/{file?}', name: 'homepage')]
    public function homepage(?string $collection, ?string $folder, ?string $file, AudioList $audioList, Request $request): Response
    {
        $setCookie = $collection !== null;
        $collection = $collection ?: $request->cookies->get('last_collection', $audioList->getDefaultCollection());

        if ($collection === null) {
            throw $this->createNotFoundException('No collection found');
        }

        $folders = $audioList->getFiles($collection);

        $response = $this->render('default/homepage.html.twig', [
            'current_collection' => $collection,
            'current_folder' => $folder,
            'current_file' => $file,
            'folders' => $folders
        ]);

        if ($setCookie) {
            $response->headers->setCookie(Cookie::create('last_collection', $collection));
        }

        return $response;
    }
}
