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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zantolov\AppBundle\Controller\DefaultEntityCrudController;
use Zantolov\BlogBundle\Entity\Post;
use Zantolov\MediaBundle\Entity\Image;
use Zantolov\MediaBundle\Form\ImageType;
use Zantolov\MediaBundle\Service\NamerTrait;

/**
 * @Route("/admin/image")
 */
class ImageController extends DefaultEntityCrudController
{
    use NamerTrait;

    const ROUTE_PREFIX = 'zantolov.media.image.';

    protected function getEntityClass()
    {
        return 'ZantolovMediaBundle:Image';
    }

    /**
     * @return Image
     */
    function getNewEntity()
    {
        return new Image();
    }

    /**
     * @return ImageType
     */
    function getNewEntityType()
    {
        return new ImageType();
    }

    /**
     * Lists all Image entities.
     *
     * @Route("/upload/{entityType}/{id}", name="app.image.upload")
     * @Method("POST")
     * @Template()
     */
    public function uploadProcessAction(Request $request, $entityType, $id)
    {
        if (
            empty($_FILES) &&
            empty($_POST) &&
            isset($_SERVER['REQUEST_METHOD']) &&
            strtolower($_SERVER['REQUEST_METHOD']) == 'post'
        ) {
            // catch file overload error...
            $postMax = ini_get('post_max_size'); //grab the size limits...
            return new Response("Files larger than {$postMax} are not allowed!", 413);
        }

        $uploadsDir = $this->getParameter('kernel.root_dir') . '/../web/' . $this->getParameter('uploads_dir') . '/images/default/';
        $files = $request->files->all();

        /** @var UploadedFile $file */
        foreach ($files as $file) {

            $name = $this->getFileName($file->getClientOriginalName());
            if ($extension = $this->getExtension($file)) {
                $name = sprintf('%s.%s', $name, $extension);
            }

            $file = $file->move($uploadsDir, $name);

            $entity = $this->getNewEntity();
            $entity->setActive(true);
            $entity->setImageFile($file);
            $entity->setImageName($name);
            $this->getManager()->persist($entity);

        }

        $this->getManager()->flush();

        return new Response('OK');
    }
}