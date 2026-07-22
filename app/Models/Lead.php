<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['name', 'phone', 'service', 'details', 'status', 'ip'];

    public const STATUSES = [
        'new' => 'جديد',
        'contacted' => 'تم التواصل',
        'won' => 'تحوّل لعميل',
        'lost' => 'لم يكتمل',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /** رابط فتح محادثة واتساب مع صاحب الطلب مباشرة. */
    public function getWhatsappUrlAttribute(): string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->phone);
        if (str_starts_with($digits, '0')) {
            $digits = '966'.ltrim($digits, '0');
        }

        return 'https://wa.me/'.$digits;
    }
}
