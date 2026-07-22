<x-layouts.admin title="طلبات العملاء" crumb="الطلبات الواردة من نموذج «اطلب عرض سعر»">

<x-slot:actions>
  <a href="{{ route('admin.leads.export') }}" class="btn btn-ghost"><x-admin-icon name="download" /> تصدير CSV</a>
</x-slot:actions>

<div class="tabs">
  <a href="{{ route('admin.leads.index') }}" @class(['on' => ! $status])>الكل</a>
  @foreach(\App\Models\Lead::STATUSES as $key => $label)
    <a href="{{ route('admin.leads.index', ['status' => $key]) }}" @class(['on' => $status === $key])>
      {{ $label }} ({{ $counts[$key] }})
    </a>
  @endforeach
</div>

<div class="card">
  @if($leads->isEmpty())
    <div class="empty">
      <div class="ic"><x-admin-icon name="inbox" /></div>
      <b>لا توجد طلبات في هذا التصنيف</b>
      كل طلب يصل من صفحة «تواصل معنا» يظهر هنا مباشرةً.
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>التاريخ</th><th>الاسم</th><th>الجوال</th><th>الخدمة</th>
            <th>التفاصيل</th><th style="width:160px">الحالة</th><th style="width:170px"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($leads as $lead)
            <tr>
              <td style="white-space:nowrap;color:var(--tx-3);font-size:.85rem">
                {{ $lead->created_at->format('Y-m-d') }}<br>
                <small>{{ $lead->created_at->format('H:i') }}</small>
              </td>
              <td><b>{{ $lead->name }}</b></td>
              <td dir="ltr" style="text-align:start;white-space:nowrap">{{ $lead->phone }}</td>
              <td>{{ $lead->service ?: '—' }}</td>
              <td style="max-width:320px;font-size:.88rem;color:var(--tx-2)">{{ Str::limit($lead->details, 120) ?: '—' }}</td>
              <td>
                <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
                  @csrf @method('PATCH')
                  <select name="status" onchange="this.form.submit()" style="padding:7px 10px;font-size:.84rem">
                    @foreach(\App\Models\Lead::STATUSES as $key => $label)
                      <option value="{{ $key }}" @selected($lead->status === $key)>{{ $label }}</option>
                    @endforeach
                  </select>
                </form>
              </td>
              <td class="actions">
                <a href="{{ $lead->whatsapp_url }}" target="_blank" class="btn btn-ghost btn-sm">واتساب</a>
                <a href="tel:{{ $lead->phone }}" class="btn btn-ghost btn-sm"><x-admin-icon name="phone" /></a>
                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" style="display:inline"
                      data-confirm="سيُحذف طلب «{{ $lead->name }}» نهائياً. متأكد؟">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm"><x-admin-icon name="trash" /></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

@if($leads->hasPages())
  <div class="pager">{{ $leads->links('admin.pagination') }}</div>
@endif

</x-layouts.admin>
