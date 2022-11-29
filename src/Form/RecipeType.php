<?php

namespace App\Form;

use App\Entity\Recipes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ["attr" => ["placeholder" => "Name", "class" => "form-control mb-2"]])
            ->add('description', TextareaType::class, ["attr" => ["placeholder" => "Short description", "class" => "form-control mb-2"]])
            ->add('image', TextType::class, ["attr" => ["placeholder" => "Image URL", "class" => "form-control mb-2"]])
            ->add('serves', NumberType::class, ["attr" => ["placeholder" => "Serves", "class" => "form-control mb-2"]])
            ->add('time', NumberType::class, ["attr" => ["placeholder" => "Duration", "class" => "form-control mb-2"]])
            ->add('difficulty', ChoiceType::class, [
                'choices'  => [
                    'easy' => "easy",
                    'advanced' => "advanced",
                    'chef' => "chef",

                ], "attr" => ["class" => "form-control mb-2"]
            ])
            ->add('link', TextType::class,  ["attr" => ["placeholder" => "Link to Recipe", "class" => "form-control mb-2"]])
            ->add('create', SubmitType::class,  ["attr" => ["class" => "btn btn-outline-dark", "value" => "create"]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Recipes::class]);
    }
}
