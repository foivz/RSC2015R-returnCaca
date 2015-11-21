<?php

namespace AppBundle\Controller;

use Zantolov\AppBundle\Controller\EntityCrudController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Team;
use AppBundle\Form\TeamType;

/**
 * Team controller.
 *
 * @Route("/admin/teams")
 */
class TeamController extends EntityCrudController
{

    protected function getEntityClass()
    {
        return 'AppBundle:Team';
    }/**
    * Lists all Team entities.
*
            * @Route("/", name="admin_teams.index")
        * @Method("GET")
        * @Template()
    */
    public function indexAction(Request $request)
{

    return parent::baseIndexAction($request);
}
/**
    * Creates a new Team entity.
*
            * @Route("/", name="admin_teams.create")
        * @Method("POST")
        * @Template("AppBundle:Team:new.html.twig")
    */
    public function createAction(Request $request)
{
    return parent::baseCreateAction(
    $request,
    new Team(),
    'admin_teams.show'    );


}

    /**
    * Creates a form to create a Team entity.
    *
    * @param Team $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createCreateForm($entity)
    {
        return parent::createBaseCreateForm(
            $entity,
            new TeamType(),
            $this->generateUrl('admin_teams.create')
        );
    }
/**
    * Displays a form to create a new Team entity.
*
            * @Route("/new", name="admin_teams.new")
        * @Method("GET")
        * @Template()
    */
    public function newAction()
{

    return parent::baseNewAction(new Team());
}
/**
    * Finds and displays a Team entity.
*
            * @Route("/{id}", name="admin_teams.show", requirements={"id"="\d+"})
        * @Method("GET")
        * @Template()
    */
    public function showAction($id)
{

    return parent::baseShowAction($id);
}
/**
    * Displays a form to edit an existing Team entity.
*
            * @Route("/{id}/edit", name="admin_teams.edit")
        * @Method("GET")
        * @Template()
    */
    public function editAction($id)
{

        return parent::baseEditAction($id);
}

    /**
    * Creates a form to edit a Team entity.
    *
    * @param Team $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createEditForm($entity)
    {
        return parent::createBaseEditForm(
            $entity,
            new TeamType(),
            $this->generateUrl('admin_teams.update', array('id' => $entity->getId()))
        );
    }
/**
    * Edits an existing Team entity.
*
            * @Route("/{id}", name="admin_teams.update")
        * @Method("PUT")
        * @Template("AppBundle:Team:edit.html.twig")
    */
    public function updateAction(Request $request, $id)
{

    return parent::baseUpdateAction(
        $request,
        $id,
        $this->generateUrl('admin_teams.edit', array('id' => $id))
    );
}
/**
    * Deletes a Team entity.
*
            * @Route("/{id}", name="admin_teams.delete")
        * @Method("DELETE")
    */
    public function deleteAction(Request $request, $id)
{

    return parent::baseDeleteAction(
        $request,
        $id,
        $this->generateUrl('admin_teams.index')
    );
}

    /**
    * Creates a form to delete a Team entity by id.
    *
    * @param mixed $id The entity id
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createDeleteForm($id)
    {
        return parent::baseCreateDeleteForm(
            $this->generateUrl('admin_teams.delete', array('id' => $id))
        );
    }
}
