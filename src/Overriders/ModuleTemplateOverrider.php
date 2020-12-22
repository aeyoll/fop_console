<?php
/**
 * Copyright (c) Since 2020 Friends of Presta
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file docs/licenses/LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to infos@friendsofpresta.org so we can send you a copy immediately.
 *
 * @author    Friends of Presta <infos@friendsofpresta.org>
 * @copyright since 2020 Friends of Presta
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License ("AFL") v. 3.0
 */

declare(strict_types=1);

namespace FOP\Console\Overriders;

use Context;
use Symfony\Component\Filesystem\Filesystem;

final class ModuleTemplateOverrider extends AbstractOverrider implements OverriderInterface
{
    /**
     * @param string $path
     *
     * @return array<string>
     */
    public function run(string $path): array
    {
        $final_path = sprintf('themes/%s/%s', $this->getThemePath(), $path);
        $fs = new Filesystem();
        if ($fs->exists($final_path) && !$this->IsForceMode()) {
            $this->hasIo() && $this->getIo()->comment("File already exists '$final_path'.");
            $abort = true;

            if ($this->isInteractiveMode() && $this->hasIo() && $this->getIo()->confirm('Overwrite existing file ?')) {
                $abort = false;
            }

            if ($abort) {
                $this->setUnsuccessful();

                return ['File not created.', 'It already exists. Use --force to bypass this protection.'];
            }
        }

        $fs->copy($path, $final_path, true);
        $this->setSuccessful();

        return ["File $final_path created"];
    }

    public function handle(string $path): bool
    {
        return fnmatch('modules/*/*.tpl', $path);
    }

    /**
     * @return string
     */
    private function getThemePath(): string
    {
        // @todo Maybe it's better to rely on the directory property
        return Context::getContext()->shop->theme->getName();
    }
}
