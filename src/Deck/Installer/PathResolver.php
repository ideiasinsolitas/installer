<?php

namespace Deck\Installer;

use Deck\Types\CanonicalLocationInterface;

class PathResolver implements CanonicalLocationInterface
{
    protected function buildDbDirPath($package, $suffix)
    {

        return $package->dir . '/' . $suffix;
    }

    protected function getFullRoutingPath($package)
    {

        $path = $this->buildDbDirPath($package, 'schema');

        return $path . '/routes.json';
    }

    protected function getFullSettingPath($package)
    {

        $path = $this->buildDbDirPath($package, 'schema');

        return $path . '/settings.json';
    }

    protected function getFullDbTablePath($package)
    {

        $path = $this->buildDbDirPath($package, 'schema');

        return $path . '/table.sql';
    }

    protected function getFullDbValuesPath($package)
    {

        $path = $this->buildDbDirPath($package, 'schema');

        return $path . '/values.sql';
    }
}
