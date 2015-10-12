<?php

namespace AppBundle\Controller;

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
     * @Route("/meterToIndex/add", name="meterToIndexAdd")
     */
    public function newMeterToIndex(Request $request)
    {

       return new Response("Meter to Index form page goes here");

    }
}
