@extends('layouts.dashboard')
@section('title', __('admin.add_product'))
@section('content')
<form method="POST" action="{{ route('dashboard.products.store') }}" enctype="multipart/form-data">
  @include('dashboard.products._form')
</form>
@endsection
