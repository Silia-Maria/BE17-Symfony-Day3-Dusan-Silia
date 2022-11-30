<?php

namespace App\Form;

use App\Entity\Status;
use App\Entity\Recipes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ["attr" => ["placeholder" => "Name", "class" => "form-control mb-2"]])
            ->add('description', TextareaType::class, ["attr" => ["placeholder" => "Short description", "class" => "form-control mb-2"]])

            ->add('image', FileType::class, [
                "label" => "Image (jpeg,jpg, png", 'mapped' => false,  'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('serves', IntegerType::class, ["attr" => ["placeholder" => "Serves", "class" => "form-control mb-2"]])
            ->add('time', IntegerType::class, ["attr" => ["placeholder" => "Duration", "class" => "form-control mb-2"]])
            ->add('difficulty', ChoiceType::class, [
                'choices'  => [
                    'easy' => "easy",
                    'advanced' => "advanced",
                    'chef' => "chef",

                ], "attr" => ["class" => "form-control mb-2"]
            ])
            ->add('link', TextType::class,  ["attr" => ["placeholder" => "Link to Recipe", "class" => "form-control mb-2"]])
            ->add('fk_status', EntityType::class, ["class" => Status::class, "choice_label" => "name"])
            ->add('create', SubmitType::class,  ["attr" => ["class" => "btn btn-outline-dark", "value" => "create"]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Recipes::class]);
    }
}
