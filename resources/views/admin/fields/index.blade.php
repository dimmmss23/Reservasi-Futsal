@extends('layouts.admin')

@section('title', 'Manage Fields')
@section('page-title', 'Manage Fields')
@section('page-subtitle', 'Create, edit, and manage futsal fields')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-display font-bold text-white">Semua Lapangan</h2>
            <p class="text-gray-400">Kelola semua lapangan futsal dalam sistem</p>
        </div>
        <a href="{{ route('admin.fields.create') }}" 
           class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all neon-border flex items-center space-x-2">
            <i class="fas fa-plus-circle"></i>
            <span>Tambah Lapangan Baru</span>
        </a>
    </div>

    <!-- Fields Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fields as $field)
        <div class="bg-gray-900 rounded-lg border-2 border-gray-800 overflow-hidden hover:border-green-500 transition-all duration-300 group">
            <!-- Field Image Placeholder -->
            <div class="h-48 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center relative overflow-hidden">
                @if($field->image)
                    <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-futbol text-6xl text-gray-700 group-hover:text-green-500 transition-colors"></i>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @if($field->status === 'available')
                        <span class="px-3 py-1 bg-green-900/80 text-green-400 rounded-full text-xs font-semibold border border-green-700 backdrop-blur-sm">
                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                        </span>
                    @elseif($field->status === 'maintenance')
                        <span class="px-3 py-1 bg-yellow-900/80 text-yellow-400 rounded-full text-xs font-semibold border border-yellow-700 backdrop-blur-sm">
                            <i class="fas fa-tools mr-1"></i>Pemeliharaan
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-900/80 text-red-400 rounded-full text-xs font-semibold border border-red-700 backdrop-blur-sm">
                            <i class="fas fa-times-circle mr-1"></i>Tidak Tersedia
                        </span>
                    @endif
                </div>
            </div>

            <!-- Field Info -->
            <div class="p-6">
                <h3 class="text-xl font-display font-bold text-white mb-2">{{ $field->name }}</h3>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-gray-400">
                        <i class="fas fa-layer-group w-5 mr-2 text-green-400"></i>
                        <span>{{ $field->type }}</span>
                    </div>
                    <div class="flex items-center text-gray-400">
                        <i class="fas fa-money-bill-wave w-5 mr-2 text-green-400"></i>
                        <span class="text-white font-semibold">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                        <span class="text-xs ml-1">/ jam</span>
                    </div>
                </div>

                @if($field->description)
                <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $field->description }}</p>
                @else
                <p class="text-gray-600 text-sm mb-4 italic">Tidak ada deskripsi</p>
                @endif

                <!-- Actions -->
                <div class="space-y-2">
                    <a href="{{ route('admin.fields.edit', $field->id) }}" 
                       class="block px-4 py-2 bg-blue-900/50 hover:bg-blue-800 text-blue-400 rounded border border-blue-700 text-center font-semibold transition-all text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    
                    <form method="POST" action="{{ route('admin.fields.update', $field->id) }}" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $field->name }}">
                        <input type="hidden" name="type" value="{{ $field->type }}">
                        <input type="hidden" name="price_per_hour" value="{{ $field->price_per_hour }}">
                        <input type="hidden" name="description" value="{{ $field->description }}">
                        <select name="status" 
                                onchange="this.form.submit()"
                                class="w-full px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded border border-gray-700 font-semibold transition-all text-sm cursor-pointer">
                            <option value="available" {{ $field->status === 'available' ? 'selected' : '' }}>✅ Tersedia</option>
                            <option value="maintenance" {{ $field->status === 'maintenance' ? 'selected' : '' }}>🔧 Pemeliharaan</option>
                            <option value="unavailable" {{ $field->status === 'unavailable' ? 'selected' : '' }}>❌ Tidak Tersedia</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-700 mb-4"></i>
            <p class="text-gray-500 text-lg mb-4">Tidak ada lapangan ditemukan</p>
            <a href="{{ route('admin.fields.create') }}" 
               class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all neon-border">
                <i class="fas fa-plus mr-2"></i>Tambah Lapangan Pertama
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
