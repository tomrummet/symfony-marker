<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('', name: 'app_index')]
    public function index(
        PostRepository $postRepository
    ): Response
    {
        return $this->render('index/index.html.twig', [
            'content' => [],
            'pages' => [],
        ]);
    }
}
