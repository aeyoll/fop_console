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

namespace FOP\Console\Commands;

use Exception;
use FOP\Console\Command;
use FOP\Console\Overriders\OverriderInterface;
use FOP\Console\Overriders\Provider;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeOverride extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('fop:override')
            ->setDescription('Generate a file to make an override.')
            ->addArgument('path', InputArgument::REQUIRED, 'file to override.')
            ->addOption('force', InputArgument::OPTIONAL, InputOption::VALUE_NONE, 'overwrite files without confirmation');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $path = (string) $input->getArgument('path');

        try {
            // gather overriders
            $overriders = $this->getOverriders($path, $input->getOptions(), $io);
            if (empty($overriders)) {
                $io->comment("No Overrider for path '$path' fails");
                $io->comment("Looking for a demo ? try with 'classes/README.md' ...");

                return 0;
            }

            // run overriders
            $error_messages = $success_messages = [];
            foreach ($overriders as $overrider) {
                $message = $overrider->run($path);

                $overrider->isSuccessful()
                    ? $success_messages += $message
                    : $error_messages += $message;
            }

            // display results
            empty($success_messages) ?: $io->success($success_messages);
            empty($error_messages) ?: $io->warning($error_messages);

            return 0;
        } catch (Exception $exception) {
            $io->error(["Override for '$path' failed", $exception->getMessage()]);
            // Caught Exception get rethrown at high verbosity (-vvv)
            if (OutputInterface::VERBOSITY_DEBUG === $output->getVerbosity()) {
                throw $exception;
            }

            return 1;
        }
    }

    /**
     * @param string $path
     * @param array $options
     * @param SymfonyStyle $io
     *
     * @return OverriderInterface[]
     */
    private function getOverriders(string $path, array $options, SymfonyStyle $io): array
    {
        /** @var Provider $override_provider */
        $override_provider = $this->getContainer()->get('fop.console.overrider_provider');

        return $override_provider->getOverriders($path, $options, $io);
    }
}
