@extends('emails.master-email')
@section('content')
    <p>{!!$body??''!!}</p>
@endsection
