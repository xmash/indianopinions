@extends('layouts.admin')
@section('page_title', 'Homepage Layout')

@section('content')
<x-admin.page-header title="Homepage layout" subtitle="Choose what appears in each slot. Empty slots show the latest published articles." />

@include('admin.layout._form', [
    'action' => route('admin.layout.homepage.update'),
    'title' => 'Homepage slots',
])

@endsection
