@extends('errors::illustrated-layout')

@section('code', '404')

@section('title', __('404 | Страница не найдена'))

@section('image')

<div style="background-image: url('/assets/images/new-logo-loto.png');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
</div>

@endsection

@section('message', __('К сожалению, страница, которую вы ищете, не существует.'))