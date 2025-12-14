@extends('layouts.admin')

@section('title', 'Edit Field')
@section('page-title', 'Edit Field')
@section('page-subtitle', 'Update field information')

@section('content')
<div class="max-w-3xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.fields.index') }}" 
           class="inline-flex items-center text-gray-400 hover:text-green-400 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Fields
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-gray-900 rounded-lg border-2 border-gray-800 p-8">
        <form method="POST" action="{{ route('admin.fields.update', $field->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Field Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">
                    Field Name <span class="text-red-400">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $field->name) }}"
                       class="w-full px-4 py-3 bg-gray-800 border-2 border-gray-700 rounded-lg text-white focus:border-green-500 focus:outline-none transition-colors"
                       placeholder="e.g., Lapangan A - Premium"
                       required>
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field Type -->
            <div>
                <label for="type" class="block text-sm font-semibold text-gray-300 mb-2">
                    Field Type <span class="text-red-400">*</span>
                </label>
                <select id="type" 
                        name="type"
                        class="w-full px-4 py-3 bg-gray-800 border-2 border-gray-700 rounded-lg text-white focus:border-green-500 focus:outline-none transition-colors"
                        required>
                    <option value="">Select Type</option>
                    <option value="Sintetis" {{ old('type', $field->type) === 'Sintetis' ? 'selected' : '' }}>Sintetis (Synthetic Grass)</option>
                    <option value="Vinyl" {{ old('type', $field->type) === 'Vinyl' ? 'selected' : '' }}>Vinyl</option>
                </select>
                @error('type')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price Per Hour -->
            <div>
                <label for="price_per_hour" class="block text-sm font-semibold text-gray-300 mb-2">
                    Price Per Hour (Rp) <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" 
                           id="price_per_hour" 
                           name="price_per_hour" 
                           value="{{ old('price_per_hour', $field->price_per_hour) }}"
                           class="w-full pl-12 pr-4 py-3 bg-gray-800 border-2 border-gray-700 rounded-lg text-white focus:border-green-500 focus:outline-none transition-colors"
                           placeholder="150000"
                           min="0"
                           step="1000"
                           required>
                </div>
                @error('price_per_hour')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-300 mb-2">
                    Status <span class="text-red-400">*</span>
                </label>
                <select id="status" 
                        name="status"
                        class="w-full px-4 py-3 bg-gray-800 border-2 border-gray-700 rounded-lg text-white focus:border-green-500 focus:outline-none transition-colors"
                        required>
                    <option value="available" {{ old('status', $field->status) === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="maintenance" {{ old('status', $field->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="unavailable" {{ old('status', $field->status) === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
                @error('status')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-300 mb-2">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-3 bg-gray-800 border-2 border-gray-700 rounded-lg text-white focus:border-green-500 focus:outline-none transition-colors resize-none"
                          placeholder="Describe the field features, facilities, etc.">{{ old('description', $field->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field Image Upload -->
            <div>
                <label for="image" class="block text-sm font-semibold text-gray-300 mb-2">
                    Field Image
                </label>
                
                <!-- Current Image -->
                @if($field->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 mb-2">Current Image:</p>
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $field->image) }}" 
                                 alt="{{ $field->name }}" 
                                 class="max-w-full h-48 rounded-lg border-2 border-gray-700">
                        </div>
                    </div>
                @endif
                
                <div class="relative">
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/jpeg,image/png,image/jpg"
                           class="hidden"
                           onchange="previewImage(event)">
                    <label for="image" 
                           class="flex items-center justify-center w-full px-4 py-8 bg-gray-800 border-2 border-dashed border-gray-700 rounded-lg cursor-pointer hover:border-green-500 transition-colors">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-600 mb-3"></i>
                            <p class="text-gray-400 mb-1">{{ $field->image ? 'Click to change field image' : 'Click to upload field image' }}</p>
                            <p class="text-xs text-gray-500">JPG, PNG (Max 2MB)</p>
                        </div>
                    </label>
                </div>
                
                <!-- New Image Preview -->
                <div id="imagePreview" class="hidden mt-4">
                    <p class="text-sm text-gray-400 mb-2">New Preview:</p>
                    <div class="relative inline-block">
                        <img id="preview" src="" alt="Preview" class="max-w-full h-48 rounded-lg border-2 border-gray-700">
                        <button type="button" 
                                onclick="removeImage()" 
                                class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4 pt-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all neon-border">
                    <i class="fas fa-save mr-2"></i>Update Field
                </button>
                <a href="{{ route('admin.fields.index') }}" 
                   class="flex-1 px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg font-semibold text-center transition-all border-2 border-gray-700">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        // Check file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('preview').src = '';
}
</script>
@endsection
