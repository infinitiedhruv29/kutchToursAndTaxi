<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $tables = [
            'Routes' => 'taxi_routes', 'Vehicles' => 'vehicles', 'Packages' => 'packages',
            'Destinations' => 'destinations', 'FAQs' => 'faqs', 'Gallery images' => 'gallery_images',
            'Testimonials' => 'testimonials', 'Invoices' => 'invoices', 'Inquiries' => 'inquiries',
        ];
        $counts = [];
        foreach ($tables as $label => $table) {
            $counts[$label] = DB::table($table)->count();
        }
        $newInquiries = DB::table('inquiries')->where('status', 'new')->count();
        $recentInquiries = DB::table('inquiries')
            ->select('name', 'service_type', 'service_title', 'status', 'created_at')
            ->orderByDesc('created_at')->orderByDesc('id')->limit(5)->get();

        return view('admin.dashboard', compact('counts', 'newInquiries', 'recentInquiries'));
    }
}
