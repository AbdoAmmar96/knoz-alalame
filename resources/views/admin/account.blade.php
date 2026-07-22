<x-layouts.admin title="حسابي" crumb="بيانات الدخول للوحة التحكم">

@if(auth()->user()->email === 'admin@konozcompany.com')
  <div class="flash err">
    <x-admin-icon name="alert" />
    ما زلت تستخدم بيانات الدخول الافتراضية المذكورة في ملف التوثيق — غيّرها الآن.
  </div>
@endif

<form method="POST" action="{{ route('admin.account.update') }}" class="card card-pad">
  @csrf @method('PUT')

  <div class="form-grid">
    <div class="field-row">
      <div class="field">
        <label for="name">الاسم</label>
        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" />
        @error('name')<p class="err">{{ $message }}</p>@enderror
      </div>
      <div class="field">
        <label for="email">البريد الإلكتروني</label>
        <input type="text" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" dir="ltr" />
        @error('email')<p class="err">{{ $message }}</p>@enderror
      </div>
    </div>

    <div class="field">
      <label for="current_password">كلمة المرور الحالية</label>
      <input type="password" id="current_password" name="current_password" dir="ltr" autocomplete="current-password" />
      <p class="hint">مطلوبة لتأكيد أي تغيير.</p>
      @error('current_password')<p class="err">{{ $message }}</p>@enderror
    </div>

    <div class="field-row">
      <div class="field">
        <label for="password">كلمة مرور جديدة</label>
        <input type="password" id="password" name="password" dir="ltr" autocomplete="new-password" />
        <p class="hint">اتركها فارغة إن لم ترغب بتغييرها. 10 خانات على الأقل، حروف وأرقام.</p>
        @error('password')<p class="err">{{ $message }}</p>@enderror
      </div>
      <div class="field">
        <label for="password_confirmation">تأكيد كلمة المرور</label>
        <input type="password" id="password_confirmation" name="password_confirmation" dir="ltr" autocomplete="new-password" />
      </div>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary"><x-admin-icon name="check" /> حفظ</button>
  </div>
</form>

</x-layouts.admin>
