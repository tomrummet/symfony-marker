<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Tomrummet\MarkerBundle\Repository\PageRepository;
use Tomrummet\MarkerBundle\Repository\PostRepository;

final class PageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        PageRepository $pageRepository,
        PostRepository $postRepository,
    ): Response {
        return $this->render('index/index.html.twig', [
            'content' => $pageRepository->getMarkdownContent($pageRepository->getFile('index')->getPathname()),
            'pages' => $pageRepository->getPages(),
            'posts' => $postRepository->getPosts(5),
        ]);
    }

    #[Route('/page/{name}', name: 'marker_page')]
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

        return $this->render('marker/page/index.html.twig', [
            'title' => rtrim($file->getFilename(), '.md'),
            'content' => $pageRepository->getMarkdownContent($file)
        ]);
    }
}
