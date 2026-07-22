@php
    $editing = $row->exists;
    $action = $editing
        ? route('admin.resource.update', [$def['key'], $row->id])
        : route('admin.resource.store', $def['key']);
@endphp

<x-layouts.admin
    :title="($editing ? 'تعديل ' : 'إضافة ') . $def['singular']"
    :crumb="$def['label']">

<x-slot:actions>
  <a href="{{ route('admin.resource.index', $def['key']) }}" class="btn btn-ghost">رجوع للقائمة</a>
</x-slot:actions>

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="card card-pad">
  @csrf
  @if($editing) @method('PUT') @endif

  <div class="form-grid">
    @foreach($def['fields'] as $name => $field)
      @php
        $value = old($name, match ($field['type']) {
            'list' => is_array($row->{$name}) ? implode("\n", $row->{$name}) : '',
            'datetime' => $row->{$name}?->format('Y-m-d\TH:i'),
            default => $row->{$name} ?? ($field['default'] ?? ''),
        });
      @endphp

      <div class="field">
        <label for="f-{{ $name }}">{{ $field['label'] }}</label>

        @switch($field['type'])
          @case('textarea')
          @case('list')
            <textarea id="f-{{ $name }}" name="{{ $name }}" rows="{{ $field['rows'] ?? 4 }}">{{ $value }}</textarea>
            @break

          @case('svg')
            <textarea id="f-{{ $name }}" name="{{ $name }}" class="mono" rows="4">{{ $value }}</textarea>
            @if($value)
              <div style="margin-top:10px;display:flex;align-items:center;gap:10px">
                <span style="font-size:.78rem;color:var(--tx-3)">المعاينة:</span>
                <span style="width:44px;height:44px;border-radius:12px;display:grid;place-items:center;color:#fff;
                             background:linear-gradient(145deg,var(--orange-2),var(--orange-3))">{!! $value !!}</span>
              </div>
            @endif
            @break

          @case('image')
            <div class="upload">
              <div class="preview" id="prev-{{ $name }}">
                @if($value)<img src="{{ img($value) }}" alt="" />@else<span>لا صورة</span>@endif
              </div>
              <div class="pick">
                <input type="file" name="{{ $name }}_file" accept="image/*"
                       data-preview="#prev-{{ $name }}" style="margin-bottom:8px" />
                <input type="text" id="f-{{ $name }}" name="{{ $name }}" value="{{ $value }}"
                       placeholder="أو الصق رابط صورة" dir="ltr" />
              </div>
            </div>
            @break

          @case('bool')
            <label class="switch">
              <input type="checkbox" name="{{ $name }}" value="1" @checked($value)>
              <span class="track"></span>
              نعم
            </label>
            @break

          @case('number')
            <input type="number" id="f-{{ $name }}" name="{{ $name }}" value="{{ $value }}" min="1" max="5" />
            @break

          @case('datetime')
            <input type="datetime-local" id="f-{{ $name }}" name="{{ $name }}" value="{{ $value }}" />
            @break

          @case('slug')
            <input type="text" id="f-{{ $name }}" name="{{ $name }}" value="{{ $value }}" dir="ltr" />
            @break

          @default
            <input type="text" id="f-{{ $name }}" name="{{ $name }}" value="{{ $value }}" />
        @endswitch

        @if(isset($field['hint']))<p class="hint">{{ $field['hint'] }}</p>@endif
        @error($name)<p class="err">{{ $message }}</p>@enderror
        @error($name.'_file')<p class="err">{{ $message }}</p>@enderror
      </div>
    @endforeach
  </div>

  <div class="form-actions">
    <label class="switch">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editing ? $row->is_active : true))>
      <span class="track"></span>
      ظاهر في الموقع
    </label>
    <div class="spacer"></div>
    <a href="{{ route('admin.resource.index', $def['key']) }}" class="btn btn-ghost">إلغاء</a>
    <button type="submit" class="btn btn-primary"><x-admin-icon name="check" /> {{ $editing ? 'حفظ التعديلات' : 'إضافة' }}</button>
  </div>
</form>

</x-layouts.admin>
