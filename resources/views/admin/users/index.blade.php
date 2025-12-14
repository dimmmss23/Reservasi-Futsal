@extends('layouts.admin')

@section('title', 'Manage Users - Admin Futsal ID')

@section('content')

<div class="container-fluid px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">
                <i class="fas fa-users mr-3 text-neon-green"></i>
                User Management
            </h1>
            <p class="text-gray-400">Kelola semua user sistem (Admin & Member)</p>
        </div>
        <a href="{{ route('admin.users.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg hover:shadow-green-500/50">
            <i class="fas fa-plus-circle mr-2"></i>
            Tambah User Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Total Users</p>
                    <p class="text-3xl font-bold text-white">{{ $totalUsers }}</p>
                </div>
                <div class="bg-blue-500/20 p-4 rounded-lg">
                    <i class="fas fa-users text-3xl text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- Total Admins -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Administrators</p>
                    <p class="text-3xl font-bold text-white">{{ $totalAdmins }}</p>
                </div>
                <div class="bg-purple-500/20 p-4 rounded-lg">
                    <i class="fas fa-user-shield text-3xl text-purple-400"></i>
                </div>
            </div>
        </div>

        <!-- Total Members -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Members</p>
                    <p class="text-3xl font-bold text-white">{{ $totalMembers }}</p>
                </div>
                <div class="bg-green-500/20 p-4 rounded-lg">
                    <i class="fas fa-user text-3xl text-green-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-500/20 border-2 border-green-500 text-green-400 px-6 py-4 rounded-lg flex items-center">
        <i class="fas fa-check-circle text-2xl mr-4"></i>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-500/20 border-2 border-red-500 text-red-400 px-6 py-4 rounded-lg flex items-center">
        <i class="fas fa-exclamation-circle text-2xl mr-4"></i>
        <span class="font-semibold">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Users Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-900 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-400 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-400 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4">
                            <span class="text-white font-mono">#{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">{{ $user->name }}</p>
                                    @if($user->id === Auth::id())
                                        <span class="text-xs text-yellow-400">(You)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            <i class="fas fa-envelope text-gray-500 mr-2"></i>
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            @if($user->phone)
                                <i class="fas fa-phone text-gray-500 mr-2"></i>
                                {{ $user->phone }}
                            @else
                                <span class="text-gray-500 italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-xs font-bold border border-purple-500 whitespace-nowrap">
                                    <i class="fas fa-shield-alt mr-1"></i>ADMIN
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-bold border border-green-500 whitespace-nowrap">
                                    <i class="fas fa-user mr-1"></i>MEMBER
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 px-3 py-2 rounded-lg transition border border-blue-500"
                                   title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete Button -->
                                @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}? Semua data reservasi user ini juga akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500/20 hover:bg-red-500/30 text-red-400 px-3 py-2 rounded-lg transition border border-red-500"
                                                title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button disabled 
                                            class="bg-gray-700 text-gray-500 px-3 py-2 rounded-lg cursor-not-allowed"
                                            title="Cannot delete yourself">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-users text-5xl mb-4 opacity-20"></i>
                            <p class="text-lg">Belum ada user terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
