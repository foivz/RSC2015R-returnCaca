<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('team1')
            ->add('team2')
            ->add('winner')
;
}

/**
* @param OptionsResolverInterface $resolver
*/
public function setDefaultOptions(OptionsResolverInterface $resolver)
{
$resolver->setDefaults(array(
'data_class' => 'AppBundle\Entity\Game'
));
}

/**
* @return string
*/
public function getName()
{
return 'appbundle_game';
}
}
