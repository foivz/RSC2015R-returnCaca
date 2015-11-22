<?php

namespace AppBundle\Controller;

use Zantolov\AppBundle\Controller\EntityCrudController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Game;
use AppBundle\Form\GameType;

/**
 * Game controller.
 *
 * @Route("/admin/games")
 */
class GameController extends EntityCrudController
{

    protected function getEntityClass()
    {
        return 'AppBundle:Game';
    }

    /**
     * Lists all Game entities.
     *
     * @Route("/", name="admin_games.index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {

        return parent::baseIndexAction($request);
    }

    /**
     * Creates a new Game entity.
     *
     * @Route("/", name="admin_games.create")
     * @Method("POST")
     * @Template("AppBundle:Game:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::baseCreateAction(
            $request,
            new Game(),
            'admin_games.show');


    }

    /**
     * Creates a form to create a Game entity.
     *
     * @param Game $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm($entity)
    {
        return parent::createBaseCreateForm(
            $entity,
            new GameType(),
            $this->generateUrl('admin_games.create')
        );
    }

    /**
     * Displays a form to create a new Game entity.
     *
     * @Route("/new", name="admin_games.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {

        return parent::baseNewAction(new Game());
    }

    /**
     * Finds and displays a Game entity.
     *
     * @Route("/{id}", name="admin_games.show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        return parent::baseShowAction($id);
    }

    /**
     * Displays a form to edit an existing Game entity.
     *
     * @Route("/{id}/edit", name="admin_games.edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {

        return parent::baseEditAction($id);
    }

    /**
     * Creates a form to edit a Game entity.
     *
     * @param Game $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm($entity)
    {
        return parent::createBaseEditForm(
            $entity,
            new GameType(),
            $this->generateUrl('admin_games.update', array('id' => $entity->getId()))
        );
    }

    /**
     * Edits an existing Game entity.
     *
     * @Route("/{id}", name="admin_games.update")
     * @Method("PUT")
     * @Template("AppBundle:Game:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        return parent::baseUpdateAction(
            $request,
            $id,
            $this->generateUrl('admin_games.edit', array('id' => $id))
        );
    }

    /**
     * Deletes a Game entity.
     *
     * @Route("/{id}", name="admin_games.delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {

        return parent::baseDeleteAction(
            $request,
            $id,
            $this->generateUrl('admin_games.index')
        );
    }

    /**
     * Creates a form to delete a Game entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return parent::baseCreateDeleteForm(
            $this->generateUrl('admin_games.delete', array('id' => $id))
        );
    }
}
