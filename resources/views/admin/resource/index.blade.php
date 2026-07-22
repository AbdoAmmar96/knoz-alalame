<x-layouts.admin :title="$def['label']" crumb="اسحب الصفوف لإعادة الترتيب — الترتيب هنا هو ترتيب الظهور في الموقع">

<x-slot:actions>
  <a href="{{ route('admin.resource.create', $def['key']) }}" class="btn btn-primary">
    <x-admin-icon name="plus" /> إضافة {{ $def['singular'] }}
  </a>
</x-slot:actions>

<div class="card">
  @if($rows->isEmpty())
    <div class="empty">
      <div class="ic"><x-admin-icon :name="$def['icon']" /></div>
      <b>لا توجد عناصر بعد</b>
      ابدأ بإضافة أول {{ $def['singular'] }}.
      <div style="margin-top:18px">
        <a href="{{ route('admin.resource.create', $def['key']) }}" class="btn btn-primary"><x-admin-icon name="plus" /> إضافة {{ $def['singular'] }}</a>
      </div>
    </div>
  @else
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:26px"></th>
            @if(collect($def['fields'])->contains(fn ($f) => $f['type'] === 'image'))<th style="width:74px">صورة</th>@endif
            @foreach($def['columns'] as $label)<th>{{ $label }}</th>@endforeach
            <th style="width:96px">الظهور</th>
            <th style="width:150px"></th>
          </tr>
        </thead>
        <tbody data-sortable>
          @foreach($rows as $row)
            <tr data-id="{{ $row->id }}">
              <td class="grip" title="اسحب لإعادة الترتيب"><x-admin-icon name="grip" /></td>
              @if(collect($def['fields'])->contains(fn ($f) => $f['type'] === 'image'))
                <td class="thumb-cell">
                  @if($row->image)<img src="{{ img($row->image) }}" alt="" loading="lazy" />@endif
                </td>
              @endif
              @foreach($def['columns'] as $field => $label)
                <td>{{ Str::limit((string) $row->{$field}, 70) }}</td>
              @endforeach
              <td>
                <form method="POST" action="{{ route('admin.resource.toggle', [$def['key'], $row->id]) }}">
                  @csrf
                  <button type="submit" class="pill {{ $row->is_active ? 'pill-on' : 'pill-off' }}" style="border-width:1px;cursor:pointer">
                    <x-admin-icon :name="$row->is_active ? 'eye' : 'eye-off'" />
                    {{ $row->is_active ? 'ظاهر' : 'مخفي' }}
                  </button>
                </form>
              </td>
              <td class="actions">
                <a href="{{ route('admin.resource.edit', [$def['key'], $row->id]) }}" class="btn btn-ghost btn-sm"><x-admin-icon name="edit" /> تعديل</a>
                <form method="POST" action="{{ route('admin.resource.destroy', [$def['key'], $row->id]) }}"
                      style="display:inline" data-confirm="سيُحذف «{{ $row->title ?? $row->name ?? $row->question ?? $row->label }}» نهائياً. هل أنت متأكد؟">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm"><x-admin-icon name="trash" /></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <form method="POST" action="{{ route('admin.resource.reorder', $def['key']) }}" id="reorder-form">
      @csrf
      <div id="reorder-bar" hidden class="card-pad" style="border-top:1.5px solid var(--line);display:flex;align-items:center;gap:12px;flex-wrap:wrap">
        <span style="font-weight:700;font-size:.9rem">تغيّر الترتيب — احفظه ليظهر في الموقع.</span>
        <button type="submit" class="btn btn-primary btn-sm"><x-admin-icon name="check" /> حفظ الترتيب</button>
      </div>
    </form>
  @endif
</div>

</x-layouts.admin>
