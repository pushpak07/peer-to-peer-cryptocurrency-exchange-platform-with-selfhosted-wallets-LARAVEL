<?php

namespace HolluwaTosin\Installer\Helpers;

use Jackiedo\DotenvEditor\DotenvEditor;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envGroup;

    /**
     * Editor instance
     *
     * @var DotenvEditor
     */
    protected $editor;

    /**
     * EnvironmentManager constructor.
     * @param DotenvEditor $editor
     */
    public function __construct(DotenvEditor $editor)
    {
        $this->envGroup = config(
            'installer.environment'
        );

        $this->editor = $editor;
    }

    /**
     * Get the content of the .env file.
     *
     * @return array
     */
    public function getContent()
    {
        $contents = $this->envGroup;

        foreach ($contents as $group => $env) {

            $keys = array_keys($contents[$group]);
            $env = $this->editor->getKeys($keys);

            foreach ($env as $key => $data) {
                $contents[$group][$key]['value'] = $data['value'];
            }
        }

        return $contents;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        $keys = [];

        foreach ($this->envGroup as $group => $env) {
            $keys = array_merge(
                array_keys($this->envGroup[$group]), $keys
            );
        }

        return $keys;
    }

    /**
     * @return array
     */
    public function getKeyPairs()
    {
        $pairs = [];

        foreach ($this->envGroup as $group => $env) {
            $pairs = array_merge(
                $this->envGroup[$group], $pairs
            );
        }

        return $pairs;
    }

    /**
     * Save environment inputs
     *
     * @param $inputs
     */
    public function save($inputs)
    {
        $inputs = collect($inputs)
            ->filter(function ($value, $key) {
                return in_array(
                    $key, $this->getKeys()
                );
            })->toArray();

        foreach ($inputs as $key => $value) {
            $this->editor->setKey($key, $value);
        }

        $this->editor->save();
    }
}
