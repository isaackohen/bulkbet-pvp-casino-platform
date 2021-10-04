@extends('errors::illustrated-layout')

@section('code', '500 😭')
@section('title', __('500 | Ошибочка'))

@section('image')
<div style="background-image: url('/assets/images/new-logo-loto.png');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __('Упс, что-то пошло не так.'))