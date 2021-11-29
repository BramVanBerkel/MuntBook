<nav x-data="{ mobileMenuOpen: false }" class="border-b border-blue border-opacity-25 lg:border-none">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
        <div
            class="relative h-16 flex items-center justify-between lg:border-b lg:border-blue-400 lg:border-opacity-25">
            <div class="px-2 flex items-center lg:px-0">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}">
                        <img class="block h-8" src="{{ asset('images/logos/white.svg') }}" alt="GuldenBook logo"/>
                    </a>
                </div>
                <div class="hidden lg:block lg:ml-10">
                    <div class="flex space-x-4">

                        <x-nav-item
                            :route="route('price')">
                            Price
                        </x-nav-item>

{{--                        <x-nav-item--}}
{{--                            :route="route('price')"--}}
{{--                            :children="[(object)['route' => '#', 'label' => 'test']]">--}}
{{--                            Home--}}
{{--                        </x-nav-item>--}}
                    </div>
                </div>
            </div>
            @auth
                <div class="flex lg:hidden">
                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                            class="bg-blue-600 p-2 rounded-md inline-flex items-center justify-center text-blue-200 hover:text-white hover:bg-indigo-500 hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!--
                          Heroicon name: outline/menu

                          Menu open: "hidden", Menu closed: "block"
                        -->
                        <svg class="h-6 w-6" :class="{  }" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <i class="fas fa-bars"></i>
                        <!--
                          Heroicon name: outline/x

                          Menu open: "block", Menu closed: "hidden"
                        -->
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="hidden lg:block lg:ml-4">
                    <div class="flex items-center">
                        <!-- Profile dropdown -->
                        <div x-data="{ open: false }"
                             class="ml-3 relative flex-shrink-0">
                            <div>
                                <button @click="open = !open"
                                        type="button"
                                        class="bg-blue-600 rounded-full flex text-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-600 focus:ring-white"
                                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="rounded-full h-8 w-8"
                                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                         alt="">
                                </button>
                            </div>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                 role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                 tabindex="-1">
                                <!-- Active: "bg-gray-100", Not Active: "" -->

                                <a href="#" class="block py-2 px-4 text-sm text-gray-700" role="menuitem" tabindex="-1"
                                   id="user-menu-item-0">
                                    Your Profile
                                </a>

                                <a href="#" class="block py-2 px-4 text-sm text-gray-700" role="menuitem" tabindex="-1"
                                   id="user-menu-item-1">
                                    Settings
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block py-2 px-4 text-sm text-gray-700">Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div x-show="mobileMenuOpen" class="lg:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <!-- Current: "bg-blue-700 text-white", Default: "text-white hover:bg-blue-500 hover:bg-opacity-75" -->
            <a href="#" class="bg-black bg-opacity-20 text-white block rounded-md py-2 px-3 text-base font-medium"
               aria-current="page">
                Dashboard
            </a>

            <a href="#"
               class="text-white hover:bg-blue-500 hover:bg-opacity-75 block rounded-md py-2 px-3 text-base font-medium">
                Team
            </a>

            <a href="#"
               class="text-white hover:bg-blue-500 hover:bg-opacity-75 block rounded-md py-2 px-3 text-base font-medium">
                Projects
            </a>

            <a href="#"
               class="text-white hover:bg-blue-500 hover:bg-opacity-75 block rounded-md py-2 px-3 text-base font-medium">
                Calendar
            </a>

            <a href="#"
               class="text-white hover:bg-blue-500 hover:bg-opacity-75 block rounded-md py-2 px-3 text-base font-medium">
                Reports
            </a>
        </div>
        <div class="pt-4 pb-3 border-t border-blue-700">
            <div class="px-5 flex items-center">
                <div class="flex-shrink-0">
                    <img class="rounded-full h-10 w-10"
                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                         alt="">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-white">Tom Cook</div>
                    <div class="text-sm font-medium text-blue-300">tom@example.com</div>
                </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
                <a href="#"
                   class="block rounded-md py-2 px-3 text-base font-medium text-white hover:bg-blue-500 hover:bg-opacity-75">
                    Your Profile
                </a>

                <a href="#"
                   class="block rounded-md py-2 px-3 text-base font-medium text-white hover:bg-blue-500 hover:bg-opacity-75">
                    Settings
                </a>

                <a href="#"
                   class="block rounded-md py-2 px-3 text-base font-medium text-white hover:bg-blue-500 hover:bg-opacity-75">
                    Sign out
                </a>
            </div>
        </div>
    </div>
</nav>
