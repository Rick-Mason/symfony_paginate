<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Exercise;
use AppBundle\Form\ExerciseType;
use AppBundle\Utilities\Paginator;

/**
 * Exercise controller.
 *
 * @Route("/exercise")
 */
class ExerciseController extends Controller
{

    /**
     * Lists all Exercise entities.
     *
     * @Route("/list/{pagestart}/{musclegroup}", 
     *          name="exercise", 
     *          defaults={"pagestart" = 1, "musclegroup" = "All"})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($pagestart, $musclegroup, Request $request)
    {

        $groupArray = ['All', 'Chest', 'Back', 'Arms', 'Shoulders', 'Legs', 'Abs-Core'];
        $group = $request->getSession()->get('group');
        $recordsperpage = 10;
        $grace = 5;

        $exRepo = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('AppBundle:Exercise');
        
        $count = $exRepo->countTotalRows($group);
        $paginator = new Paginator((int)$pagestart, $count, $recordsperpage, $grace);
        $pagelist = $paginator->getPagesList();

        $entities = $exRepo->findByLimitOffset($recordsperpage, $paginator->getOffset(), $group);

        return array(
            'entities'      => $entities,
            'pagelist'      => $pagelist,
            'groupArray'    => $groupArray,
            'pagestart'     => $pagestart,
            'lastpage'      => $paginator->getTotalPages(),
        );
    }


    /**
     * Switches out the session order-by on the repository.
     *
     * @Route("/update_session", name="exercise_update_session")
     */
    public function updateSessionAction(Request $request)
    {
        $musclegroup = $request->get('musclegroup');
        $session = $request->getSession()->set('group', $musclegroup);
        return $this->redirectToRoute('exercise');
        
    }

    
    /**
     * Creates a new Exercise entity.
     *
     * @Route("/", name="exercise_create")
     * @Method("POST")
     * @Template("AppBundle:Exercise:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Exercise();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('exercise_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Exercise entity.
     *
     * @param Exercise $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Exercise $entity)
    {
        $form = $this->createForm(new ExerciseType(), $entity, array(
            'action' => $this->generateUrl('exercise_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Exercise entity.
     *
     * @Route("/new", name="exercise_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Exercise();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Exercise entity.
     *
     * @Route("/{id}", name="exercise_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Exercise')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Exercise entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Exercise entity.
     *
     * @Route("/{id}/edit", name="exercise_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Exercise')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Exercise entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Exercise entity.
    *
    * @param Exercise $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Exercise $entity)
    {
        $form = $this->createForm(new ExerciseType(), $entity, array(
            'action' => $this->generateUrl('exercise_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Exercise entity.
     *
     * @Route("/{id}", name="exercise_update")
     * @Method("PUT")
     * @Template("AppBundle:Exercise:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Exercise')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Exercise entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('exercise_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Exercise entity.
     *
     * @Route("/{id}", name="exercise_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Exercise')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Exercise entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('exercise'));
    }

    /**
     * Creates a form to delete a Exercise entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('exercise_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
