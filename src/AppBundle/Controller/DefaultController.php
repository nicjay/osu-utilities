<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\MeterRead;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/test", name="test")
     */
    public function newMeterRead(Request $request)
    {
        $meter_read = new MeterRead();
/*        $meter_read->setId(19);
        $meter_read->setRate("$40");*/

        $form = $this->createFormBuilder($meter_read)
            ->add('id', 'number')
            ->add('date', 'date')
            ->add('rate', 'number')
            ->add('kilowatts', 'number')
            ->add('save', 'submit', array('label' => 'Create New Meter Read'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            return $this->redirectToRoute('test/task_success');
        }

        return $this->render('default/meter_read_entry.html.twig', array(
            'form' => $form->createView(),
            ));

    }

    /**
     * @Route("test/task_success", name="test/task_success")
     */
    public function submitted()
    {
        return new Response(" " );
    }
}
