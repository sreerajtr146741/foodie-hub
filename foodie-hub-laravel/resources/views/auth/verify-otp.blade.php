@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 shadow rounded-lg text-center">
        <div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Verify Your Email</h2>
            <p class="mt-2 text-sm text-gray-600">
                Please enter the 6-digit code sent to your email address: <strong>{{ Auth::user()->email }}</strong>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('verify.otp') }}" method="POST">
            @csrf
            
            @if ($errors->any())
                <div class="text-red-500 text-sm">
                    <ul>
                         @foreach ($errors->all() as $error)
                             <li>{{ $error }}</li>
                         @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="otp" class="sr-only">OTP Code</label>
                    <input id="otp" name="otp" type="text" maxlength="6" required class="appearance-none rounded relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 text-2xl tracking-widest text-center" placeholder="000 000">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Verify Account
                </button>
            </div>
        </form>
        
        <!-- Resend OTP Form (separate from verify form) -->
        <div class="text-sm mt-4">
            <p class="text-gray-500 mb-2">Didn't receive the code?</p>
            <form method="POST" action="{{ route('resend.otp') }}">
                @csrf
                <button type="submit" class="font-medium text-orange-600 hover:text-orange-500 underline">
                    Resend OTP
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
