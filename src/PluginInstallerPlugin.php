<?php

/*
 * This file is part of the phpdish/plugin-installer package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPDish\PluginInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

class PluginInstallerPlugin implements PluginInterface, Capable, CommandProvider
{
    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getCapabilities()
    {
        return [
            CommandProvider::class => __CLASS__
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCommands()
    {
        return [
            new Command\ResolvePrivatePluginCommand()
        ];
    }
}