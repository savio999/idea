<x-layout>
    <x-form title="Edit Profile" description="Update your profile information">
        <form action="{{ route('profile.update') }}" method="POST" class="mt-10 space-y-4">
            @csrf
            @method('PATCH')
            <x-form.field name="name" label="Name" type="text" class="input mt-1" value="{{ old('name', $user->name) }}"/>
            <x-form.error name="name"/>
            <x-form.field name="email" label="Email" type="email" class="input mt-1" value="{{ old('email', $user->email) }}"/>
            <x-form.error name="email"/>
            <x-form.field name="password" label="New Password" type="password" class="input mt-1"/>
            <x-form.error name="password"/>
            <input type="submit" value="Update Account" class="btn mt-2 h-10 w-full">
        </form>
    </x-form>
</x-layout>