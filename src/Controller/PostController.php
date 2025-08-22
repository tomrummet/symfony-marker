<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_posts')]
    public function index(
        PostRepository $postRepository
    ): Response {
        return $this->render('marker/post/index.html.twig', [
            'content' => '',
            'posts' => $postRepository->getPosts(),
        ]);
    }

    #[Route('/posts/{slug}', name: 'app_post')]
    public function post(
        string $slug,
        PostRepository $postRepository,
    ): Response {
        return $this->render('marker/post/post.html.twig', [
            'post' => $postRepository->getPost($slug),
        ]);
    }
}
