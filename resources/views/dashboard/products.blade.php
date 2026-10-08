@extends('layouts.dashboard')
@section('title', __('dashboard.products'))
@section('content')
@php
$products = [
 ['Hikvision 4MP Dome Camera','cameras','EGP 1,850',24],['Dahua 8-Channel NVR','cameras','EGP 4,200',9],
 ['Lenovo ThinkCentre M70','computers','EGP 18,500',5],['Dell Vostro 3520 Laptop','computers','EGP 24,900',0],
 ['2TB Surveillance HDD','accessories','EGP 3,100',31],
];
$field = 'rounded-md border border-slate-300 dark:border-white/15 bg-white dark:bg-panel px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-signal';
@endphp
<div class="flex flex-wrap gap-3 mb-4">
  <input type="search" placeholder="{{ __('dashboard.search') }}" class="{{ $field }} w-full sm:w-64">
  <select class="{{ $field }}"><option>{{ __('dashboard.all_categories') }}</option>@foreach(['cameras','computers','accessories'] as $c)<option>{{ __('dashboard.'.$c) }}</option>@endforeach</select>
  <button class="ms-auto px-4 py-2 rounded-md bg-signal text-ink font-semibold text-sm hover:brightness-95">{{ __('dashboard.add_product') }}</button>
</div>
<x-panel class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="text-slate-500 dark:text-slate-400"><tr>@foreach(['name','category','price','stock','actions'] as $h)<th class="px-5 py-3 text-start font-medium">{{ __('dashboard.'.$h) }}</th>@endforeach</tr></thead>
    <tbody>@foreach($products as [$n,$c,$p,$s])
      <tr class="border-t border-slate-100 dark:border-white/5">
        <td class="px-5 py-3"><div class="flex items-center gap-3"><span class="w-10 h-10 rounded bg-slate-100 dark:bg-white/10 shrink-0"></span><span class="font-medium">{{ $n }}</span></div></td>
        <td class="px-5 py-3">{{ __('dashboard.'.$c) }}</td><td class="px-5 py-3">{{ $p }}</td>
        <td class="px-5 py-3">@if($s===0)<span class="text-red-600 dark:text-red-400">{{ __('dashboard.out_of_stock') }}</span>@else{{ $s }}@endif</td>
        <td class="px-5 py-3 whitespace-nowrap"><a href="#" class="text-sky-700 dark:text-sky-400 me-3">{{ __('dashboard.edit') }}</a><a href="#" class="text-red-600 dark:text-red-400">{{ __('dashboard.delete') }}</a></td>
      </tr>
    @endforeach</tbody>
  </table>
</x-panel>
@endsection
