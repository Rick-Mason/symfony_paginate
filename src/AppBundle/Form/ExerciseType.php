<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExerciseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('muscleGroup', 'choice', array(
                    'choices' => array(
                            'Chest' => 'Chest',
                            'Back'  => 'Back',
                            'Arms'  => 'Arms',
                            'Shoulders' => 'Shoulders',
                            'Legs'  => 'Legs',
                            'Abs-Core'  => 'Abs-Core',
                        )
                ))
            ->add('description', 'textarea', array(
                    'attr' => array('class' => 'tinymce')
                ))
            ->add('youtubeUrl')
            ->add('rating', 'number', array(
                    'scale' => 1
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Exercise'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_exercise';
    }
}
