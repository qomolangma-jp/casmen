<nav x-data="{ open: false }" class="bg-black border-b border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('master.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-master-nav-link :href="route('master.dashboard')" :active="request()->routeIs('master.dashboard')">
                        {{ __('マスターTOP') }}
                    </x-master-nav-link>
                    <x-master-nav-link :href="route('master.shop.index')" :active="request()->routeIs('master.shop.*')">
                        {{ __('登録店舗') }}
                    </x-master-nav-link>
                    <x-master-nav-link :href="route('master.link.index')" :active="request()->routeIs('master.link.*')">
                        {{ __('面接URL') }}
                    </x-master-nav-link>
                    <x-master-nav-link :href="route('master.notice.index')" :active="request()->routeIs('master.notice.*')">
                        {{ __('お知らせ管理') }}
                    </x-master-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-black hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div class="text-red-400 font-bold">MASTER</div>
                            <div class="ml-2">{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('プロフィール') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('ログアウト') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-master-responsive-nav-link :href="route('master.dashboard')" :active="request()->routeIs('master.dashboard')">
                {{ __('マスターTOP') }}
            </x-master-responsive-nav-link>
            <x-master-responsive-nav-link :href="route('master.shop.index')" :active="request()->routeIs('master.shop.*')">
                {{ __('登録店舗') }}
            </x-master-responsive-nav-link>
            <x-master-responsive-nav-link :href="route('master.link.index')" :active="request()->routeIs('master.link.*')">
                {{ __('面接URL') }}
            </x-master-responsive-nav-link>
            <x-master-responsive-nav-link :href="route('master.notice.index')" :active="request()->routeIs('master.notice.*')">
                {{ __('お知らせ管理') }}
            </x-master-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-red-400">MASTER</div>
                <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-master-responsive-nav-link :href="route('profile.edit')">
                    {{ __('プロフィール') }}
                </x-master-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-master-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('ログアウト') }}
                    </x-master-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
