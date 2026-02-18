<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingService
{
    private static array $cache = [];

    public function get(string $key, mixed $default = null): mixed
    {
        if (empty(static::$cache)) {
            static::$cache = Setting::pluck('value', 'key')->toArray();
        }

        return static::$cache[$key] ?? $default;
    }

    public function set(string $key, mixed $value, string $group = 'general', string $type = 'text'): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
        static::$cache = [];
    }

    public function getGroup(string $group): Collection
    {
        return Setting::where('group', $group)->get();
    }

    public function getAllGrouped(): Collection
    {
        return Setting::all()->groupBy('group');
    }

    public static function clearCache(): void
    {
        static::$cache = [];
    }
}
