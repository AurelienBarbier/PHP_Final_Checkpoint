<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ingredient controller.
 *
 */
class IngredientController extends Controller
{
    /**
     * Lists all ingredient entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ingredients = $em->getRepository('AppBundle:Ingredient')->findAll();

        return $this->render('ingredient/index.html.twig', array(
            'ingredients' => $ingredients,
        ));
    }

    /**
     * Creates a new ingredient entity.
     *
     */
    public function newAction(Request $request)
    {
        $ingredient = new Ingredient();
        $form = $this->createForm('AppBundle\Form\IngredientType', $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('ingredient_show', array('id' => $ingredient->getId()));
        }

        return $this->render('ingredient/new.html.twig', array(
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ingredient entity.
     *
     */
    public function showAction(Ingredient $ingredient)
    {
        $deleteForm = $this->createDeleteForm($ingredient);

        return $this->render('ingredient/show.html.twig', array(
            'ingredient' => $ingredient,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays all ingredients entity.
     *
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ingredients = $em->getRepository(Ingredient::class)
            ->findAll();

        return $this->render('ingredient/list.html.twig', array(
            'ingredients' => $ingredients,
        ));
    }

    /**
     * Displays a form to edit an existing ingredient entity.
     *
     */
    public function editAction(Request $request, Ingredient $ingredient)
    {
        $deleteForm = $this->createDeleteForm($ingredient);
        $editForm = $this->createForm('AppBundle\Form\IngredientType');
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ingredient_edit', array('id' => $ingredient->getId()));
        }

        return $this->render('ingredient/edit.html.twig', array(
            'ingredient' => $ingredient,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ingredient entity.
     *
     */
    public function deleteAction(Request $request, Ingredient $ingredient)
    {
        $form = $this->createDeleteForm($ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ingredient);
            $em->flush();
        }

        return $this->redirectToRoute('ingredient_index');
    }

    /**
     * Creates a form to delete a ingredient entity.
     *
     * @param Ingredient $ingredient The ingredient entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ingredient $ingredient)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ingredient_delete', array('id' => $ingredient->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
