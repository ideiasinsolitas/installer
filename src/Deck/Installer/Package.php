<?php

namespace Deck\Installer;

class Package
{

    protected $name;
    protected $dir;
    protected $version;
    protected $author;
    protected $description;
    protected $url;

    protected $tables;
    protected $requirements;
    protected $files;

    protected $tablesSchemaFile;
    protected $valuesFile;

    public function __construct($name)
    {
        $this->name = $name;

        $this->dir = dirname(__FILE__) . '/../Package/' . ucfirst($name);

        $this->buildPackagePath();
        $this->loadSchema();
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    public function __get($key)
    {
        return $this->$key;
    }

    public function __toString()
    {
        return $this->name;
    }

    protected function loadSchema()
    {
        $path = $this->dir;
        $fullFileName = $path . '/package.json';

        $schema = file_get_contents($fullFileName);

        $this->parseSchema($schema);
    }

    protected function parseSchema($schema)
    {
        $schemaArray = json_decode($schema);
        
        $this->tables = $schemaArray[''];
        $this->tablesSchemaFile = $schemaArray[''];
        $this->valuesFile = $schemaArray[''];
        $this->requirements = $schemaArray[''];
    }

    public function checkRequirements()
    {
        $basePath = dirname(__FILE__) . '/../Package/';
        $missing = array();

        foreach ($this->requirements as $packageName) {
            $filename = $basePath . $packageName . '/package.json';
            if (!file_exists($filename)) {
                $missing[] = $packageName;
            }
        }

        if (empty($missing)) {
            return true;
        }

        return $missing;
    }
}
