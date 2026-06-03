<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        <div>
            <a href="/">Idea</a>
        </div>
        <div class="flex gap-5">    
            @guest        
                <a href="{{ route('login') }}">Login</a>
                <a class="btn" href="{{ route('register') }}">Register</a>
            @else
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-ghost">Logout</button>
                </form>
            @endif
        </div>
    </div>
</nav>