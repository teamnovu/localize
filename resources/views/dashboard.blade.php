@extends('statamic::layout')
@section('title', 'Localize')

@section('content')
    <localize-list
        site="{{ $site }}"
        :sites="{{ $sites }}"
        action="{{ cp_route('localize.dashboard.update') }}"
    ></localize-list>
@endsection