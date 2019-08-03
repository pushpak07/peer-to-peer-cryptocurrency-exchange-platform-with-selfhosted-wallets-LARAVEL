<?php

namespace HolluwaTosin\Installer\Helpers;

class RequirementsChecker
{
    /**
     * Minimum PHP Version Supported (Override is in installer.php config file).
     *
     * @var string
     */
    private $_minPhpVersion = '7.0.0';

    /**
     * Check for the server requirements.
     *
     * @param array $requirements
     * @return array
     */
    public function check()
    {
        $results = [];

        $requirements = config('installer.requirements');

        foreach($requirements as $type => $data)
        {
            switch ($type) {
                // check php requirements
                case 'php':
                    foreach($requirements[$type] as $requirement)
                    {
                        $results['requirements'][$type][$requirement] = true;

                        if(!extension_loaded($requirement))
                        {
                            $results['requirements'][$type][$requirement] = false;

                            $results['errors'] = true;
                        }
                    }
                    break;
                // check apache requirements
                case 'apache':
                    foreach ($requirements[$type] as $requirement) {
                        // if function doesn't exist we can't check apache modules
                        if(function_exists('apache_get_modules'))
                        {
                            $results['requirements'][$type][$requirement] = true;

                            if(!in_array($requirement, apache_get_modules()))
                            {
                                $results['requirements'][$type][$requirement] = false;

                                $results['errors'] = true;
                            }
                        }
                    }
                    break;
            }
        }

        return $results;
    }

    /**
     * Check PHP version requirement.
     *
     * @param string|null $minPhpVersion
     * @return array
     */
    public function checkPHPversion()
    {
        $current = $this->getPhpVersionInfo();
        $supported = false;

        $minimum = config(
            'installer.core.minPhpVersion'
        ) ?: $this->getMinPhpVersion();

        $compare = version_compare(
            $current['version'], $minimum
        );

        if ($compare >= 0) {
            $supported = true;
        }
        return [
            'full' => $current['full'],
            'current' => $current['version'],
            'supported' => $supported,
            'minimum' => $minimum,
        ];
    }

    /**
     * Get current Php version information
     *
     * @return array
     */
    private static function getPhpVersionInfo()
    {
        preg_match(
            "#^\d+(\.\d+)*#", PHP_VERSION,$match
        );

        return [
            'full' => PHP_VERSION,
            'version' => $match[0]
        ];
    }

    /**
     * Get minimum PHP version ID.
     *
     * @return string _minPhpVersion
     */
    protected function getMinPhpVersion()
    {
        return $this->_minPhpVersion;
    }

    /**
     * Check if requirement was passed
     *
     * @return bool
     */
    public function pass()
    {
        $requirements = $this->check();
        $php = $this->checkPHPversion();

        if(isset($requirements['errors'])){
            $verify = $php['supported'] && !$requirements['errors'];
        }else{
            $verify = $php['supported'];
        }

        return $verify;
    }

}
