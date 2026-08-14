@csrf

<div class="flex flex-col gap-6">
    <div>
        <label class="block text-sm text-forest-dark mb-1" for="name"
            >名称 <span class="text-red-600">*</span></label
        >
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $location->name ?? '') }}"
            class="w-full border border-forest-dark/30 rounded px-3 py-2"
        />
        @error ('name')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm text-forest-dark mb-1" for="address"
            >住所 <span class="text-red-600">*</span></label
        >
        <input
            type="text"
            name="address"
            id="address"
            value="{{ old('address', $location->address ?? '') }}"
            class="w-full border border-forest-dark/30 rounded px-3 py-2"
        />
        @error ('address')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm text-forest-dark mb-1" for="base_fee"
            >場所代（経費）</label
        >
        <input
            type="number"
            name="base_fee"
            id="base_fee"
            min="0"
            value="{{ old('base_fee', $location->base_fee ?? '') }}"
            class="w-full border border-forest-dark/30 rounded px-3 py-2"
        />
        @error ('base_fee')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label
            class="block text-sm text-forest-dark mb-1"
            for="price_addon_per_session"
            >交通費加算（円）</label
        >
        <input
            type="number"
            name="price_addon_per_session"
            id="price_addon_per_session"
            min="0"
            value="{{ old('price_addon_per_session', $location->price_addon_per_session ?? 0) }}"
            class="w-full border border-forest-dark/30 rounded px-3 py-2"
        />
        @error ('price_addon_per_session')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm text-forest-dark mb-1" for="map_url"
            >地図URL</label
        >
        <input
            type="text"
            name="map_url"
            id="map_url"
            value="{{ old('map_url', $location->map_url ?? '') }}"
            class="w-full border border-forest-dark/30 rounded px-3 py-2"
        />
        @error ('map_url')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex gap-8">
        <label class="flex items-center gap-2 text-sm text-forest-dark">
            <input
                type="checkbox"
                name="is_bookable"
                value="1"
                {{ old('is_bookable', $location->is_bookable ?? true) ? 'checked' : '' }}
            />
            予約可能にする
        </label>
    </div>
</div>
