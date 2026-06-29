@extends('layouts.admin')
@section('page_title', ucwords(str_replace('-', ' ', $hubSlug)).' Hub Layout')

@section('content')
<x-admin.page-header :title="ucwords(str_replace('-', ' ', $hubSlug)).' page layout'" subtitle="Curate this hub page. Empty slots show the latest articles in this category." />

@include('admin.layout._form', [
    'action' => route('admin.layout.hub.update', $hubSlug),
    'title' => ucwords(str_replace('-', ' ', $hubSlug)).' slots',
])

@endsection
