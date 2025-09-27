<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Tomrummet\MarkerBundle\Repository\PostRepository;

final class PostController extends AbstractController
{
    #[Route('/posts', name: 'marker_posts')]
    public function index(
        PostRepository $postRepository
    ): Response {
        return $this->render('marker/post/index.html.twig', [
            'content' => '',
            'posts' => $postRepository->getPosts(),
        ]);
    }

    #[Route('/posts/{slug}', name: 'marker_post')]
    public function post(
        string $slug,
        PostRepository $postRepository,
    ): Response {
        return $this->render('marker/post/post.html.twig', [
            'post' => $postRepository->getPost($slug),
        ]);
    }
}
