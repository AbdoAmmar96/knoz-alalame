<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        return view('admin.leads', [
            'leads' => Lead::query()
                ->when($status, fn ($q) => $q->where('status', $status))
                ->latest()->paginate(25)->withQueryString(),
            'status' => $status,
            'counts' => collect(Lead::STATUSES)->map(
                fn ($label, $key) => Lead::where('status', $key)->count()
            ),
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Lead::STATUSES))],
        ]));

        return back()->with('ok', 'تم تحديث حالة الطلب.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with('ok', 'تم حذف الطلب.');
    }

    /** تصدير الطلبات CSV بترميز يفتح صحيحاً في إكسل العربي. */
    public function export(): StreamedResponse
    {
        $name = 'konoz-leads-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");   // BOM
            fputcsv($out, ['التاريخ', 'الاسم', 'الجوال', 'الخدمة', 'التفاصيل', 'الحالة']);
            Lead::latest()->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $lead) {
                    fputcsv($out, [
                        $lead->created_at->format('Y-m-d H:i'),
                        $lead->name, $lead->phone, $lead->service,
                        $lead->details, $lead->status_label,
                    ]);
                }
            });
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
