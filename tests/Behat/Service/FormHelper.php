<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusCmsPlugin\Behat\Service;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Mink\Session;

final class FormHelper
{
    public static function fillHiddenInput(
        Session $session,
        string $id,
        $value,
    ): void {
        try {
            $session->executeScript(
                sprintf(
                    "(function (select, value) { select.tomselect.addOption({value: value, text: value}); select.tomselect.addItem(value); })(document.getElementById('%s'), '%s');",
                    $id,
                    $value,
                ),
            );
        }
        /** @noinspection PhpRedundantCatchClauseInspection */
        catch (UnsupportedDriverActionException $ex) {
            $session->getPage()
                ->find('css', '#' . $id)
                ->setValue($value)
            ;
        }
    }
}
