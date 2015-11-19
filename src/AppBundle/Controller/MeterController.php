<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;

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
        $this->get("logger")->info("hello meter");

        $meter = new Meter();
/*        $meter_read->setId(19);
        $meter_read->setRate("$40");*/


        $builder = $this->createFormBuilder($meter);

        $builder
            ->add('utilityType', 'choice', array(
                'placeholder' => 'Choose an option',
                'choices' => array('electrical' => 'Electrical', 'water' => 'Water', 'gas' => 'Gas', 'steam' => 'Steam'),
                'required' => false
            ))
            ->add('name', 'text', array('label' => 'Meter Name:', 'required' => false))
            ->add('description', 'text', array('label' => 'Description:', 'required' => false))
            ->add('externalId', 'text', array('label' => 'External ID:', 'required' => false))
            ->add('externalSystemName', 'text', array('label' => 'External System Name:', 'required' => false))
            ->add('ownedBy', 'text', array('label' => 'Owner:', 'required' => false))
            ->add('propertyId1', 'text', array('label' => 'Property:', 'required' => false))
            ->add('propertyId2', 'text', array('label' => 'Property 2:', 'required' => false))
            ->add('propertyId3', 'text', array('label' => 'Property 3:', 'required' => false))
            ->add('multiplier', 'text', array('label' => 'Multiplier:', 'required' => false))
            ->add('isActive', 'choice', array('label' => 'Active?', 'required' => true, 'choices' => array(true => 'Yes', false => 'No'),))
            ->add('meterLocation', 'text', array('label' => 'Location:', 'required' => false))
            ->add('save', 'submit', array('label' => 'Submit New Meter'));


        $form = $builder->getForm();


        $meter->setCreated(new \DateTime());
        $meter->setModified(new \DateTime());



       /* $meter->setCreated(date('Y-m-d H:i:s'));*/

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
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Meter');

        $query = $repository->createQueryBuilder('m')
            ->getQuery();

        $meter_entries = $query->getResult();


        return $this->render(
            'default/meter_view.html.twig', array(
            'entries' => $meter_entries,
        ));

    }

    /**
     * @Route("/meter/read/add", name="meterReadAdd")
     */
    public function newMeterRead(Request $request)
    {

        $meterRead = new MeterRead();

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Meter');

        $meters = $repository->findAll();




      /*  $meterIds = array();
        $meterNames = array();*/

        $meterChoices = array();

        $logger = $this->get('logger');
      //  $logger = $this->get('logger')->info(var_export($meterIds, true));

        foreach ($meters as $meter){
            $meterChoices[$meter->getId().' '.$meter->getName()] = $meter->getId();

           /* array_push($meterIds, $meter->getId());
            array_push($meterNames, $meter->getId().' '.$meter->getName());*/

        }
      //  $logger = $this->get('logger')->info(var_export($meterIds, true));
        $builder = $this->createFormBuilder($meterRead);

        $builder
            ->add('meterId', 'choice', array(
                'choices' => $meterChoices,
                'choices_as_values' => true,
                'placeholder' => 'Choose a Meter ID',
                'required'=>true))
            ->add('readDate', 'text', array('label' => 'Read Date:', 'required' => false, 'attr'=> array('class'=>'datepicker')))
            ->add('meterRead', 'number', array('scale'=>2, 'required' => false))
            ->add('peak', 'number', array('scale'=>2, 'required' => false))
            ->add('consumption', 'number', array('scale'=>2, 'required' => false))
            ->add('totalDollars', 'money', array('required' => false))
            ->add('save', 'submit', array('label' => 'Submit Meter Read'))
            ->getForm();

        //Convert form date string to DateTime on submit
        $builder->get('readDate')->addModelTransformer(new CallbackTransformer(
            function ($originalDescription) {
                return $originalDescription;
            },
            function ($submittedDescription) {
                return date_create_from_format('m/d/Y', $submittedDescription);
            }
        ));

        $form = $builder->getForm();

        $meterRead->setCreated(new \DateTime());
        $meterRead->setModified(new \DateTime());

        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($meterRead);
            $em->flush();

            /*array_push($this->meter_reads, $data);*/

            return $this->redirectToRoute('meterViewSingle', array('meterId' => $data->getMeterId()));

        }

        return $this->render(
            'default/meter_read_entry.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("meter/view/{meterId}", name="meterViewSingle")
     */
    public function viewMeterSingle($meterId)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Meter');

        $meter = $repository->findOneById($meterId);



        $repositoryR = $this->getDoctrine()
            ->getRepository('AppBundle:MeterRead');
        $meter_read_entries = $repositoryR->findBy(array('meterId' => $meterId));

        $logger = $this->get('logger')->info(var_export($meter_read_entries, true));

        return $this->render(
            'default/meter_view_single.html.twig', array(
            'entry' => $meter,
            'readentries' => $meter_read_entries,
        ));

    }

    /**
     * @Route("/meter/note/add", name="meterNoteAdd")
     */
    public function newMeterNote(Request $request)
    {

        return $this->render('default/meter_note_entry.html.twig');

    }

}
