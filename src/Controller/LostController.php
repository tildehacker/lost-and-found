<?php

namespace App\Controller;

use App\Entity\Lost;
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


class LostController extends AbstractController
{

    /**
     * @Route("/lost")
     */
    public function new(Request $request)
    {
        $lost = new Lost();

        $form = $this->createFormBuilder($lost)
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'Captcha'
            ))
            ->add('description', TextType::class)
            ->add('email', EmailType::class)
            ->add('when_lost', DateType::class)
            ->add('where_longitude', HiddenType::class)
            ->add('where_latitude', HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Lost'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lost = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lost);
            $entityManager->flush();

            return $this->redirectToRoute('form_success');
        }

        return $this->render('default/lost.html.twig', array(
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