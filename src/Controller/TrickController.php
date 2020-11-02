<?php

namespace App\Controller;


use App\Entity\Trick;
use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\DateTime;


class TrickController extends AbstractController
{
    /**
     * @Route("/trick", name="trick_index")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Trick::class);
        $trick = $repository->findAll();

        return $this->render('trick/index.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/trick/new", name="trick_new")
     */
    public function new(Request $request)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $trick->setCreatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $this->addFlash(
                'notice',
                'La figure a été enregistrée.');

            return $this->redirectToRoute('trick_index');
        }
        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trick/show/{id}", name="trick_show")
     */
    public function show(Trick $trick)
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/trick/edit/{id}", name="trick_edit")
     */
    public function edit(Request $request, Trick $trick)
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $trick->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $this->addFlash(
                'notice',
                'La figure a été modifiée.');

            return $this->redirectToRoute('trick_index');
        }
        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trick/remove/{id}", name="trick_remove")
     */
    public function remove(Trick $trick)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();

        $this->addFlash(
            'notice',
            'La figure a été supprimée.');

        return $this->redirectToRoute('trick_index');
    }
}
