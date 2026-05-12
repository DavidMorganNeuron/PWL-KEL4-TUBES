@extends('layout.app')

<form method="POST" action="/order/branch">
    @csrf

    @foreach($branches as $branch)
        <label>
            <input type="radio" name="branch_id" value="{{ $branch->id_branches }}">
            {{ $branch->name }}
        </label>
    @endforeach

    <button type="submit">Lanjut</button>
</form>