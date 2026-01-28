<template>
  <div class="bg-white rounded-lg shadow p-5 sticky top-24">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-2xl font-bold">Filter</h3>
      <button class="text-red-500 text-base font-semibold">Reset</button>
    </div>

    <!-- Search -->
    <div class="mb-4 border-b pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-search"></i></span>
        <span class="font-semibold">Search</span>
      </div>
      <input type="text" placeholder="Search here..." class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2" />
      <label class="block font-semibold mb-1">Select Location</label>
      <select class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2">
        <option>Chicago</option>
        <option>Jakarta</option>
        <option>Bandung</option>
      </select>
      <label class="block font-semibold mb-1">No of Bedrooms</label>
      <select class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2">
        <option>Select</option>
        <option>1</option>
        <option>2</option>
      </select>
      <label class="block font-semibold mb-1">No of Bathrooms</label>
      <select class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2">
        <option>Select</option>
        <option>1</option>
        <option>2</option>
      </select>
      <label class="block font-semibold mb-1">Min Sqft</label>
      <input type="text" placeholder="Search here..." class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full" />
    </div>

    <!-- Price -->
    <div class="mb-4 border-b pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-dollar-sign"></i></span>
        <span class="font-semibold">Price</span>
      </div>
      <input type="range" min="200" max="5695" value="800" class="w-full accent-purple-500" />
      <div class="flex justify-between text-xs mt-1">
        <span class="bg-purple-500 text-white px-2 py-1 rounded">$200</span>
        <span class="bg-purple-500 text-white px-2 py-1 rounded">$800</span>
      </div>
      <div class="text-xs mt-2">Range : $200 - $5695</div>
    </div>

    <!-- Reviews -->
    <div class="mb-4 border-b pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-magic"></i></span>
        <span class="font-semibold">Reviews</span>
      </div>
      <div class="space-y-2">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span class="text-yellow-400">★★★★★</span>
          <span>5 Star</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span class="text-yellow-400">★★★★☆</span>
          <span>4 Star</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span class="text-yellow-400">★★★☆☆</span>
          <span>3 Star</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span class="text-yellow-400">★★☆☆☆</span>
          <span>2 Star</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span class="text-yellow-400">★☆☆☆☆</span>
          <span>1 Star</span>
        </label>
      </div>
    </div>

    <!-- Style -->
    <div class="mb-4 pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-building"></i></span>
        <span class="font-semibold">Style</span>
      </div>
      <div class="space-y-2">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span>Budget</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span>Midrange</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span>Luxury</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" />
          <span>Family Friendly</span>
        </label>
      </div>
    </div>

    <!-- Apply Filter Button -->
    <button class="bg-gray-900 text-white w-full py-3 rounded-lg font-semibold mt-2">Apply Filter</button>
  </div>
</template>
