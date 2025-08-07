<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('', name: 'app_index')]
    public function index(
        PageRepository $pageRepository,
    )
    {
        return $this->render('index/index.html.twig', [
            'content' => $pageRepository->getMarkdownContent($pageRepository->getFile('index')),
            'pages' => $pageRepository->getPages(),
        ]);
    }

    #[Route('/page/{name}', name: 'app_page')]
    public function page(
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
            'title' => 'This will be a title',
            'content' => $pageRepository->getMarkdownContent($file)
        ]);
    }
}
