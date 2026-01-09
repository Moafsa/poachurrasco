@extends('emails.layout')

@section('title', $notification->title)

@section('content')
    <h2 style="color: #1f2937; margin-top: 0;">{{ $notification->title }}</h2>
    
    <p style="color: #4b5563; font-size: 16px;">
        {{ $notification->message }}
    </p>

    @if($notification->establishment)
        <div class="divider"></div>
        <p style="color: #6b7280; font-size: 14px;">
            <strong>Estabelecimento:</strong> {{ $notification->establishment->name }}
        </p>
    @endif

    @if($notification->order)
        <div class="divider"></div>
        <p style="color: #6b7280; font-size: 14px;">
            <strong>Pedido:</strong> #{{ $notification->order->order_number }}
        </p>
    @endif

    @if($notification->data)
        <div class="divider"></div>
        @foreach($notification->data as $key => $value)
            <p style="color: #6b7280; font-size: 14px;">
                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}
            </p>
        @endforeach
    @endif

    <div class="divider"></div>
    <p style="text-align: center;">
        <a href="{{ url('/dashboard') }}" class="button">Acessar Dashboard</a>
    </p>
@endsection


















