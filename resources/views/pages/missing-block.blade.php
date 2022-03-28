@extends('layouts.app')

@section('title', "Block {$block}")

@section('content')
    <x-alert type="warning">
        This block is not yet mined! This block will approximately be mined at {{ $date }}
    </x-alert>
@endsection
