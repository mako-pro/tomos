<?php

namespace placer\tomos\console\commands;

use mako\application\Application;
use mako\reactor\Command;

class PublishAssets extends Command
{
    /**
     * Command signature
     *
     * @var string
     */
    protected $signature = 'publish.assets';

    /**
     * Description
     *
     * @var array
     */
    protected $commandInformation = [
        'description' => 'Publish package assets by create a symbolic link."',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function execute(Application $application, $packageName = 'placer/tomos')
    {
        $package     = $application->getPackage($packageName);
        $targetPath  = $package->getPath() . '/public';
        $publicPath  = realpath(MAKO_APPLICATION_PATH . '/../public');
        $linkPath    = $publicPath . '/' . $packageName;
        $vendorName  = explode('/', $packageName)[0];
        $vendorDir   = $publicPath . '/' . $vendorName;

        if (file_exists($linkPath))
            return $this->error("The [$packageName] directory already exists!");

        if (! is_dir($vendorDir))
        {
            $old = umask(0);

            if (! @mkdir($vendorDir, 0777, true))
                return $this->error("Failed to create directory [$vendorDir]");

            umask($old);
        }

        if (! is_writable($vendorDir))
            return $this->error("Directory [$vendorDir] not writable!");

        if ($this->makeLink($targetPath, $linkPath))
            $this->write("The [$packageName] directory has been linked.");
    }

    /**
     * Create a hard link to the target file or directory
     *
     * @param  string  $target
     * @param  string  $link
     * @return void
     */
    private function makeLink($target, $link)
    {
        if (strtolower(substr(PHP_OS, 0, 3)) !== 'win')
            return symlink($target, $link);

        $mode = is_dir($target) ? 'J' : 'H';

        exec("mklink /{$mode} \"{$link}\" \"{$target}\"");
    }

}
