<form method="POST" action="{{ route('search') }}" class="relative">
    @csrf
    <input name="query"
           type="text"
           placeholder="Search for blocks, transactions or addresses"
           class="outline-none focus:ring ring-0 placeholder-gray-300 border-none rounded-lg px-8 py-2 bg-white w-96">

    <div class="absolute top-1/2 left-2 transform -translate-y-1/2 text-xs text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
</form>

