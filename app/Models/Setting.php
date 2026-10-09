<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Setting extends Model
{
    protected $fillable = ['key','value'];

    protected $casts=['value'=>'array'];

    /** All settings of one group, keyed without the prefix: about.title becomes ['title' => ...] */
    public static function forGroup(string $group): array
    {
        return static::where('key', 'like', $group.'.%')
            ->get()
            ->mapWithKeys(fn (self $s) => [Str::after($s->key, $group.'.') => $s->value])
            ->all();
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /** For the public site later: picks the current language, falls back to English. */
    public static function localized(mixed $value): ?string
    {
        return is_array($value)
            ? ($value[app()->getLocale()] ?? $value['en'] ?? null)
            : $value;
    }
}
