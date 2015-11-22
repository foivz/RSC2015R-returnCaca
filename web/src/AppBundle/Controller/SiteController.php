<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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

    /**
     * @Route("/judge/new-game", name="judge.new.game")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function judgeIndexAction(Request $request)
    {
        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Game')->find(1);
        return compact('game');
    }

    /**
     * @Route("/judge/map-drawer", name="judge.map.drawer")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function judgeDrawerAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("/judge/map", name="judge.map")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function judgeMapAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("/leaderboard", name="leaderboard")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leaderboardAction(Request $request)
    {
        $teams = $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findAll();

        uasort($teams, function (Team $a, Team $b) {
            if ($a->getPoints() == $b->getPoints()) {
                return 0;
            }
            return ($a->getPoints() > $b->getPoints()) ? -1 : 1;
        });

        return ['teams' => $teams];
    }

    /**
     * @Route("/mobile/leaderboard", name="leaderboard.mobile")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leaderboardMobileAction(Request $request)
    {
        $teams = $this->getDoctrine()->getManager()->getRepository('AppBundle:Team')->findAll();

        uasort($teams, function (Team $a, Team $b) {
            if ($a->getPoints() == $b->getPoints()) {
                return 0;
            }
            return ($a->getPoints() > $b->getPoints()) ? -1 : 1;
        });

        return ['teams' => $teams];
    }

    /**
     * @Route("/user", name="user.profile")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userProfileAction(Request $request)
    {
        $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy(['user' => $this->getUser()]);
        return compact('player');
    }


    /**
     * @Route("/start-game", name="game.start")
     * @Template
     */
    public function startGameAction(Request $request)
    {
        /** @var Game $game */
        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Game')->find(1);
        $game->setOwnerRegion1(null);
        $game->setOwnerRegion2(null);
        $game->setOwnerRegion3(null);
        $game->setOwnerRegion4(null);
        $game->setWinner(null);
        $game->setActive(true);

        $dt = new \DateTime();
        $duration = $game->getDuration();
        $dt2 = $dt->add(new \DateInterval('PT' . $duration . 'M'));
        $game->setEndTimeStamp($dt2);

        /** @var Player $player */
        foreach ($game->getTeam1()->getPlayers() as $player) {
            $player->setIsLive(true);
        }

        /** @var Player $player */
        foreach ($game->getTeam2()->getPlayers() as $player) {
            $player->setIsLive(true);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('spectate');
    }

    /**
     * @Route("/stop-game", name="game.stop")
     * @Template
     */
    public function stopGameAction(Request $request)
    {
        /** @var Game $game */
        $game = $this->getDoctrine()->getManager()->getRepository('AppBundle:Game')->find(1);
        $game->setActive(false);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('judge.new.game');
    }

    /**
     * @Route("/mobile/user", name="user.profile.mobile")
     * @Template
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userMobileProfileAction(Request $request)
    {
        $playerId = $request->get('id');

        if (!empty($playerId)) {
            $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->find($playerId);
        } else {
            $player = $this->getDoctrine()->getManager()->getRepository('AppBundle:Player')->findOneBy(['user' => $this->getUser()]);
        }

        return compact('player');
    }


}
