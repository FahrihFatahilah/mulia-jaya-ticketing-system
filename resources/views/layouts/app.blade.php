<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Tiket Bus Mulia Jaya' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-bus text-2xl"></i>
                    <h1 class="text-xl font-bold">PO. Bus Mulia Jaya</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-sm">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                        <a href="{{ route('admin.dashboard') }}" class="hover:bg-blue-700 px-3 py-2 rounded">
                            <i class="fas fa-tachometer-alt mr-2"></i>Admin
                        </a>
                        <a href="{{ route('loket.dashboard') }}" class="hover:bg-blue-700 px-3 py-2 rounded">
                            <i class="fas fa-ticket-alt mr-2"></i>Loket
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:bg-blue-700 px-3 py-2 rounded">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:bg-blue-700 px-3 py-2 rounded">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 mb-16">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
    
    <footer class="bg-gray-800 text-white py-4 fixed bottom-0 left-0 right-0">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">Copyright © {{ date('Y') }} PO Bus Muliajaya. Developed By Agam</p>
        </div>
    </footer>
</body>
</html>