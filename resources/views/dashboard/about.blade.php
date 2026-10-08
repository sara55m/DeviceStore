@extends('layouts.dashboard')
@section('title', __('admin.about'))
@section('content')
@php $f = 'w-full rounded-md border border-slate-300 dark:border-white/15 bg-white dark:bg-panel px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-signal'; @endphp
<form method="POST" action="#">
  @csrf
  <div class="grid lg:grid-cols-2 gap-4">
    @foreach([['en','English','ltr'],['ar','العربية','rtl']] as [$l,$label,$dir])
    <x-panel class="p-5 space-y-4">
      <h2 class="font-semibold">{{ $label }}</h2>
      @foreach(['title'=>1,'story'=>5,'mission'=>3,'vision'=>3] as $k=>$rows)
      <label class="block text-sm"><span class="font-medium">{{ __('admin.'.$k) }}</span>
        @if($rows===1)<input name="{{ $k }}_{{ $l }}" dir="{{ $dir }}" class="{{ $f }} mt-1">
        @else<textarea name="{{ $k }}_{{ $l }}" rows="{{ $rows }}" dir="{{ $dir }}" class="{{ $f }} mt-1"></textarea>@endif
      </label>
      @endforeach
    </x-panel>
    @endforeach
  </div>
  <button class="mt-4 px-5 py-2 rounded-md bg-signal text-ink font-semibold text-sm">{{ __('admin.save') }}</button>
</form>
@endsection
