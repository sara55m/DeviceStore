@extends('layouts.dashboard')
@section('title', __('admin.products'))
@section('content')
@php $field = 'rounded-md border border-slate-300 dark:border-white/15 bg-white dark:bg-panel px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-signal'; @endphp
<form method="GET" class="flex flex-wrap gap-3 mb-4">
  <input name="q" value="{{ request('q') }}" type="search" placeholder="{{ __('admin.search') }}" class="{{ $field }} w-full sm:w-64">
  <select name="category" onchange="this.form.submit()" class="{{ $field }}">
    <option value="">{{ __('admin.all_categories') }}</option>
    @foreach($categories as $c)
      <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
    @endforeach
  </select>
  <a href="{{ route('dashboard.products.create') }}" class="ms-auto px-4 py-2 rounded-md bg-signal text-ink font-semibold text-sm hover:brightness-95">{{ __('admin.add_product') }}</a>
</form>

<x-panel class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="text-slate-500 dark:text-slate-400"><tr>
      @foreach(['name','category','price','status','actions'] as $h)<th class="px-5 py-3 text-start font-medium">{{ __('admin.'.$h) }}</th>@endforeach
    </tr></thead>
    <tbody>
    @forelse($products as $p)
      <tr class="border-t border-slate-100 dark:border-white/5">
        <td class="px-5 py-3"><div class="flex items-center gap-3">
          @if($p->primaryImage)<img src="{{ $p->primaryImage->url }}" alt="" class="w-10 h-10 rounded object-cover shrink-0">
          @else<span class="w-10 h-10 rounded bg-slate-100 dark:bg-white/10 shrink-0"></span>@endif
          <span class="font-medium">{{ $p->name }}</span>
        </div></td>
        <td class="px-5 py-3">{{ $p->category->name }}</td>
        <td class="px-5 py-3">{{ $p->price !== null ? 'EGP '.number_format($p->price / 100, 2) : '—' }}</td>
        <td class="px-5 py-3">
          <span class="px-2 py-0.5 rounded-full text-xs {{ $p->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-white/10 dark:text-slate-300' }}">
            {{ $p->is_active ? __('admin.active') : __('admin.hidden') }}
          </span>
        </td>
        <td class="px-5 py-3 whitespace-nowrap">
          <a href="{{ route('dashboard.products.edit', $p) }}" class="text-sky-700 dark:text-sky-400 me-3">{{ __('admin.edit') }}</a>
          <form method="POST" action="{{ route('dashboard.products.destroy', $p) }}" class="inline" onsubmit="return confirm(@js(__('admin.confirm_delete')))">
            @csrf @method('DELETE')
            <button class="text-red-600 dark:text-red-400">{{ __('admin.delete') }}</button>
          </form>
        </td>
      </tr>
    @empty
    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">
        {{ request()->filled('q') || request()->filled('category') ? __('admin.no_results') : __('admin.no_products') }}
      </td></tr>
    @endforelse
    </tbody>
  </table>
</x-panel>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
