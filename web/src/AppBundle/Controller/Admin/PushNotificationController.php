<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Service\GcmService;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zantolov\BlogBundle\Entity\Post;

/**
 * @Route("/admin/notifications")
 */
class PushNotificationController extends Controller
{
    /**
     * Lists all Authority entities.
     *
     * @Route("/", name="admin_push_notification")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return [];
    }

    /**
     * Lists all Authority entities.
     *
     * @Route("/send-post/{id}", name="admin_push_notification.send.post")
     * @Method("GET")
     * @Template()
     */
    public function sendPostAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Post $post */
        $post = $em->getRepository('ZantolovBlogBundle:Post')->findOneBy(array(
            'active' => true,
            'id'     => $id,
        ));

        if (empty($post)) {
            throw $this->createNotFoundException();
        }

        /** @var EntityRepository $ids */
        $ids = $this->getDoctrine()->getManager()->getRepository('ZantolovAppBundle:User');
        $ids = $ids->createQueryBuilder('u')
            ->select('u.gcmRegistrationId')
            ->where('u.gcmRegistrationId IS NOT NULL')
            ->getQuery()->getArrayResult();

        $idValues = [];
        foreach ($ids as $id) {
            $idValues[] = $id['gcmRegistrationId'];
        }

        /** @var GcmService $gcmService */
        $gcmService = $this->get('gcm_service');
        $categories = $post->getCategories();
        $catVals = [];
        foreach ($categories as $category) {
            $catVals[] = ['id' => $category->getId(), 'name' => $category->getName()];
        }

        $result = $gcmService->sendNotification($idValues, [
            'title' => $post->getTitle(),
            'url'   => $this->generateUrl('site.post', ['slug' => $post->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
            'tags'  => $catVals,
        ]);

        $this->get('logger')->debug($result);
        $this->get('session')->getFlashBag()->add('success', 'Push notifications sent');
        return $this->redirectToRoute('blog.admin.post');
    }
}