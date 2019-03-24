<?php

namespace App\Controller\Rest;
use App\Entity\Article;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityNotFoundException;
class ArticleController extends FOSRestController
{

    /**
     * Creates an Article resource
     * @Rest\Post("/articles")
     * @param Request $request
     * @return View
     */
    public function postArticle(Request $request): View
    {
        /* var_dump($request); die('errror'); */
        $entityManager = $this->getDoctrine()->getManager();
        $article = new Article();
        $article->setTitle($request->get('title'));
        $article->setContent($request->get('content'));
        /* $this->articleRepository->save($article); */
        $entityManager->persist($article);
        $entityManager->flush();
        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($article, Response::HTTP_CREATED);
    }
    /**
     * Retrieves an Article resource
     * @Rest\Get("/articles/{articleId}")
     */
    public function getArticle(int $articleId): View
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            throw new EntityNotFoundException('Article with id '.$articleId.' does not exist!');
        }
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($article, Response::HTTP_OK);
    }
    /**
     * Retrieves a collection of Article resource
     * @Rest\Get("/articles")
     */
    public function getArticles(): View
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articleRepository->findAll();
        // In case our GET was a success we need to return a 200 HTTP OK response with the collection of article object
        return View::create($articles, Response::HTTP_OK);
    }

    /**
     * Replaces Article resource
     * @Rest\Put("/articles/{articleId}")
     */
    public function putArticle(int $articleId, Request $request): View
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            throw new EntityNotFoundException('Article with id ' . $articleId . ' does not exist!');
        }
            $article->setTitle($request->get('title'));
            $article->setContent($request->get('content'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

        // In case our PUT was a success we need to return a 200 HTTP OK response with the object as a result of PUT
        return View::create($article, Response::HTTP_OK);
    }

    /**
     * Removes the Article resource
     * @Rest\Delete("/articles/{articleId}")
     */
    public function deleteArticle(int $articleId): View
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            throw new EntityNotFoundException('Article with id '.$articleId.' does not exist!');
        }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

        // In case our DELETE was a success we need to return a 204 HTTP NO CONTENT response. The object is deleted.
        return View::create([], Response::HTTP_NO_CONTENT);
    }

}
