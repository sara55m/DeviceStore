@extends('layouts.dashboard')
@section('title', __('admin.contact'))
@section('content')
@php $f = 'w-full rounded-md border border-slate-300 dark:border-white/15 bg-white dark:bg-panel px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-signal'; @endphp
<form method="POST" action="{{ route('dashboard.contact.update') }}">
  @csrf @method('PUT')
  @include('dashboard._errors')

  <x-panel class="p-5">
    <h2 class="font-semibold mb-4">{{ __('admin.store_info') }}</h2>
    <div class="grid sm:grid-cols-2 gap-4">
      @foreach(['phone'=>'tel','whatsapp'=>'tel','email'=>'email','maps_url'=>'url'] as $k=>$type)
      <label class="block text-sm"><span class="font-medium">{{ __('admin.'.$k) }}</span>
        <input type="{{ $type }}" name="{{ $k }}" dir="ltr" value="{{ old($k, $contact[$k] ?? '') }}" class="{{ $f }} mt-1">
      </label>
      @endforeach
    </div>
  </x-panel>

  <div class="grid lg:grid-cols-2 gap-4 mt-4">
    @foreach([['en','English','ltr'],['ar','العربية','rtl']] as [$l,$label,$dir])
    <x-panel class="p-5 space-y-4">
      <h2 class="font-semibold">{{ $label }}</h2>
      <label class="block text-sm"><span class="font-medium">{{ __('admin.address') }}</span>
        <textarea name="address[{{ $l }}]" dir="{{ $dir }}" rows="3" class="{{ $f }} mt-1">{{ old('address.'.$l, $contact['address'][$l] ?? '') }}</textarea>
      </label>
      <label class="block text-sm"><span class="font-medium">{{ __('admin.hours') }}</span>
        <input name="hours[{{ $l }}]" dir="{{ $dir }}" value="{{ old('hours.'.$l, $contact['hours'][$l] ?? '') }}" class="{{ $f }} mt-1">
      </label>
    </x-panel>
    @endforeach
  </div>

  <button class="mt-4 px-5 py-2 rounded-md bg-signal text-ink font-semibold text-sm">{{ __('admin.save') }}</button>
</form>
@endsection
