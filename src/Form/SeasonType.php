<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Serie;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('firstAirDate')
            ->add('overview')
            ->add('poster')
            ->add('tmdbId')
            ->add('serie', EntityType::class, [
                'class' => Serie::class,
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('s')
                        ->addOrderBy('s.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
