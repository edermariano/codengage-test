<?php

namespace App\Form;

use App\Entity\Produto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemPedidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('produto', EntityType::class, [
                'required' => true,
                'class' => Produto::class,
                'choice_label' => 'nome',
                'placeholder' => 'Selecione...'
            ])
            ->add('produto_id', HiddenType::class, [
                'required' => true,
            ])
            ->add('quantidade', NumberType::class, [
                'required' => true,
                'scale' => 2,
                'data' => 1
            ])
            ->add('percentualDesconto', PercentType::class, [
                'required' => true,
                'label' => 'Desconto %',
                'data' => 0
            ])
            ->add('precoUnitario', MoneyType::class, [
                'required' => true,
                'currency' => 'BRL',
                'label' => 'Preço'
            ])
            ->add('total', MoneyType::class, [
                'required' => true,
                'currency' => 'BRL',
                'label' => 'Total'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => ItemPedido::class,
        ]);
    }
}
