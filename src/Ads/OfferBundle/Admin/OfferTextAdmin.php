<?php

namespace Ads\OfferBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class OfferTextAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('status')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('status')
            ->add('createdAt')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Главное')
            ->add('title')
            ->add('shortText', 'textarea', array('label'=>'Короткий текст', 'required'=>false, 'attr' => array('class' => 'ckeditor')))
            ->add('text', 'textarea', array('label'=>'Большой текст', 'required'=>false, 'attr' => array('class' => 'ckeditor')))
            ->add('firstTextBlock', 'textarea', array('label'=>'Внутри текста', 'required'=>false, 'attr' => array('class' => 'ckeditor')))
            ->add('status', 'choice', array(
                'label' => 'Статус',
                'choices' => array(0 => 'скрыт', 1 => 'виден')))
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('shortText')
            ->add('text')
            ->add('status')
            ->add('createdAt')
        ;
    }
}
