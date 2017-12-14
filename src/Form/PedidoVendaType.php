<?php

namespace App\Form;

use App\Entity\PedidoVenda;
use App\Entity\Pessoa;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PedidoVendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cliente', EntityType::class, [
                'required' => true,
                'class' => Pessoa::class,
                'choice_label' => 'nome',
                'placeholder' => 'Selecione...'
            ])
            ->add('items', CollectionType::class, [
                'entry_type' => ItemPedidoType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PedidoVenda::class,
        ]);
    }
}
