@if($errors->any())
  <div class="mb-4 rounded-md border border-red-300 bg-red-50 text-red-800 dark:border-red-400/30 dark:bg-red-400/10 dark:text-red-300 px-4 py-3 text-sm">
    <ul class="list-disc ms-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif
