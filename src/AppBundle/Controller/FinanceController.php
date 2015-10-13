<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MeterToIndex;
use AppBundle\Entity\RateAdjustment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/finance/rateAdjustment/add", name="rateAdjustmentAdd")
     */
    public function newRateAdjustment(Request $request)
    {
        $rate_adjustment = new RateAdjustment();

        $form = $this->createFormBuilder($rate_adjustment)
            ->add('invoiceMonth', 'choice', array('choices' => range(1, 12)))
            ->add('invoiceYear', 'choice', array('choices' => range(date('Y'), Date('Y') - 6)))
            ->add('utilityType', 'text')
            ->add('description', 'text')
            ->add('startDate', 'date')
            ->add('save', 'submit', array('label' => 'Add Rate Adjustment'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            $data = $form->getData();

            /*array_push($this->meter_reads, $data);*/

            /* return $this->redirectToRoute('meterView');*/

        }

        return $this->render(
            'default/rate_adjustment_entry.html.twig', array(
            'form' => $form->createView(),
        ));


    }

}
