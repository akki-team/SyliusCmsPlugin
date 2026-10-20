<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusCmsPlugin\Behat\Page\Admin\Block;

use DMore\ChromeDriver\ChromeDriver;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Sylius\Behat\Service\Helper\AutocompleteHelper;
use Tests\BitBag\SyliusCmsPlugin\Behat\Behaviour\ContainsErrorTrait;
use Webmozart\Assert\Assert;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    public function fillField(string $field, string $value): void
    {
        $this->getDocument()->fillField($field, $value);
    }

    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function fillLink(string $link): void
    {
        $this->getDocument()->fillField('Link', $link);
    }

    public function fillContent(string $content): void
    {
        $this->getDocument()->fillField('Content', $content);
    }

    public function disable(): void
    {
        $this->getDocument()->uncheckField('Enabled');
    }

    public function associateSections(array $sectionsNames): void
    {
        Assert::isInstanceOf($this->getDriver(), ChromeDriver::class);

        foreach ($sectionsNames as $sectionName) {
            (new AutocompleteHelper())->selectByName($this->getDriver(), $this->getElement('sections')->getXpath(), $sectionName);
        }
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'sections' => '#bitbag_sylius_cms_plugin_block_sections',
        ]);
    }
}
