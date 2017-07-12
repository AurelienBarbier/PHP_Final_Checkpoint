<?php

namespace RecipesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use RecipesBundle\Entity\Recipes;
use RecipesBundle\Form\RecipesType;

/**
 * Recipes controller.
 *
 */
class RecipesController extends Controller
{
    /**
     * Lists all Recipes entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $recipes = $em->getRepository('RecipesBundle:Recipes')->findAll();

        return $this->render('recipes/index.html.twig', array(
            'recipes' => $recipes,
        ));
    }

    /**
     * Creates a new Recipes entity.
     *
     */
    public function newAction(Request $request)
    {
        $recipe = new Recipes();
        $form = $this->createForm('RecipesBundle\Form\RecipesType', $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('recipes_show', array('id' => $recipe->getId()));
        }

        return $this->render('recipes/new.html.twig', array(
            'recipe' => $recipe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Recipes entity.
     *
     */
    public function showAction(Recipes $recipe)
    {
        $deleteForm = $this->createDeleteForm($recipe);

        return $this->render('recipes/show.html.twig', array(
            'recipe' => $recipe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Recipes entity.
     *
     */
    public function editAction(Request $request, Recipes $recipe)
    {
        $deleteForm = $this->createDeleteForm($recipe);
        $editForm = $this->createForm('RecipesBundle\Form\RecipesType', $recipe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('recipes_edit', array('id' => $recipe->getId()));
        }

        return $this->render('recipes/edit.html.twig', array(
            'recipe' => $recipe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Recipes entity.
     *
     */
    public function deleteAction(Request $request, Recipes $recipe)
    {
        $form = $this->createDeleteForm($recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recipe);
            $em->flush();
        }

        return $this->redirectToRoute('recipes_index');
    }

    /**
     * Creates a form to delete a Recipes entity.
     *
     * @param Recipes $recipe The Recipes entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Recipes $recipe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('recipes_delete', array('id' => $recipe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
