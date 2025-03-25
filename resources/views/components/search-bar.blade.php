<div x-data="{ searchOpen: false }" class="relative">
    <!-- Search icon button with improved hover effect -->
    <button @click="searchOpen = !searchOpen" 
            class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </button>

    <!-- Enhanced search form with smooth animations -->
    <div x-show="searchOpen" 
         x-cloak
         @click.away="searchOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
         class="absolute right-0 mt-3 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-10 overflow-hidden border border-gray-200 dark:border-gray-700">
        
        <!-- Search form with focus state -->
        <form action="{{ route('users.search') }}" method="GET" class="p-3">
            <div class="flex items-center overflow-hidden border border-gray-200 dark:border-gray-600 rounded-lg focus-within:ring-2 focus-within:ring-blue-400 dark:focus-within:ring-blue-500 bg-gray-50 dark:bg-gray-700">
                <input type="text" 
                       name="q" 
                       placeholder="Search users..." 
                       class="w-full px-4 py-2.5 bg-transparent text-gray-800 dark:text-white focus:outline-none text-sm placeholder-gray-500 dark:placeholder-gray-400"
                       autocomplete="off"
                       x-ref="searchInput"
                       x-init="$nextTick(() => { $refs.searchInput.focus() })">
                <button type="submit" 
                        class="px-3 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
        
        <!-- Quick links section with subtle hover effects -->
        <div class="bg-gray-50 dark:bg-gray-750 border-t border-gray-200 dark:border-gray-700 p-3">
            <a href="{{ route('users.index') }}" 
               class="flex items-center text-sm text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                View all users
            </a>
            
            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 italic">
                Try searching by name or username
            </div>
        </div>
    </div>
</div>

<!-- Add x-cloak style for Alpine.js -->
<style>
    [x-cloak] { display: none !important; }
</style>