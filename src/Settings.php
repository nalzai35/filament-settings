<?php

namespace Nalzai35\FilamentSettings;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Nalzai35\FilamentSettings\Models\Setting;

class Settings implements ConfigRepository
{
    protected array $items = [];

    /**
     * Create a new configuration repository.
     *
     * @param  array  $items
     * @return void
     */
    public function __construct(array $items = [])
    {
        $this->items = $items ?: $this->fillItems();
    }

    private function fillItems()
    {
        return Cache::rememberForever('settings', function () {
            return $this->getSetting()
                ->all()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    private function getSetting(): Setting
    {
        return new Setting();
    }

    public function has($key): bool
    {
        return Arr::has($this->items, $key);
    }

    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMany($key);
        }

        return Arr::get($this->items, $key, $default);
    }

    public function getMany($keys): array
    {
        $config = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }

            $config[$key] = Arr::get($this->items, $key, $default);
        }

        return $config;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Cache::forget('settings');
            \Nalzai35\FilamentSettings\Models\Setting::set($key, $value);
        }
    }

    public function prepend($key, $value)
    {
        $array = $this->get($key, []);

        array_unshift($array, $value);

        $this->set($key, $array);
    }

    public function push($key, $value)
    {
        $array = $this->get($key, []);

        $array[] = $value;

        $this->set($key, $array);
    }
}
