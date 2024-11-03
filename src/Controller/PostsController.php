<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Users;
use App\Form\PostFormType;
use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostsController extends AbstractController
{
    private $em;
    private $postsRepository;
    private $usersRepository;
    public function __construct(EntityManagerInterface $em, PostsRepository $postsRepository, UsersRepository $usersRepository)
    {
        $this->em = $em;
        $this->postsRepository = $postsRepository;
        $this->usersRepository = $usersRepository;
    }

    // Display all Posts
    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        // findAll - SELECT * FROM posts;
        $posts = $this->postsRepository->findAll();

        // Need a Fix => to call By Id
        $users = $this->usersRepository->findAll();

        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
            'users' => $users
        ]);
    }

    // Create a Post (use an instance of the request obj => to have access all ways users can provide input to the site)
    #[Route('/posts/create', name: 'app_create_posts')]
    public function create(Request $request): Response
    {
        $posts = new Posts();
        $form = $this->createForm(PostFormType::class, $posts);

        // Handle submit btn (check if post or put request && check whether fields are valid)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Store values we got from the request inside a variable
            $newPosts = $form->getData();

            // dd($newPosts);
            //   exit;

            // Get one specific value =>  key of the image
            $image = $form->get('image')->getData();
            if ($image) {
                // Generate random id & change file ext (new var where we replace name)
                $newFileName = uniqid() . '.' . $image->guessExtension();

                try {
                    // We have an image => move it to a different location to store it (uploads)
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                // Update image from our input field to the updated image using setter of our image created in Entity
                // setImage method comes from PostsRepository
                $newPosts->setImage('/uploads/' . $newFileName);
            }

            // Persist the EntityManager interface & then flush the data (newPosts)
            $this->em->persist($newPosts);
            $this->em->flush();

            return $this->redirectToRoute('app_posts');
        }

        return $this->render('posts/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    // Edit post
    #[Route('/posts/edit/{id}', name: 'app_edit_posts')]
    public function edit($id, Request $request): Response
    {
        // dd($id);
        // exit;

        // Db call
        $post = $this->postsRepository->find($id);
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        $image = $form->get('image')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($image) {
                // Handle image upload
                if ($post->getImage() !== null) {
                    // Users are always guilty until proven innocent! ;-)
                    if (file_exists(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/' . $post->getImage()
                    )) {
                        $this->getParameter('kernel.project_dir') . '/public/uploads/' . $post->getImage();
                    }
                    $newFileName = uniqid() . '.' . $image->guessExtension();

                    try {
                        $image->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $post->setImage('/uploads/' . $newFileName);
                    $this->em->flush();

                        return $this->redirectToRoute('app_posts');
                    }

            }else {
                // dd("Ok");

                $post->setTitle($form->get('title')->getData());
                $post->setContent($form->get('content')->getData());
                $post->setDate($form->get('date')->getData());
                $post->setSlug($form->get('slug')->getData());
                $post->setUser($form->get('user')->getData());
                $post->setCategory($form->get('category')->getData());

                $this->em->flush();
                return $this->redirectToRoute('app_posts');
            }
        }

        return $this->render('posts/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }


    // Delete a post
    #[Route('/posts/delete/{id}', methods: ['GET', 'DELETE'], name: 'app_delete_posts')]
    public function delete($id): Response
    {
        $post = $this->postsRepository->find($id);
        $this->em->remove($post);
        $this->em->flush();

        return $this->redirectToRoute('app_posts');
    }


    // Display a single post
    #[Route('/posts/{id}', methods: ['GET'], name: 'app_singlepost_posts')]
    public function singlepost($id): Response
    {
        // find by id;
        $post = $this->postsRepository->find($id);

        return $this->render('posts/singlepost.html.twig', [
            'post' => $post,
        ]);
    }

}

