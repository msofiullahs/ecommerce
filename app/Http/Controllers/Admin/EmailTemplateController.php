<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailTemplateController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('EmailTemplates/Index', [
            'templates' => EmailTemplate::all(),
        ]);
    }

    public function edit(EmailTemplate $emailTemplate): Response
    {
        return Inertia::render('EmailTemplates/Edit', [
            'template' => $emailTemplate,
            'locales' => config('ecommerce.locales'),
        ]);
    }

    public function update(Request $request, EmailTemplate $emailTemplate): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|array',
            'body' => 'required|array',
        ]);

        $emailTemplate->update($request->only(['subject', 'body', 'is_active']));

        return redirect()->route('admin.email-templates.index')->with('success', 'Email template updated.');
    }
}
