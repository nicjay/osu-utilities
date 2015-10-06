<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\MeterRead;
use Symfony\Component\HttpFoundation\Session\Session;

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
            ->add('date', 'date')
            ->add('rate', 'number')
            ->add('kilowatts', 'number')
            ->add('save', 'submit', array('label' => 'Create New Meter Read'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            $data = $form->getData();
            $this->saveMeterRead($data);

            /*array_push($this->meter_reads, $data);*/

            return $this->redirectToRoute('test/task_success');

        }

        return $this->render('default/meter_read_entry.html.twig', array(
            'form' => $form->createView(),
            'entries' => array('test1', 'test2'),
            ));

    }

    public function saveMeterRead(MeterRead $meter_read)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($meter_read);
        $em->flush();
    }

    /**
     * @Route("test/task_success", name="test/task_success")
     */
    public function submitted()
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MeterRead');

        $query = $repository->createQueryBuilder('m')
            ->getQuery();

        $meter_reads = $query->getResult();

      /*  $meter_read = $query->setMaxResults(1)->getOneOrNullResult();*/

       /* return new Response("Entry successfully submitted ... {$meter_read->getRate()}");*/

        return $this->render('default/meter_read_success_display.html.twig', array(
            'entries' => $meter_reads,
        ));

    }
}
