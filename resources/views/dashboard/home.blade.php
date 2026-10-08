@extends('layouts.dashboard')
@section('title', __('admin.home'))
@section('content')
@php
$stats = [['total_sales','EGP 248,300','+12%'],['orders','184','+8%'],['customers','1,026','+5%'],['low_stock','7','']];
$bars = [40,65,50,80,60,95,72];
$orders = [['#1042','Omar Hassan','delivered','EGP 5,400'],['#1041','Nour Tech Co.','shipped','EGP 32,900'],['#1040','Mona Ali','pending','EGP 1,850'],['#1039','Karim Adel','delivered','EGP 12,300']];
$badge = ['pending'=>'bg-amber-100 text-amber-800 dark:bg-amber-400/15 dark:text-amber-300','shipped'=>'bg-sky-100 text-sky-800 dark:bg-sky-400/15 dark:text-sky-300','delivered'=>'bg-emerald-100 text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-300'];
@endphp
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
  @foreach($stats as [$k,$v,$d])
  <x-panel class="p-5">
    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('admin.'.$k) }}</p>
    <p class="mt-2 text-2xl font-bold">{{ $v }}</p>
    @if($d)<p class="text-sm text-emerald-600 dark:text-emerald-400 mt-1">{{ $d }}</p>@endif
  </x-panel>
  @endforeach
</div>
<div class="grid lg:grid-cols-3 gap-4 mt-4">
  <x-panel class="p-5 lg:col-span-2">
    <h2 class="font-semibold mb-4">{{ __('admin.weekly_sales') }}</h2>
    <div class="flex items-end gap-3 h-48">
      @foreach($bars as $b)<div class="flex-1 rounded-t {{ $loop->last ? 'bg-signal' : 'bg-ink dark:bg-slate-500' }}" style="height:{{ $b }}%"></div>@endforeach
    </div>
  </x-panel>
  <x-panel class="p-5">
    <h2 class="font-semibold mb-4">{{ __('admin.top_categories') }}</h2>
    @foreach([['cameras',58],['computers',32],['accessories',10]] as [$c,$p])
      <div class="mb-3"><div class="flex justify-between text-sm mb-1"><span>{{ __('admin.'.$c) }}</span><span>{{ $p }}%</span></div>
      <div class="h-2 rounded bg-slate-200 dark:bg-white/10"><div class="h-2 rounded bg-signal" style="width:{{ $p }}%"></div></div></div>
    @endforeach
  </x-panel>
</div>
<x-panel class="mt-4 overflow-x-auto">
  <h2 class="font-semibold p-5 pb-3">{{ __('admin.recent_orders') }}</h2>
  <table class="w-full text-sm">
    <thead class="text-slate-500 dark:text-slate-400"><tr>@foreach(['order','customer','status','total'] as $h)<th class="px-5 py-2 text-start font-medium">{{ __('admin.'.$h) }}</th>@endforeach</tr></thead>
    <tbody>@foreach($orders as [$id,$cu,$st,$t])
      <tr class="border-t border-slate-100 dark:border-white/5"><td class="px-5 py-3 font-medium">{{ $id }}</td><td class="px-5 py-3">{{ $cu }}</td>
      <td class="px-5 py-3"><span class="px-2 py-0.5 rounded-full text-xs {{ $badge[$st] }}">{{ __('admin.'.$st) }}</span></td><td class="px-5 py-3">{{ $t }}</td></tr>
    @endforeach</tbody>
  </table>
</x-panel>
@endsection
