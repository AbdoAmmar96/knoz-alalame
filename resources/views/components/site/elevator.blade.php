{{-- بئر المصعد المتحرك (يُشارَك بين هيرو الرئيسية وهيرو الصفحات الداخلية) — بئران بلوحة كحلية وكبينتان برتقاليتان --}}
<div class="shaft-wrap rv">
  <div class="lift">
    <span class="floors" aria-hidden="true">@for($n = 10; $n >= 1; $n--)<i @class(['on' => $n === 10])>{{ $n }}</i>@endfor</span>
    <div class="bay">
      <div class="shaft">
        <div class="cabin up"><span class="cd l"></span><span class="cd r"></span></div>
      </div>
      <div class="shaft">
        <div class="cabin down"><span class="cd l"></span><span class="cd r"></span></div>
      </div>
    </div>
    <span class="floors" aria-hidden="true">@for($n = 10; $n >= 1; $n--)<i @class(['on' => $n === 1])>{{ $n }}</i>@endfor</span>
  </div>
  <div class="shaft-chip"><x-icon name="clock" /><div><b>24/7</b><small>استجابة لأعطال الطوارئ</small></div></div>
</div>
