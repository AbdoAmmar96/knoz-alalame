<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // مصيدة السبام: الحقل مخفي عن المستخدم، فامتلاؤه يعني روبوتاً.
        if ($request->filled('website')) {
            return back()->with('sent', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[\d\s+()\-]{7,}$/'],
            'service' => ['nullable', 'string', 'max:120'],
            'details' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => 'من فضلك اكتب الاسم.',
            'phone.required' => 'من فضلك اكتب رقم الجوال.',
            'phone.regex' => 'رقم الجوال غير صحيح.',
        ]);

        Lead::create($data + ['ip' => $request->ip()]);

        return back()->with('sent', true)->withFragment('quote-form');
    }
}
