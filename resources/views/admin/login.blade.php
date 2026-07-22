<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="robots" content="noindex,nofollow" />
<title>تسجيل الدخول — لوحة تحكم كنوز العالم</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/admin.css') }}?v={{ filemtime(public_path('assets/admin.css')) }}" />
</head>
<body>

<div class="login-wrap">
  <div class="login-card">
    <div class="logo"><img src="{{ asset('logo.png') }}" alt="كنوز العالم للمصاعد" /></div>
    <h1>لوحة التحكم</h1>
    <p class="sub">سجّل الدخول لإدارة محتوى الموقع</p>

    @if($errors->any())
      <div class="flash err"><x-admin-icon name="alert" /> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}" class="form-grid">
      @csrf
      <div class="field">
        <label for="email">البريد الإلكتروني</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus dir="ltr" />
      </div>
      <div class="field">
        <label for="password">كلمة المرور</label>
        <input id="password" type="password" name="password" required dir="ltr" />
      </div>
      <label class="switch">
        <input type="checkbox" name="remember" value="1" />
        <span class="track"></span>
        تذكّرني على هذا الجهاز
      </label>
      <button type="submit" class="btn btn-primary btn-block">دخول</button>
    </form>
  </div>
</div>

</body>
</html>
