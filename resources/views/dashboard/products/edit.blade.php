@extends('layouts.dashboard')
@section('title', __('admin.edit_product'))
@section('content')
<form method="POST" action="{{ route('dashboard.products.update', $product) }}" enctype="multipart/form-data">
  @method('PUT')
  @include('dashboard.products._form')
</form>
@endsection
