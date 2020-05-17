<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\House;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class House1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'title',
        ])
        ->add('title')
        
        ->add('keywords')
        ->add('description')
        ->add('image', FileType::class, [
            'label' => 'House Main Image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '4096k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid Image File',

                ]),
            ],

        ])
        ->add('price')
        ->add('area')
        ->add('numberroom', ChoiceType::class, [
            'choices' => [
            '1+0' => '1+0',
            '1+1' => '1+1',
            '2+0' => '2+0',
            '2+1' => '2+1',
            '3+1' => '3+1',
            '4+1' => '4+1',

            ],
        ])
        ->add('phone')
        ->add('address')
        ->add('city')
        ->add('location')
        ->add('detail',CKEditorType::class, array(
            'config'=> array(
                'uiColor'=>'#ffffff',
            ),
        ))
            ->add('updated_at')
        ->add('email')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => House::class,
        ]);
    }
}
