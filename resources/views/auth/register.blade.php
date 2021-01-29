<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <img src="/diffsync.png" class="w-24 h-24">

        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('名前') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="user_name" value="{{ __('ニックネーム')}}" />
                <x-jet-input id="user_name" class="block mt-1 w-full" type="text" name="user_name" :value="old('user_name')" required/>
            </div>
            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('メールアドレス') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('パスワード') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('パスワード（確認）') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('アカウントを持っていますか？') }}
                </a>

                <x-jet-button class="ml-4">
                    {{ __('登録する') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
