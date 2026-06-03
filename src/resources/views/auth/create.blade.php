<x-layout>
    <x-form title="Register" description="Create an account to get started">
        <form action="{{ route('register.store') }}" method="POST" class="mt-10 space-y-4">
            @csrf
            <x-form.field name="name" label="Name" type="text" class="input mt-1" value="{{ old('name') }}"/>
            <x-form.field name="email" label="Email" type="email" class="input mt-1" value="{{ old('email') }}"/>
            <x-form.field name="password" label="Password" type="password" class="input mt-1"/>
            <input type="submit" value="Register" class="btn mt-2 h-10 w-full">
        </form>
    </x-form>
</x-layout>