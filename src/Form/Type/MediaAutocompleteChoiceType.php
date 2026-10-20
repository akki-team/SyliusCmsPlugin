<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCmsPlugin\Form\Type;

use BitBag\SyliusCmsPlugin\Entity\MediaInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\AdminBundle\Form\Type\TranslatableAutocompleteType;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

#[AsEntityAutocompleteField(
    alias: 'bitbag_sylius_cms_plugin_media',
    route: 'sylius_admin_entity_autocomplete',
)]
final class MediaAutocompleteChoiceType extends AbstractType
{
    public function __construct(private readonly string $mediaClass)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => $this->mediaClass,
            'choice_label' => 'name',
            'choice_value' => 'code',
            'media_type' => null,
        ]);

        $resolver->setAllowedValues('media_type', [
            MediaInterface::IMAGE_TYPE,
            MediaInterface::FILE_TYPE,
            MediaInterface::VIDEO_TYPE,
            null,
        ]);

        $resolver->setNormalizer('extra_options', fn (Options $options, array $extraOptions): array => null === $options['media_type'] ? $extraOptions : array_merge($extraOptions, ['media_type' => $options['media_type']]));

        $resolver->setNormalizer('filter_query', function (Options $options, ?callable $filterQuery): ?callable {
            $mediaType = $options['media_type'] ?? $options['extra_options']['media_type'] ?? null;
            if (null === $mediaType) {
                return $filterQuery;
            }

            return function (QueryBuilder $queryBuilder, string $query, EntityRepository $repository) use ($filterQuery, $mediaType): void {
                if (null !== $filterQuery) {
                    $filterQuery($queryBuilder, $query, $repository);
                }

                $queryBuilder
                    ->andWhere(sprintf('%s.type = :mediaType', TranslatableAutocompleteType::ENTITY_ALIAS))
                    ->setParameter('mediaType', $mediaType)
                ;
            };
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['media_type'] = $options['media_type'];
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_media_autocomplete_choice';
    }

    public function getParent(): string
    {
        return TranslatableAutocompleteType::class;
    }
}
