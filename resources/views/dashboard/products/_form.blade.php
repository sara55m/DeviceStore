@csrf
@php
  $f = 'w-full rounded-md border border-slate-300 dark:border-white/15 bg-white dark:bg-panel px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-signal';
  $specRows = array_values(old('specs', $product->specs ?? []));
@endphp

@if($errors->any())
  <div class="mb-4 rounded-md border border-red-300 bg-red-50 text-red-800 dark:border-red-400/30 dark:bg-red-400/10 dark:text-red-300 px-4 py-3 text-sm">
    <ul class="list-disc ms-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<div class="grid lg:grid-cols-2 gap-4">
  @foreach([['en','English','ltr'],['ar','العربية','rtl']] as [$l,$label,$dir])
  <x-panel class="p-5 space-y-4">
    <h2 class="font-semibold">{{ $label }}</h2>
    <label class="block text-sm"><span class="font-medium">{{ __('admin.name') }}</span>
      <input name="name[{{ $l }}]" dir="{{ $dir }}" value="{{ old('name.'.$l, $product->getTranslation('name', $l, false)) }}" class="{{ $f }} mt-1" required>
    </label>
    <label class="block text-sm"><span class="font-medium">{{ __('admin.description') }}</span>
      <textarea name="description[{{ $l }}]" dir="{{ $dir }}" rows="5" class="{{ $f }} mt-1">{{ old('description.'.$l, $product->getTranslation('description', $l, false)) }}</textarea>
    </label>
  </x-panel>
  @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-4 mt-4">
  <x-panel class="p-5 space-y-4">
    <label class="block text-sm"><span class="font-medium">{{ __('admin.category') }}</span>
      <select name="category_id" class="{{ $f }} mt-1" required>
        <option value="">—</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </label>
    <div class="grid grid-cols-2 gap-4">
      <label class="block text-sm"><span class="font-medium">{{ __('admin.sku') }}</span>
        <input name="sku" value="{{ old('sku', $product->sku) }}" class="{{ $f }} mt-1">
      </label>
      <label class="block text-sm"><span class="font-medium">{{ __('admin.price_egp') }}</span>
        <input name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price !== null ? $product->price / 100 : '') }}" class="{{ $f }} mt-1">
      </label>
    </div>
    <div class="flex flex-wrap gap-6 text-sm">
      <label class="flex items-center gap-2"><input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))> {{ __('admin.active') }}</label>
      <label class="flex items-center gap-2"><input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> {{ __('admin.featured') }}</label>
    </div>
  </x-panel>

  <x-panel class="p-5">
    <div x-data="{ rows: @js($specRows) }">
      <h2 class="font-semibold mb-3">{{ __('admin.specs') }}</h2>
      <template x-for="(row, i) in rows" :key="i">
        <div class="flex gap-2 mb-2">
          <input :name="`specs[${i}][key]`" x-model="row.key" placeholder="{{ __('admin.spec_label') }}" class="{{ $f }}">
          <input :name="`specs[${i}][value]`" x-model="row.value" placeholder="{{ __('admin.spec_value') }}" class="{{ $f }}">
          <button type="button" @click="rows.splice(i, 1)" class="px-2 text-red-600 dark:text-red-400" aria-label="{{ __('admin.remove') }}">✕</button>
        </div>
      </template>
      <button type="button" @click="rows.push({key:'', value:''})" class="text-sm text-sky-700 dark:text-sky-400">+ {{ __('admin.add_spec') }}</button>
    </div>
  </x-panel>
</div>

<x-panel class="p-5 mt-4">
  <h2 class="font-semibold mb-3">{{ __('admin.images') }}</h2>

  @if($product->exists && $product->images->isNotEmpty())
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
      @foreach($product->images as $img)
        <div class="rounded-md border border-slate-200 dark:border-white/10 p-2 text-xs space-y-2">
          <img src="{{ $img->url }}" alt="" class="w-full h-28 object-cover rounded">
          <label class="flex items-center gap-2"><input type="radio" name="primary_image" value="{{ $img->id }}" @checked($img->is_primary)> {{ __('admin.primary') }}</label>
          <label class="flex items-center gap-2 text-red-600 dark:text-red-400"><input type="checkbox" name="remove_images[]" value="{{ $img->id }}"> {{ __('admin.remove') }}</label>
        </div>
      @endforeach
    </div>
  @endif

  <div x-data="{
    items: [],
    max: 8,
    add(e) {
      for (const f of e.target.files) {
        if (this.items.length >= this.max) break;
        const dup = this.items.some(i => i.file.name === f.name && i.file.size === f.size && i.file.lastModified === f.lastModified);
        if (!dup) this.items.push({ file: f, url: URL.createObjectURL(f) });
      }
      this.sync();
    },
    remove(i) {
      URL.revokeObjectURL(this.items[i].url);
      this.items.splice(i, 1);
      this.sync();
    },
    sync() {
      const dt = new DataTransfer();
      this.items.forEach(i => dt.items.add(i.file));
      this.$refs.input.files = dt.files;
    }
  }">
    <div class="flex items-center gap-3">
      <label class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-slate-300 dark:border-white/15 text-sm cursor-pointer hover:bg-slate-50 dark:hover:bg-white/5">
        + {{ __('admin.add_images') }}
        <input type="file" x-ref="input" name="images[]" multiple accept="image/png,image/jpeg,image/webp" class="sr-only" @change="add($event)">
      </label>
      <span class="text-sm text-slate-500 dark:text-slate-400" x-text="items.length + ' / ' + max"></span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4" x-show="items.length" x-cloak>
      <template x-for="(item, i) in items" :key="item.url">
        <div class="relative">
          <img :src="item.url" alt="" class="w-full h-28 object-cover rounded">
          <button type="button" @click="remove(i)" aria-label="{{ __('admin.remove') }}"
                  class="absolute top-1 end-1 w-6 h-6 rounded-full bg-black/70 text-white text-xs grid place-items-center">✕</button>
        </div>
      </template>
    </div>

    <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">{{ __('admin.images_hint') }}</p>
  </div>
</x-panel>

<div class="mt-4 flex gap-3">
  <button class="px-5 py-2 rounded-md bg-signal text-ink font-semibold text-sm">{{ __('admin.save') }}</button>
  <a href="{{ route('dashboard.products.index') }}" class="px-5 py-2 rounded-md border border-slate-300 dark:border-white/15 text-sm">{{ __('admin.cancel') }}</a>
</div>
