@extends("errors.layout")
@section("code", "404")
@section("title", "الصفحة غير موجودة")
@section("message", "الرابط الذي فتحته غير صحيح أو أن الصفحة نُقلت. تصفّح خدماتنا أو تواصل معنا مباشرةً.")
@section("extra")
  <a href="{{ url("/services") }}" class="btn g">تصفّح خدماتنا</a>
  <a href="{{ url("/contact") }}" class="btn g">تواصل معنا</a>
@endsection
