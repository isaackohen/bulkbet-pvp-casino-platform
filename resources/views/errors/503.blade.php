@extends('errors::illustrated-layout')

@section('code', '503 😭')
@section('title', __('Техническое обслуживание'))

@section('image')
<div style="background-image: url('/assets/images/new-logo-loto.png');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __($exception->getMessage() ?: 'Извините, мы проводим техническое обслуживание. Пожалуйста, зайдите в ближайшее время.'))