<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CatType extends AbstractType{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => "Prénom du chat",
                'attr' => ['min' => 2],
                'attr' => ['max' => 50],
                'help' => "Exemple: Tigrou"
            ])
            ->add('breed', null, [
                'label' => "Race du chat",
                'attr' => ['min' => 2],
                'attr' => ['max' => 75],
                'help' => "Exemple: Persan"
            ])
            ->add('birthDate', null, [
                'label' => "Date de naissance",
                'years' => range(2000, date('Y')),
            ])
        
            ->add('location', null, [
                'label' => "Département du chat",
                'attr' => ['min' => 2],
                'attr' => ['max' => 100],
                'help' => "Exemple: Vosges"
            ]);
            
            if($this->security->isGranted('ROLE_ADMIN')){
                $builder
                ->add('publish', null, [
                    'label' => "Publication",
                    'attr' => ["selected"],
                ]);
            }

            $builder
            ->add('image', ImageType::class)
            
            ->add('deleteImage', CheckboxType::class, [
                'label' => "Supprimer l'image",
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'date_class' => Cat::class
        ]);
    }
}

?>