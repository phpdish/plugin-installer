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
use Composer\Config\JsonConfigSource;
use Composer\Json\JsonFile;
use Composer\Package\RootPackageInterface;
use PHPDish\PluginInstaller\PluginInstaller;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ResolvePrivatePluginCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('handle');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $package = $this->getComposer()->getPackage();
        $pluginMetas = $this->findPluginSources();

        $rootJsonConfigSource = $this->createJsonConfigSource('composer.json');

    }

    public function findPluginSources()
    {
        $pluginMetas = [];
        $foundFiles = (new Finder())->in(PluginInstaller::PLUGIN_PATH . '/*/')->depth('<=1')->name('composer.json');
        foreach ($foundFiles as $file) {
            $decoded = JsonFile::parseJson(file_get_contents($file->getRealPath()));
            if (!$decoded || !isset($decoded['name']) || !isset($decoded['type']) || $decoded['type'] !== 'phpdish-plugin') {
                continue;
            }
            $version = 'dev-master';
            if (isset($decoded['extra']['phpdish-plugin']['version'])) {
                $version = $decoded['extra']['phpdish-plugin']['version'];
            }
            $pluginMetas[$decoded['name']] = [
                'name' => $decoded['name'],
                'version' => $version,
                'dir' => $file->getPath(),
            ];
        }
        return $pluginMetas;
    }

    /**
     * @param RootPackageInterface $rootPackage
     * @package array $pluginMetas
     */
    protected function mergePluginPackages($rootPackage, $pluginMetas)
    {
        $rootConfigSource = $this->createJsonConfigSource('composer.json');
        foreach ($rootPackage->getRepositories() as $index => $repository) {
            if (
                $repository['type'] === 'package' &&
                array_key_exists($repository['package']['name'], $pluginMetas)
            ) {
                $rootConfigSource->removeRepository($index);
            }
        }

        $total = count($rootPackage->getRepositories());
        foreach ($pluginMetas as $pluginMeta) {
            $rootConfigSource->addRepository((string)$total ++, [
                'type' => 'package',
                'package' => [
                    'name' => $pluginMeta['name'],
                    'version' => $pluginMeta['version'],
                    'dist' => [
                        'url' => $pluginMeta['name'],
                        'type' => 'path'
                    ]
                ]
            ]);
        }
    }

    /**
     * @param string $filePath
     * @return JsonConfigSource
     */
    protected function createJsonConfigSource($filePath)
    {
        $jsonFile = new JsonFile($filePath);
        return new JsonConfigSource($jsonFile);
    }
}