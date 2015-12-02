<?php
/**
 * Created by PhpStorm.
 * User: jordann
 * Date: 12/2/2015
 * Time: 11:03 AM
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MeterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        // TODO: Implement getName() method.
        return 'meter';
    }
}