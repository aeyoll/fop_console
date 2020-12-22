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

namespace FOP\Console\Overriders;

use Exception;
use Symfony\Component\Console\Style\SymfonyStyle;

class Provider
{
    /**
     * @var array<OverriderInterface>
     */
    private $overriders = [];

    /**
     * Provider constructor.
     *
     * @param array<OverriderInterface> $overriders
     *
     * @throws \Exception
     */
    public function __construct(array $overriders)
    {
        // check that provided Overriders are really Overriders.
        array_walk($overriders, function ($overrider) {
            if (!$overrider instanceof OverriderInterface) {
                throw new Exception(__CLASS__ . ' parameter $overrider must contain ' . OverriderInterface::class . ' instances only.');
            }
        });
        $this->overriders = $overriders;
    }

    /**
     * Returns the overriders which handle this path.
     *
     * @param string $path
     * @param array<mixed> $options
     * @param SymfonyStyle $io
     *
     * @return OverriderInterface[]
     */
    public function getOverriders(string $path, array $options, SymfonyStyle $io): array
    {
        $overriders = array_filter($this->overriders, function ($overrider) use ($path) {return $overrider->handle($path); });
        // initialize overrider for interaction and behaviour (overwrite file, ...)
        array_walk(
            $overriders,
            function (OverriderInterface &$overrider) use ($options, $io) {
                $overrider->init(
                    $options['force'] ?? false,
                    $options['no-interaction'] ?? false,
                    $io ?? null);
            }
                );

        return $overriders;
    }
}
