<?php
//src/AppBundle/Controller/ArticleController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use AppBundle\Entity\Tag;
use AppBundle\Utilities\Paginator;



/**
 * Article controller.
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{

     /**
     * Lists all Article entities.
     *
     * @Route("/", name="article")
     * @Route("/list/{pagestart}", 
     *          name="article", 
     *          defaults={"pagestart" = 1})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($pagestart, Request $request)
    {
        $recordsperpage = 5;
        $grace = 2;
        $session = $this->getRequest()->getSession();
       
        $author = ($session->has('author')) ? $session->get('author') : 'All';      
        $tag_ids = ($session->has('tags'))? $session->get('tags') : array();
        $arRepo = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('AppBundle:Article');
        
        $count = $arRepo->countTotalRows($author, $tag_ids);
        $paginator = new Paginator($pagestart, $count, $recordsperpage, $grace);
        $pagelist = $paginator->getPagesList();

        $entities = $arRepo->findByLimitOffset($recordsperpage, $paginator->getOffset(), $author, $tag_ids);

        $author_em = $this->getDoctrine()->getManager()->getRepository('AppBundle:Author');
        $authors = $author_em->findAll();

        $tag_em = $this->getDoctrine()->getManager()->getRepository('AppBundle:Tag');
        $tags = $tag_em->findAll();

        return array(
            'entities'      => $entities,
            'pagelist'      => $pagelist,
            'pagestart'     => $pagestart,
            'lastpage'      => $paginator->getTotalPages(),
            'author_entities' => $authors,
            'tag_entities'  => $tags,
        );
    }

    /**
     * Switches out the session order-by on the repository.
     *
     * @Route("/update_article_session", name="article_update_session")
     */
    public function updateSessionAction(Request $request)
    {
        
        $session = $this->getRequest()->getSession();

        //set session for tags
        if($session->has('tags')){
            $session->remove('tags');
        }
        $tags = $request->get('tags');
        $session->set('tags', $tags);

        //set session for author_id
        if($session->has('author')){
            $session->remove('author');
        }
        $author = $request->get('author');
        if($author != 'All'){ 
            $session->set('author', $author);
        }
        

        return $this->redirectToRoute('article');
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/", name="article_create")
     * @Method("POST")
     * @Template("AppBundle:Article:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Article();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Article $entity)
    {
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     *
     * @Route("/new", name="article_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Article();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{id}", name="article_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/{id}/edit", name="article_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
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
    * Creates a form to edit a Article entity.
    *
    * @param Article $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Article $entity)
    {
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Article entity.
     *
     * @Route("/{id}", name="article_update")
     * @Method("PUT")
     * @Template("AppBundle:Article:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('article_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Article entity.
     *
     * @Route("/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Article')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Article entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('article'));
    }

    /**
     * Creates a form to delete a Article entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
