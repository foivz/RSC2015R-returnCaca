<?php

namespace AppBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zantolov\AppBundle\Entity\User;
use Zantolov\BlogBundle\Entity\Category;
use Zantolov\BlogBundle\Entity\Post;
use Zantolov\BlogBundle\Repository\PostRepository;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class SiteController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        /** @var QueryBuilder $posts */
        $posts = $this->getDoctrine()->getRepository('ZantolovBlogBundle:Post')->getActivePostsQueryBuilder();
        $posts->setMaxResults(3);
        $posts = $posts->getQuery()->getResult();

        return compact('posts');
    }


    /**
     * @Route("/setLocale/{locale}", name="site.setLocale")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeLocaleAction($locale)
    {
        $this->get('session')->set('_locale', $locale);
        return $this->redirectToRoute('homepage');
    }


    /**
     * @Route("/p/{slug}", name="site.post")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postDetailsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var PostRepository $postRepo */
        $postRepo = $em->getRepository('ZantolovBlogBundle:Post');
        $post = $postRepo->getPostBySlug($slug);
        if (empty($post)) {
            throw $this->createNotFoundException();
        }
        $post = $post[0];
        return compact('post');
    }


    /**
     * @Route("/c/{slug}", name="site.category")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryIndexAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Category $category */
        $category = $em->getRepository('ZantolovBlogBundle:Category')->findOneBy(array(
            'active' => true,
            'slug'   => $slug,
        ));

        if (empty($category)) {
            throw $this->createNotFoundException();
        }

        /** @var PostRepository $postRepo */
        $postRepo = $em->getRepository('ZantolovBlogBundle:Post');
        $posts = $postRepo->getPostsByCategory($category);

        uasort($posts, function (Post $a, Post $b) {
            if ($a->getPublishedAt() == $b->getPublishedAt()) {
                return 0;
            }
            return ($a->getPublishedAt() > $b->getPublishedAt()) ? -1 : 1;
        });

        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate($posts, $request->query->getInt('page', 1)/*page number*/, 9);

        return compact('category', 'posts');
    }


    /**
     * @Route("/s/", name="site.search")
     * @Template
     * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('query');

        $em = $this->getDoctrine()->getManager();

        /** @var PostRepository $postRepo */
        $postRepo = $em->getRepository('ZantolovBlogBundle:Post');
        $posts = $postRepo->getActivePostsQueryBuilder()
            ->andWhere('p.body LIKE :query')
            ->setParameter('query', "%$query%")
            ->orWhere('p.intro LIKE :query')
            ->setParameter('query', "%$query%")
            ->orWhere('p.title LIKE :query')
            ->setParameter('query', "%$query%")
            ->getQuery()
            ->getResult();

        uasort($posts, function (Post $a, Post $b) {
            if ($a->getPublishedAt() == $b->getPublishedAt()) {
                return 0;
            }
            return ($a->getPublishedAt() > $b->getPublishedAt()) ? -1 : 1;
        });

        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate($posts, $request->query->getInt('page', 1)/*page number*/, 9);

        return compact('posts');

    }

}
