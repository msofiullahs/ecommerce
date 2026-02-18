<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Product Management
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class)->except(['show']);

// Stock Management
Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::get('/stock/movements', [StockController::class, 'movements'])->name('stock.movements');
Route::post('/stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');

// Order Management
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

// Promo Management
Route::resource('promos', PromoController::class)->except(['show']);

// Reports
Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('/reports/products', [ReportController::class, 'products'])->name('reports.products');
Route::get('/reports/sales/export/excel', [ReportController::class, 'exportSalesExcel'])->name('reports.sales.export.excel');
Route::get('/reports/sales/export/pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.sales.export.pdf');
Route::get('/reports/products/export/excel', [ReportController::class, 'exportProductsExcel'])->name('reports.products.export.excel');

// Settings
Route::get('/settings/general', [SettingController::class, 'general'])->name('settings.general');
Route::put('/settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');
Route::get('/settings/seo', [SettingController::class, 'seo'])->name('settings.seo');
Route::put('/settings/seo', [SettingController::class, 'updateSeo'])->name('settings.seo.update');
Route::get('/settings/social', [SettingController::class, 'social'])->name('settings.social');
Route::put('/settings/social', [SettingController::class, 'updateSocial'])->name('settings.social.update');
Route::get('/settings/banners', [SettingController::class, 'banners'])->name('settings.banners');
Route::put('/settings/banners', [SettingController::class, 'updateBanners'])->name('settings.banners.update');

// Email Templates
Route::get('/email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
Route::get('/email-templates/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
Route::put('/email-templates/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('email-templates.update');

// Payment Gateways
Route::get('/payment-gateways', [PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
Route::get('/payment-gateways/{paymentGateway}/configure', [PaymentGatewayController::class, 'configure'])->name('payment-gateways.configure');
Route::put('/payment-gateways/{paymentGateway}', [PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
Route::put('/payment-gateways/{paymentGateway}/toggle', [PaymentGatewayController::class, 'toggle'])->name('payment-gateways.toggle');

// CMS Pages
Route::resource('pages', PageController::class)->except(['show']);

// User Management
Route::resource('users', UserController::class);
