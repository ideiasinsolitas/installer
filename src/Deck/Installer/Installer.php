<?php

namespace Deck\Installer;

use PDO;

class Installer
{

    protected $dbHandler;
    protected $pathResolver;
    protected $packages;
    protected $checker;
    protected $infoMerger;

    protected $corePackages = array(
    'system',
    'auth',
    'structure',
    'upload',
    'asset',
    'packageManager',
    'admin'
    );

    protected $isOk = true;

    protected $errorMsg;

    public function __construct($connection) 
    {

        $this->checker = new RequirementsChecker();
        $pathResolver = new PathResolver();
        $this->pathResolver = $pathResolver;
        $this->dbHandler = new DbHandler($connection, $pathResolver);
        $this->infoMerger = new InfoMerger();
    }

    public function install($packages) 
    {

        $this->setPackageList($packages);

        $this->setDatabaseConnection();

        $this->installApplicationCore();

        $this->installPackages();

        return $this->isOk;
    }

    public function getError() 
    {

        return $this->errorMsg;
    }

    public function setPackageList(PackageCollection $packages) 
    {

        $this->packages = $packages;
    }

    public function installApplicationCore() 
    {

        foreach ($this->corePackages as $packageName) {

            $package = new Package($packageName);
            $this->installPackage($package);
        }
    }

    public function installPackages() 
    {

        foreach ($this->packages as $packageName) {

            $package = new Package($packageName);
            $this->installPackage($package);
        }
    }

    public function isInstalled(Package $package) 
    {

        return false;
    }

    public function update(Package $package) 
    {

    }

    public function fixFailedUpdate(Package $package, $version) 
    {

        if (!floatval($version)) {

            throw new \InvalidArgumentException("Error Processing Request");
        }

        $filename = $this->dbHandler->getBackUpFileName($package, $version);
        $sql = file_get_contents($filename);

        $this->dbHandler->dropTables($package);

        $this->dbHandler->queryDb($sql);
    }

    public function versionCheck(Package $oldPackage, Package $newPackage) 
    {

        return (float) $oldPackage->version < (float) $newPackage->version;
    }

    public function installPackage(Package $package) 
    {

        $oldPackage = $this->isInstalled($package);

        if (!$oldPackage) {

            $this->dbHandler->createTables($package);
            $this->dbHandler->populateTables($package);
        
        } elseif ($this->versionCheck($oldPackage, $package)) {

            $this->update($package);
        }
    }

    public function createFilesArray($packages) 
    {

        $allPackages = array_merge($packages, $this->corePackages);

        foreach ($allPackages as $package) {

            $routeFile = $this->pathResolver->get();
            
            $settingsFile = $this->pathResolver->get();            
        }
    }

    public function removeInstallScript($file) 
    {

        unlink($file);
    }
}
