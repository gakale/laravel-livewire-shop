@extends('livewire-shop::layouts.app')

@section('title', 'Recherche de produits')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-3">Recherche de produits</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('livewire-shop.index') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Recherche</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <livewire:product-search />
</div>
@endsection
