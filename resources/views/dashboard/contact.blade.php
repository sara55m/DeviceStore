@extends('layouts.dashboard')
@section('title', __('dashboard.contact'))
@section('content')
@php
$f = 'w-full rounded-md border border-slate-300 dark:border-white/15 bg-white dark:bg-panel px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-signal';
$msgs = [['Mona Ali','mona@example.com','Quote for 8 cameras','Today',true],['Nour Tech Co.','info@nour.example','Office PCs bulk order','Yesterday',false],['Karim Adel','karim@example.com','Warranty question','Oct 5',false]];
@endphp
<div class="grid lg:grid-cols-3 gap-4">
  <x-panel class="p-5 space-y-4 lg:col-span-1 self-start">
    <h2 class="font-semibold">{{ __('dashboard.store_info') }}</h2>
    <form method="POST" action="#" class="space-y-4">@csrf
      @foreach(['phone','email','address','hours'] as $k)
      <label class="block text-sm"><span class="font-medium">{{ __('dashboard.'.$k) }}</span><input name="{{ $k }}" class="{{ $f }} mt-1"></label>
      @endforeach
      <button class="px-5 py-2 rounded-md bg-signal text-ink font-semibold text-sm">{{ __('dashboard.save') }}</button>
    </form>
  </x-panel>
  <x-panel class="lg:col-span-2 overflow-x-auto">
    <h2 class="font-semibold p-5 pb-3">{{ __('dashboard.messages') }}</h2>
    <ul>@foreach($msgs as [$n,$e,$s,$d,$new])
      <li class="flex items-start gap-3 px-5 py-3 border-t border-slate-100 dark:border-white/5">
        <span class="mt-2 w-2 h-2 rounded-full shrink-0 {{ $new ? 'bg-signal' : 'bg-transparent' }}"></span>
        <div class="min-w-0 flex-1"><p class="{{ $new ? 'font-semibold' : '' }}">{{ $s }}</p><p class="text-sm text-slate-500 dark:text-slate-400">{{ $n }} · {{ $e }}</p></div>
        <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $d }}</span>
      </li>
    @endforeach</ul>
  </x-panel>
</div>
@endsection
