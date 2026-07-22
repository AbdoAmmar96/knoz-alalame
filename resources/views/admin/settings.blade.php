<x-layouts.admin title="إعدادات الموقع" crumb="بيانات التواصل والنصوص الثابتة والصور العامة">

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
  @csrf @method('PUT')

  @foreach($schema as $group => $section)
    <details class="settings-group" @if($loop->first) open @endif>
      <summary>{{ $section['label'] }}</summary>
      <div class="body">
        <div class="form-grid">
          @foreach($section['fields'] as $key => $field)
            @php $value = old($key, setting($key)); @endphp
            <div class="field">
              <label for="s-{{ $key }}">{{ $field['label'] }}</label>

              @if($field['type'] === 'textarea')
                <textarea id="s-{{ $key }}" name="{{ $key }}" rows="{{ $field['rows'] ?? 4 }}">{{ $value }}</textarea>
              @elseif($field['type'] === 'image')
                <div class="upload">
                  <div class="preview" id="prev-{{ $key }}">
                    @if($value)<img src="{{ img($value) }}" alt="" />@else<span>لا صورة</span>@endif
                  </div>
                  <div class="pick">
                    <input type="file" name="{{ $key }}_file" accept="image/*"
                           data-preview="#prev-{{ $key }}" style="margin-bottom:8px" />
                    <input type="text" id="s-{{ $key }}" name="{{ $key }}" value="{{ $value }}"
                           placeholder="أو الصق رابط صورة" dir="ltr" />
                  </div>
                </div>
              @else
                <input type="text" id="s-{{ $key }}" name="{{ $key }}" value="{{ $value }}" />
              @endif

              @if(isset($field['hint']))<p class="hint">{{ $field['hint'] }}</p>@endif
              @error($key)<p class="err">{{ $message }}</p>@enderror
            </div>
          @endforeach
        </div>
      </div>
    </details>
  @endforeach

  <div class="card card-pad" style="margin-top:20px">
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
      <button type="submit" class="btn btn-primary"><x-admin-icon name="check" /> حفظ الإعدادات</button>
      <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost"><x-admin-icon name="external" /> معاينة الموقع</a>
    </div>
  </div>
</form>

</x-layouts.admin>
