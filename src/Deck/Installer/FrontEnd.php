<?php

namespace Deck\Installer;

class FrontEnd
{

    protected $keys = array(
    'db.host',
    'db.name',
    'db.user',
    'db.password',
    'db.prefix',
    'admin.username',
    'admin.email',
    'admin.password',
    'config.timezone',
    'config.enviroment',
    'config.cache.handler',
    'config.encryption.cupher',
    'config.hash.cupher',
    'config.log.handler',
    'packages'
    );

    public function showForm()
    {

        $action = 'install.php';
        $method = 'POST';

        include dirname(__FILE__) . '/tpl/form.php';
    }

    public function processForm()
    {

        $formData = $_POST;
        $packages = array();

        if (array_keys($formData) == $this->keys && is_array($formData['packages'])) {
            $packageCollection = new PackageCollection();

            foreach ($formData['packages'] as $package) {
                $packages[] = new Package($package);
            }

            $connection = array(

            'db' => $formData[''],
            'host' => $formData[''],
            'user' => $formData[''],
            'pass' => $formData[''],
            );

            $info = array(

            'admin' => $formData[''],
            'admin' => $formData[''],
            'config' => $formData[''],
            'config' => $formData[''],
            );

            $installer = new Installer($connection);

            if ($installer->install($info, $packages)) {
                include dirname(__FILE__) . '/tpl/welcome.php';

            } else {
                $message = $installer->getError();
                $this->showForm();
            }

        } else {
            $message = 'check your info and try again';
            $this->showForm();
        }
    }

    public function index()
    {

        if (isset($_POST) && !empty($_POST)) {
            $this->processForm();
        
        } else {
            $this->showForm();
        }
    }
}
