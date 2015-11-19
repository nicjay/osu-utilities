<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MeterToIndex;
use AppBundle\Entity\RateAdjustment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\MeterRead;
use Symfony\Component\HttpFoundation\Session\Session;

class FinanceController extends Controller
{

    /**
     * @Route("/finance", name="finance")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return new Response("Finance Controller");
    }

    /**
     * @Route("/finance/meterToIndex/add", name="meterToIndexAdd")
     */
    public function newMeterToIndex(Request $request)
    {
        $meter_to_index = new MeterToIndex();

        $form = $this->createFormBuilder($meter_to_index)
            ->add('meterId', 'text')
            ->add('index', 'text')
            ->add('startDate', 'date')
            ->add('endDate', 'date')
            ->add('save', 'submit', array('label' => 'Associate Meter To Index'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            $data = $form->getData();
            $this->saveMeterToIndex($data);

            /*array_push($this->meter_reads, $data);*/

           /* return $this->redirectToRoute('meterView');*/

        }

        return $this->render(
            'default/meter_to_index_entry.html.twig', array(
            'form' => $form->createView(),
        ));


    }

    public function saveMeterToIndex(MeterToIndex $meter_to_index){

        //Saves to database
    }

    /**
     * @Route("/finance/meterToIndex/view", name="meterToIndexView")
     */
    public function viewMeterToIndex(Request $request){

        // for list testing
        $meter_to_index = new MeterToIndex();

        $meter_to_index->setMeterId("ABCDEF");
        $meter_to_index->setIndex("123456");
        $meter_to_index->setStartDate("1/1/2015");
        $meter_to_index->setEndDate("1/30/2015");

        $meter_to_indexes = array($meter_to_index, $meter_to_index, $meter_to_index, $meter_to_index);

        //
        return $this->render(
            'default/meter_to_index_view.html.twig', array(
            'entries' => $meter_to_indexes,
        ));
    }


    /**
     * @Route("/finance/rateAdjustment/add", name="rateAdjustmentAdd")
     */
    public function newRateAdjustment(Request $request)
    {
        $rate_adjustment = new RateAdjustment();

        $builder = $this->createFormBuilder($rate_adjustment);

        $builder
            ->add('monthAndYear', 'text', array('label' => 'Invoice Month/Year:', 'required' => false, 'attr'=> array('class'=>'monthpicker')))
            ->add('utilityType', 'text', array('label' => 'Utility Type:', 'required' => false))
            ->add('description', 'text', array('label' => 'Description:', 'required' => false))
            ->add('startDate', 'text', array('label' => 'Start Date:', 'required' => false, 'attr'=> array('class'=>'datepicker')))
            ->add('save', 'submit', array('label' => 'Add Rate Adjustment'));

        //Convert form date string to DateTime on submit
        $builder->get('startDate')->addModelTransformer(new CallbackTransformer(
            function ($originalDescription) {
                return $originalDescription;
            },
            function ($submittedDescription) {
                return date_create_from_format('m/d/Y', $submittedDescription);
            }
        ));

        $form = $builder->getForm();

        $form->handleRequest($request);


        if ($form->isValid()) {
            // Save the task to the database

            /* @var $data RateAdjustment */
            $data = $form->getData();

            $monthAndYear = date_parse_from_format('m/Y', $data->getMonthAndYear());
            $month = $monthAndYear["month"];
            $year = $monthAndYear["year"];
            $rate_adjustment->setInvoiceMonth($month);
            $rate_adjustment->setInvoiceYear($year);

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('rateAdjustmentView');
        }

        return $this->render(
            'default/rate_adjustment_entry.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/finance/rateAdjustment/view", name="rateAdjustmentView")
     */
    public function viewRateAdjustment(Request $request){

        //Get from database
        $repository = $this->getDoctrine()->getRepository('AppBundle:RateAdjustment');

        $query = $repository->createQueryBuilder('m')->getQuery();

        $rate_adjustment_entries = $query->getResult();

        return $this->render(
            'default/rate_adjustment_view.html.twig', array(
            'entries' => $rate_adjustment_entries,
        ));
    }

}
