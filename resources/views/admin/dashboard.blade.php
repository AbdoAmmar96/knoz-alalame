<x-layouts.admin title="لوحة البيانات" crumb="نظرة عامة على الموقع والطلبات">

<div class="tiles">
  <a href="{{ route('admin.leads.index', ['status' => 'new']) }}" class="tile hot">
    <div class="ic"><x-admin-icon name="inbox" /></div>
    <b>{{ $newLeads }}</b>
    <span>طلب جديد لم يُتابَع</span>
    <small>{{ $totalLeads }} طلباً بالإجمال</small>
  </a>

  @foreach($counts as $key => $item)
    <a href="{{ route('admin.resource.index', $key) }}" class="tile">
      <div class="ic"><x-admin-icon :name="$item['icon']" /></div>
      <b>{{ $item['total'] }}</b>
      <span>{{ $item['label'] }}</span>
      <small>{{ $item['hidden'] ? $item['hidden'].' مخفي عن الموقع' : 'الكل ظاهر' }}</small>
    </a>
  @endforeach
</div>

<div class="card">
  <div class="card-pad" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding-bottom:0">
    <h2 style="font-size:1.05rem">أحدث الطلبات</h2>
    <a href="{{ route('admin.leads.index') }}" class="btn btn-ghost btn-sm">كل الطلبات</a>
  </div>

  @if($latestLeads->isEmpty())
    <div class="empty">
      <div class="ic"><x-admin-icon name="inbox" /></div>
      <b>لا توجد طلبات بعد</b>
      طلبات نموذج «اطلب عرض سعر» ستظهر هنا فور وصولها.
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>التاريخ</th><th>الاسم</th><th>الجوال</th><th>الخدمة</th><th>الحالة</th><th></th></tr>
        </thead>
        <tbody>
          @foreach($latestLeads as $lead)
            <tr>
              <td style="white-space:nowrap;color:var(--tx-3);font-size:.85rem">{{ $lead->created_at->format('Y-m-d') }}</td>
              <td><b>{{ $lead->name }}</b></td>
              <td dir="ltr" style="text-align:start">{{ $lead->phone }}</td>
              <td>{{ $lead->service ?: '—' }}</td>
              <td><span class="pill {{ $lead->status === 'new' ? 'pill-new' : 'pill-off' }}">{{ $lead->status_label }}</span></td>
              <td class="actions">
                <a href="{{ $lead->whatsapp_url }}" target="_blank" class="btn btn-ghost btn-sm">واتساب</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

</x-layouts.admin>
