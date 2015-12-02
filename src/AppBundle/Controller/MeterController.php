<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meter;
use AppBundle\Entity\MeterRead;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Form\Type\MeterType;

class MeterController extends Controller
{

    public function saveObject($object)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
        ));
    }

    /**
     * @Route("/meter/add", name="meterAdd")
     */
    public function newMeter(Request $request)
    {
        $meter = new Meter();

        /*$builder = $this->createFormBuilder($meter);
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
        $form = $builder->getForm();*/
        $form = $this->createForm(new MeterType(), $meter);

        $meter->setCreated(new \DateTime());
        $meter->setModified(new \DateTime());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $this->saveObject($data);
            return $this->redirectToRoute('meterView');
        }

        return $this->render(
            'default/meter_entry.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/meter/read/add", name="meterReadAdd")
     */
    public function newMeterRead(Request $request)
    {
        $meterRead = new MeterRead();

        $repository = $this->getDoctrine()->getRepository('AppBundle:Meter');

        $meters = $repository->findAll();

        $meterChoices = array();

        foreach ($meters as $meter) {
            $meterChoices[$meter->getId() . ' ' . $meter->getName()] = $meter->getId();

        }

        $builder = $this->createFormBuilder($meterRead);

        $builder
            ->add('meterId', 'choice', array(
                'choices' => $meterChoices,
                'choices_as_values' => true,
                'placeholder' => 'Choose a Meter ID',
                'required' => true))
            ->add('readDate', 'text', array('label' => 'Read Date:', 'required' => false, 'attr' => array('class' => 'datepicker')))
            ->add('meterRead', 'number', array('scale' => 2, 'required' => false))
            ->add('peak', 'number', array('scale' => 2, 'required' => false))
            ->add('consumption', 'number', array('scale' => 2, 'required' => false))
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
            $data = $form->getData();
            $this->saveObject($data);
            return $this->redirectToRoute('meterViewSingle', array('meterId' => $data->getMeterId()));
        }

        return $this->render(
            'default/meter_read_entry.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/meter/note/add", name="meterNoteAdd")
     */
    public function newMeterNote(Request $request)
    {
        return $this->render('default/meter_note_entry.html.twig');
    }

    /**
     * @Route("meter/view", name="meterView")
     */
    public function viewMeter(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Meter');
        $query = $repository->createQueryBuilder('m')->getQuery();
        $meter_entries = $query->getResult();

        return $this->render(
            'default/meter_view.html.twig', array(
            'entries' => $meter_entries,
        ));

    }

    /**
     * @Route("meter/view/{meterId}", name="meterViewSingle")
     */
    public function viewMeterSingle($meterId)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Meter');

        $meter = $repository->findOneById($meterId);

        $repositoryR = $this->getDoctrine()->getRepository('AppBundle:MeterRead');
        $meter_read_entries = $repositoryR->findBy(array('meterId' => $meterId));

        return $this->render(
            'default/meter_view_single.html.twig', array(
            'entry' => $meter,
            'readentries' => $meter_read_entries,
        ));

    }



}
