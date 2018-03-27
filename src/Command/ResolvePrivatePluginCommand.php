<?php

/*
 * This file is part of the phpdish/plugin-installer package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPDish\PluginInstaller\Command;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResolvePrivatePluginCommand extends BaseCommand
{

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('scan');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $package = $this->getComposer()->getPackage();

        $this->getComposer()->getPackage()->getJS;
        exit;
    }
}