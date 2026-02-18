<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentGatewayController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('PaymentGateways/Index', [
            'gateways' => PaymentGateway::orderBy('sort_order')->get(),
        ]);
    }

    public function configure(PaymentGateway $paymentGateway, PaymentManager $manager): Response
    {
        $gatewayClass = new ($manager->gateway($paymentGateway->name)::class)($paymentGateway->config ?? []);

        return Inertia::render('PaymentGateways/Configure', [
            'gateway' => $paymentGateway,
            'configFields' => method_exists($gatewayClass, 'getConfigFields') ? $gatewayClass->getConfigFields() : [],
        ]);
    }

    public function update(Request $request, PaymentGateway $paymentGateway): RedirectResponse
    {
        $paymentGateway->update([
            'config' => $request->config,
            'is_sandbox' => $request->boolean('is_sandbox'),
        ]);

        return redirect()->route('admin.payment-gateways.index')->with('success', "{$paymentGateway->display_name} configuration updated.");
    }

    public function toggle(PaymentGateway $paymentGateway): RedirectResponse
    {
        $paymentGateway->update(['is_active' => !$paymentGateway->is_active]);

        $status = $paymentGateway->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "{$paymentGateway->display_name} {$status}.");
    }
}
