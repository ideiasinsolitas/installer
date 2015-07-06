<?php

namespace Deck\Installer;

use Deck\Types\CanonicalLocationInterface;

class InfoMerger implements CanonicalLocationInterface
{

    protected $routingFiles;
    protected $settingFiles;
    protected $loader;
    protected $writer;
    protected $format;
    protected $resolver;
    protected $routing = array();
    protected $settings = array();

    public function __construct($routingFiles = null, $settingFiles = null)
    {
        $this->routingFiles = $routingFiles;
        $this->settingFiles = $settingFiles;

        $this->loader = new ResourceLoader();
        $this->writer = new ResourceWriter();
        $this->format = new JsonFormat();
        $this->resolver = new PathResolver();
    }

    protected function parseFile($filename)
    {
        $string = $this->loader->load($this->resolver->resolve($filename));
        $this->format->input($string);
        return $this->format->output();
    }

    protected function addSettings($filename)
    {
        $this->settings[] = $this->parseFile($filename);
    }

    protected function addRouting($filename)
    {
        $this->routing[] = $this->parseFile($filename);
    }

    protected function addAllSettings(array $files)
    {
        foreach ($files as $file) {
            $this->addSettings($file);
        }
    }

    protected function addAllRouting(array $files)
    {
        foreach ($files as $file) {
            $this->addRouting($file);
        }
    }

    public function save($routingFiles = null, $settingFiles = null)
    {
        if (!$routingFiles) {
            $routingFiles = $this->routingFiles;
        }

        if (!$settingFiles) {
            $settingFiles = $this->settingFiles;
        }

        $this->addAllRouting($routingFiles);
        $this->addAllSettings($settingFiles);
        $this->writer->write(self::ROUTING_CANONICAL_PATH, $this->routing);
        $this->writer->write(self::SETTINGS_CANONICAL_PATH, $this->settings);
    }
}
