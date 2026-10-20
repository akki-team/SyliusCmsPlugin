<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCmsPlugin\Form\Type;

use Sylius\Bundle\AdminBundle\Form\Type\TranslatableAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

#[AsEntityAutocompleteField(
    alias: 'bitbag_sylius_cms_plugin_section',
    route: 'sylius_admin_entity_autocomplete',
)]
final class SectionAutocompleteChoiceType extends AbstractType
{
    public function __construct(private readonly string $sectionClass)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => $this->sectionClass,
            'choice_label' => 'name',
            'choice_value' => 'code',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_section_autocomplete_choice';
    }

    public function getParent(): string
    {
        return TranslatableAutocompleteType::class;
    }
}
