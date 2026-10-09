<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function about()
    {
        return view('dashboard.about', ['about' => Setting::forGroup('about')]);
    }

    public function updateAbout(Request $request)
    {
        $data = $request->validate([
            'title.en'   => ['required', 'string', 'max:255'],
            'title.ar'   => ['required', 'string', 'max:255'],
            'story.en'   => ['nullable', 'string', 'max:10000'],
            'story.ar'   => ['nullable', 'string', 'max:10000'],
            'mission.en' => ['nullable', 'string', 'max:2000'],
            'mission.ar' => ['nullable', 'string', 'max:2000'],
            'vision.en'  => ['nullable', 'string', 'max:2000'],
            'vision.ar'  => ['nullable', 'string', 'max:2000'],
        ]);

        foreach (['title', 'story', 'mission', 'vision'] as $field) {
            Setting::put("about.$field", $this->translated($data[$field] ?? []));
        }

        return back()->with('status', __('admin.settings_saved'));
    }

    public function contact()
    {
        return view('dashboard.contact', ['contact' => Setting::forGroup('contact')]);
    }

    public function updateContact(Request $request)
    {
        $phone = ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'];

        $data = $request->validate([
            'phone'      => $phone,
            'whatsapp'   => $phone,
            'email'      => ['nullable', 'email', 'max:255'],
            'maps_url'   => ['nullable', 'url:http,https', 'max:500'],
            'address.en' => ['nullable', 'string', 'max:500'],
            'address.ar' => ['nullable', 'string', 'max:500'],
            'hours.en'   => ['nullable', 'string', 'max:255'],
            'hours.ar'   => ['nullable', 'string', 'max:255'],
        ]);

        foreach (['phone', 'whatsapp', 'email', 'maps_url'] as $field) {
            Setting::put("contact.$field", $data[$field] ?? null);
        }
        foreach (['address', 'hours'] as $field) {
            Setting::put("contact.$field", $this->translated($data[$field] ?? []));
        }

        return back()->with('status', __('admin.settings_saved'));
    }

    /** Keep only filled en/ar values; null when both are empty. */
    private function translated(array $values): ?array
    {
        $values = array_filter(Arr::only($values, ['en', 'ar']), 'filled');

        return $values ?: null;
    }
}
