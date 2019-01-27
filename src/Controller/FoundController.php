<?php

namespace App\Controller;

use App\Entity\Found;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FoundController extends AbstractController
{

    /**
     * @Route("/found")
     */
    public function new(Request $request)
    {
        $found = new Found();

        $form = $this->createFormBuilder($found)
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'Captcha'
            ))
            ->add('description', TextType::class)
            ->add('email', EmailType::class)
            ->add('when_found', DateType::class)
            ->add('where_longitude', HiddenType::class)
            ->add('where_latitude', HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Found'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $found = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($found);
            $entityManager->flush();

            return $this->redirectToRoute('form_success');
        }

        return $this->render('default/found.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/form_success", name="form_success")
     */
    public function form_success()
    {
        return new Response(
            'Succès',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

}