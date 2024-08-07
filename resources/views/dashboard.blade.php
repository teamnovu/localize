@extends('statamic::layout')
@section('title', __('localize::general.title'))

@section('content')
    <localize-list
        site="{{ $site }}"
        :sites="{{ $sites }}"
        action="{{ cp_route('localize.dashboard.update') }}"
    ></localize-list>
@endsection