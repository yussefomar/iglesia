<?php

namespace EMM\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchivoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
                ->add('description','textarea', array('attr' => array('class' => 'tinymce')))
                ->add('status')
                ->add('createdAt')
                ->add('updatedAt')
                ->add('archivo',FileType::class)
                ->add('save','submit',array('label'=>'guardar'))   ;;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EMM\UserBundle\Entity\Archivo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'emm_userbundle_archivo';
    }


}
