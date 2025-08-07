<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/page/{name}', name: 'app_page')]
    public function index(
        string $name,
        PageRepository $pageRepository,
    ): Response {
        $file = $pageRepository->getFile(
            name: $name,
        );

        if (!$file) {
            throw $this->createNotFoundException();
        }

        return $this->render('page/index.html.twig', [
            'controller_name' => $name . 'PageController',
            'content' => $pageRepository->getMarkdownContent($file)
        ]);
    }
}
