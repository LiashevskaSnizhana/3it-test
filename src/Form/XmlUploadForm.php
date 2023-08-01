<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class XmlUploadForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('xmlFile', TextType::class, ['label' => 'Vložte odkaz na zdroj dat v XML formátu'])
            ->add('save', SubmitType::class, ['label' => 'Uložit'])
        ;
    }
}