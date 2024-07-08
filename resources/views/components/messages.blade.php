 @if (session('success'))
     <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
         <strong class="font-bold">Success!</strong>
         <span class="block sm:inline">{{ session('success') }}</span>
     </div>
 @elseif (session('error'))
     <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
         <strong class="font-bold">Error:</strong>
         <span class="block sm:inline">{{ session('error') }}</span>
     </div>
 @endif
