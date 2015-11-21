<?php

namespace AppBundle\Controller;

use Zantolov\AppBundle\Controller\EntityCrudController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Player;
use AppBundle\Form\PlayerType;

/**
 * Player controller.
 *
 * @Route("/admin/players")
 */
class PlayerController extends EntityCrudController
{

    protected function getEntityClass()
    {
        return 'AppBundle:Player';
    }/**
    * Lists all Player entities.
*
            * @Route("/", name="admin_players.index")
        * @Method("GET")
        * @Template()
    */
    public function indexAction(Request $request)
{

    return parent::baseIndexAction($request);
}
/**
    * Creates a new Player entity.
*
            * @Route("/", name="admin_players.create")
        * @Method("POST")
        * @Template("AppBundle:Player:new.html.twig")
    */
    public function createAction(Request $request)
{
    return parent::baseCreateAction(
    $request,
    new Player(),
    'admin_players.show'    );


}

    /**
    * Creates a form to create a Player entity.
    *
    * @param Player $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createCreateForm($entity)
    {
        return parent::createBaseCreateForm(
            $entity,
            new PlayerType(),
            $this->generateUrl('admin_players.create')
        );
    }
/**
    * Displays a form to create a new Player entity.
*
            * @Route("/new", name="admin_players.new")
        * @Method("GET")
        * @Template()
    */
    public function newAction()
{

    return parent::baseNewAction(new Player());
}
/**
    * Finds and displays a Player entity.
*
            * @Route("/{id}", name="admin_players.show", requirements={"id"="\d+"})
        * @Method("GET")
        * @Template()
    */
    public function showAction($id)
{

    return parent::baseShowAction($id);
}
/**
    * Displays a form to edit an existing Player entity.
*
            * @Route("/{id}/edit", name="admin_players.edit")
        * @Method("GET")
        * @Template()
    */
    public function editAction($id)
{

        return parent::baseEditAction($id);
}

    /**
    * Creates a form to edit a Player entity.
    *
    * @param Player $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createEditForm($entity)
    {
        return parent::createBaseEditForm(
            $entity,
            new PlayerType(),
            $this->generateUrl('admin_players.update', array('id' => $entity->getId()))
        );
    }
/**
    * Edits an existing Player entity.
*
            * @Route("/{id}", name="admin_players.update")
        * @Method("PUT")
        * @Template("AppBundle:Player:edit.html.twig")
    */
    public function updateAction(Request $request, $id)
{

    return parent::baseUpdateAction(
        $request,
        $id,
        $this->generateUrl('admin_players.edit', array('id' => $id))
    );
}
/**
    * Deletes a Player entity.
*
            * @Route("/{id}", name="admin_players.delete")
        * @Method("DELETE")
    */
    public function deleteAction(Request $request, $id)
{

    return parent::baseDeleteAction(
        $request,
        $id,
        $this->generateUrl('admin_players.index')
    );
}

    /**
    * Creates a form to delete a Player entity by id.
    *
    * @param mixed $id The entity id
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createDeleteForm($id)
    {
        return parent::baseCreateDeleteForm(
            $this->generateUrl('admin_players.delete', array('id' => $id))
        );
    }
}
