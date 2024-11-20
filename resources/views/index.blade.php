@extends('layouts.app')
@section('title')
Home
@endsection
@section('content')
<div class="container">
    <form action="{{route('dev.store')}}" method="post">
        @csrf
        <div class="row">
            <div class="">
                <label for="client_id">Client ID : </label>
                <input type="text" name="client_id" id="client_id" value="{{old('client_id')}}">
            </div>
            <div class="">
                <label for="client_secret">Client Secret : </label>
                <input type="text" name="client_secret" id="client_secret" value="{{old('client_secret')}}">
            </div>
            <div class="">
                <label for="partner_id">Partner ID : </label>
                <input type="text" name="partner_id" id="partner_id" value="{{old('partner_id')}}">
            </div>
            <div class="">
                <label for="rsa_public_key">Public Key : </label>
                <textarea name="rsa_public_key" id="rsa_public_key">{{old('rsa_public_key')}}</textarea>
            </div>
            <div class="">
                <button type="submit">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection
