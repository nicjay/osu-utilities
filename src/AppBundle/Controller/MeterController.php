<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Meter;
use AppBundle\Entity\MeterRead;
use Symfony\Component\HttpFoundation\Session\Session;

class MeterController extends Controller
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
     * @Route("/meter/add", name="meterAdd")
     */
    public function newMeter(Request $request)
    {


        $meter = new Meter();
/*        $meter_read->setId(19);
        $meter_read->setRate("$40");*/

        $form = $this->createFormBuilder($meter)
            ->add('type', 'choice', array(
                'placeholder' => 'Choose an option',
                'choices' => array('electrical' => 'Electrical', 'water' => 'Water', 'gas' => 'Gas', 'steam' => 'Steam'),
                'required' => true
            ))
            ->add('property', 'text')
            ->add('property2', 'text')
            ->add('property3', 'text')
            ->add('size', 'text')
            ->add('description', 'text')
            ->add('ownedBy', 'text')
            ->add('save', 'submit', array('label' => 'Save New Meter'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            $data = $form->getData();
            $this->saveMeter($data);

            /*array_push($this->meter_reads, $data);*/

            return $this->redirectToRoute('meterView');

        }

        return $this->render(
            'default/meter_entry.html.twig', array(
            'form' => $form->createView(),
            ));

    }

    public function saveMeter(Meter $meter)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($meter);
        $em->flush();
    }

    /**
     * @Route("meter/view", name="meterView")
     */
    public function viewMeter(Request $request)
    {
        //Get from database
       /* $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Meter');

        $query = $repository->createQueryBuilder('m')
            ->getQuery();

        $meter_entries = $query->getResult();*/


        /*  $meter_read = $query->setMaxResults(1)->getOneOrNullResult();*/

        /* return new Response("Entry successfully submitted ... {$meter_read->getRate()}");*/

        //List testing
        $meter_entry = new Meter();
        $meter_entry->setType("Electrical");
        $meter_entry->setProperty("Oak Creek");
        $meter_entry->setProperty2("Reser Stadium");
        $meter_entry->setSize("1M^2");
        $meter_entry->setDescription("This is a description of a meter.");
        $meter_entry->setOwnedBy("Nick Jordan");

        $meter_entries = array($meter_entry, $meter_entry, $meter_entry);


        return $this->render(
            'default/meter_view.html.twig', array(
            'entries' => $meter_entries,
        ));

    }
}
