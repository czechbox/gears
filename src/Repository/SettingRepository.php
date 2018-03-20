<?php
/**
 * Contains the SettingRepository class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-20
 *
 */

namespace Konekt\Gears\Repository;

use Konekt\Gears\Contracts\Backend;
use Konekt\Gears\Exceptions\UnregisteredSettingException;
use Konekt\Gears\Registry\SettingsRegistry;

class SettingRepository
{
    /** @var Backend */
    protected $backend;

    /** @var SettingsRegistry */
    private $registry;

    public function __construct(Backend $backend, SettingsRegistry $registry)
    {
        $this->backend  = $backend;
        $this->registry = $registry;
    }

    /**
     * Returns the value of a setting
     *
     * @param string $key
     *
     * @return mixed
     * @throws UnregisteredSettingException
     */
    public function get($key)
    {
        $this->verifyOrFail($key);

        return $this->backend->getSetting($key);
    }

    /**
     * Updates the value of a setting
     *
     * @param string $key
     * @param mixed  $value
     * @throws UnregisteredSettingException
     */
    public function set($key, $value)
    {
        $this->verifyOrFail($key);

        $this->backend->setSetting($key, $value);
    }

    /**
     * Deletes the value of a setting
     *
     * @param $key
     * @throws UnregisteredSettingException
     */
    public function forget($key)
    {
        $this->verifyOrFail($key);

        $this->backend->removeSetting($key);
    }

    /**
     * Returns all the saved setting values as key/value pairs
     *
     * @return array
     */
    public function all()
    {
        return $this->backend->allSettings()->all();
    }

    /**
     * Update multiple settings at once. It's OK to pass settings that have no values yet
     *
     * @param array $settings Pass key/value pairs
     * @throws UnregisteredSettingException
     */
    public function update(array $settings)
    {
        foreach ($settings as $key => $value) {
            $this->verifyOrFail($key);
        }

        $this->backend->setSettings($settings);
    }

    /**
     * Delete values of multiple settings at once.
     *
     * @param array $keys Pass an array of keys
     * @throws UnregisteredSettingException
     */
    public function delete(array $keys)
    {
        foreach ($keys as $key) {
            $this->verifyOrFail($key);
        }

        $this->backend->removeSettings($keys);
    }

    /**
     * Checks if setting with the given key was registered and throws an exception if not
     *
     * @param string $key
     * @throws UnregisteredSettingException
     */
    protected function verifyOrFail(string $key)
    {
        if (!$this->registry->has($key)) {
            throw new UnregisteredSettingException(
                sprintf(
                    'There\'s no setting registered with key `%s`',
                    $key
                    )
            );
        }
    }
}