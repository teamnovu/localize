@extends('statamic::layout')
@section('title', 'Localize')

@section('content')

    <header class="mb-8">
        <h1>Localize</h1>
        <p>
            Texts may contain special characters, that will be replaced on the website.<br>
            These can be <code>{name}</code> or <code>:count</code> for example.
        </p>
    </header>

    <localize-list
        :translations="{{ $translations }}"
        site="{{ $site }}"
        action="{{ cp_route('localize.dashboard.update') }}"
    ></localize-list>

@endsection