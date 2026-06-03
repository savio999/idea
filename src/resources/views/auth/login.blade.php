<x-layout>
    <x-form title="Login" description="Login to your account">
        <form action="{{ route('login.store') }}" method="POST" class="mt-10 space-y-4">
            @csrf
            <x-form.field name="email" label="Email" type="email" class="input mt-1"/>
            <x-form.field name="password" label="Password" type="password" class="input mt-1"/>
            <input type="submit" value="Login" class="btn mt-2 h-10 w-full">
        </form>
    </x-form>
</x-layout>