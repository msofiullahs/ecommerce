<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    public function __construct(private SettingService $settingService) {}

    public function general(): Response
    {
        return Inertia::render('Settings/General', [
            'settings' => $this->settingService->getGroup('general'),
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            $this->settingService->set($key, $value, 'general');
        }
        return back()->with('success', 'General settings updated.');
    }

    public function seo(): Response
    {
        return Inertia::render('Settings/Seo', [
            'settings' => $this->settingService->getGroup('seo'),
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function updateSeo(Request $request): RedirectResponse
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            $this->settingService->set($key, $value, 'seo');
        }
        return back()->with('success', 'SEO settings updated.');
    }

    public function social(): Response
    {
        return Inertia::render('Settings/Social', [
            'settings' => $this->settingService->getGroup('social'),
        ]);
    }

    public function updateSocial(Request $request): RedirectResponse
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            $this->settingService->set($key, $value, 'social');
        }
        return back()->with('success', 'Social settings updated.');
    }

    public function banners(): Response
    {
        return Inertia::render('Settings/Banners', [
            'settings' => $this->settingService->getGroup('appearance'),
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function updateBanners(Request $request): RedirectResponse
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            $this->settingService->set($key, $value, 'appearance');
        }
        return back()->with('success', 'Appearance settings updated.');
    }
}
