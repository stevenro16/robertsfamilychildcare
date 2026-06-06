@extends('layouts.portal')
@section('title', 'Staff Accounts')

@section('portal-content')
<div x-data="{ createOpen: false, editId: null, editOpen: false, editData: {} }">

    <div class="max-w-4xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('portal.settings.index') }}" class="text-slate-400 hover:text-slate-600">
                <x-icon name="chevron-left" class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Staff Accounts</h1>
            <button @click="createOpen = true" class="btn-primary gap-1.5 ml-auto">
                <x-icon name="plus" class="w-4 h-4" /> New Account
            </button>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="card divide-y divide-slate-100">
            @foreach($employees as $emp)
                <div class="flex items-center justify-between gap-4 p-4">
                    <div>
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="font-semibold text-slate-800">{{ $emp->name }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $emp->role === 'ADMIN' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $emp->role }}
                            </span>
                            @if(!$emp->isActive)
                                <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Inactive</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-500">{{ $emp->email }}</p>
                    </div>
                    <button @click="editId = '{{ $emp->id }}'; editData = {{ json_encode(['name' => $emp->name, 'email' => $emp->email, 'role' => $emp->role, 'isActive' => (bool)$emp->isActive]) }}; editOpen = true"
                            class="btn-ghost text-xs">Edit</button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Create Account Modal --}}
    <div x-show="createOpen" x-cloak
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
         @click.self="createOpen = false">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-800">Create Staff Account</h2>
                <button @click="createOpen = false" class="text-slate-400 hover:text-slate-600">
                    <x-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>
            <form method="POST" action="{{ route('portal.accounts.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="label">Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required class="input text-sm" placeholder="Jane Smith">
                </div>
                <div class="mb-3">
                    <label class="label">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" required class="input text-sm" placeholder="jane@example.com">
                </div>
                <div class="mb-3">
                    <label class="label">Temporary Password <span class="text-red-400">*</span></label>
                    <input type="text" name="password" required class="input text-sm" minlength="8" placeholder="Min. 8 characters">
                </div>
                <div class="mb-6">
                    <label class="label">Role</label>
                    <select name="role" class="input text-sm">
                        <option value="STAFF">Staff</option>
                        <option value="ADMIN">Admin</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">Create Account</button>
                    <button type="button" @click="createOpen = false" class="btn-ghost flex-1">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Account Modal --}}
    <div x-show="editOpen" x-cloak
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
         @click.self="editOpen = false"
         @keydown.escape.window="editOpen = false">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-800">Edit Account</h2>
                <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600">
                    <x-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>
            <form method="POST" :action="'/portal/accounts/' + editId">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label class="label">Name</label>
                    <input type="text" name="name" class="input text-sm"
                           x-effect="$el.value = editData.name || ''">
                </div>
                <div class="mb-3">
                    <label class="label">Email / Login</label>
                    <input type="email" name="email" class="input text-sm"
                           x-effect="$el.value = editData.email || ''">
                </div>
                <div class="mb-3">
                    <label class="label">Role</label>
                    <select name="role" class="input text-sm"
                            x-effect="$el.value = editData.role || 'STAFF'">
                        <option value="STAFF">Staff</option>
                        <option value="ADMIN">Admin</option>
                    </select>
                </div>
                <div class="mb-3 flex items-center gap-2">
                    <input type="hidden" name="isActive" value="0">
                    <input type="checkbox" name="isActive" value="1" id="edit-isActive"
                           class="w-4 h-4 rounded border-slate-300 text-primary-600"
                           x-effect="$el.checked = !!editData.isActive">
                    <label for="edit-isActive" class="text-sm text-slate-700">Active account</label>
                </div>
                <div class="mb-6">
                    <label class="label">New Password <span class="text-slate-400 font-normal">(leave blank to keep current)</span></label>
                    <input type="text" name="password" class="input text-sm" placeholder="Min. 8 characters">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">Save Changes</button>
                    <button type="button" @click="editOpen = false" class="btn-ghost flex-1">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
