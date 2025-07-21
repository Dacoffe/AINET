<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Http\Requests\RegisterFormRequest;
use Livewire\WithFileUploads;

new #[Layout('components.layouts.auth')] class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $gender = '';

    public function rules()
    {
        return (new RegisterFormRequest())->rules();
    }

    public function register(): void
    {
        $validated = $this->validate();

        // Garante que o campo password está presente e encriptado
        $validated['password'] = Hash::make($this->password);

        $validated['type'] = 'pending_member';

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('home.index', absolute: false), navigate: true);
    }
};
?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6" enctype="multipart/form-data">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Full name')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
        />

        <!-- Gender -->
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Gender') }}</label>
            <div class="mt-1 space-y-2">
                <div class="flex items-center">
                    <input wire:model="gender" id="M" type="radio" value="M" required class="mx-4 h-4 w-4 text-primary-600 focus:ring-primary-500 border-zinc-300 dark:border-zinc-600">
                    <label for="M" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300">{{ __('Male') }}</label>

                    <input wire:model="gender" id="F" type="radio" value="F" class="mx-4 h-4 w-4 text-primary-600 focus:ring-primary-500 border-zinc-300 dark:border-zinc-600">
                    <label for="F" class="ml-2 block text-sm text-zinc-700 dark:text-zinc-300">{{ __('Female') }}</label>
                </div>
            </div>
        </div>


        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
