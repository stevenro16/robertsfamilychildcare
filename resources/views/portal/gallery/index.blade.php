@extends('layouts.portal')
@section('title', 'Gallery')

@section('portal-content')
<div class="w-full" x-data="{
    uploadOpen: false,
    editOpen: false,
    editImage: { id: '', caption: '', takenAt: '' },

    uploading: false,
    uploadProgress: 0,
    uploadFileName: '',
    uploadDone: false,
    uploadError: '',

    onFileChange(e) {
        const f = e.target.files[0];
        this.uploadFileName = f ? f.name : '';
        this.uploadError = '';
    },

    doUpload(e) {
        e.preventDefault();
        if (!this.uploadFileName) return;
        const fd = new FormData(e.target);
        this.uploading = true;
        this.uploadDone = false;
        this.uploadProgress = 0;
        this.uploadError = '';
        const xhr = new XMLHttpRequest();
        xhr.upload.onprogress = ev => {
            if (ev.lengthComputable)
                this.uploadProgress = Math.round(ev.loaded / ev.total * 100);
        };
        xhr.onload = () => {
            this.uploadProgress = 100;
            this.uploadDone = true;
            setTimeout(() => window.location.reload(), 900);
        };
        xhr.onerror = () => {
            this.uploadError = 'Upload failed. Please try again.';
            this.uploading = false;
        };
        xhr.open('POST', e.target.action);
        xhr.send(fd);
    },

    closeUpload() {
        if (this.uploading) return;
        this.uploadOpen = false;
        this.uploading = false;
        this.uploadProgress = 0;
        this.uploadFileName = '';
        this.uploadDone = false;
        this.uploadError = '';
    }
}">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5">
            <x-icon name="image-plus" class="w-6 h-6 text-primary-500" />
            Gallery
        </h1>
        <button @click="uploadOpen = true" class="btn-primary gap-1.5">
            <x-icon name="image-plus" class="w-4 h-4" /> Upload Image
        </button>
    </div>
    <div class="h-0.5 bg-linear-to-r from-primary-400 to-transparent rounded-full mb-6"></div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($images->isEmpty())
        <div class="card p-12 text-center text-slate-400">
            <x-icon name="image-plus" class="w-10 h-10 mx-auto mb-3 text-slate-300" />
            <p class="font-medium">No images yet</p>
            <button @click="uploadOpen = true" class="btn-primary mt-4">Upload First Image</button>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($images as $image)
                <div class="group relative aspect-square rounded-xl overflow-hidden bg-slate-100">
                    <img src="{{ route('gallery.image', $image->id) }}" alt="{{ $image->caption }}"
                         class="w-full h-full object-cover">

                    {{-- Pencil edit button --}}
                    <button type="button"
                            @click.stop="editImage = { id: '{{ $image->id }}', caption: '{{ addslashes($image->caption ?? '') }}', takenAt: '{{ $image->takenAt ? $image->takenAt->format('Y-m-d') : '' }}' }; editOpen = true"
                            class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full bg-white/80 hover:bg-white text-slate-600 hover:text-slate-900 shadow transition-colors z-10">
                        <x-icon name="edit" class="w-3.5 h-3.5" />
                    </button>

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-colors flex flex-col items-center justify-center gap-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto">
                        @if($image->caption)
                            <p class="text-white text-xs px-3 text-center leading-snug">{{ $image->caption }}</p>
                        @endif
                        <form method="POST" action="{{ route('portal.gallery.destroy', $image->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-red-600 transition-colors"
                                    onclick="return confirm('Delete this image?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Edit caption modal --}}
    <div x-show="editOpen" x-cloak
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
         @click.self="editOpen = false">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm" @click.stop>
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Edit Caption</h2>
            <form method="POST" :action="'/portal/gallery/' + editImage.id">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="mb-4">
                    <label class="label">Caption</label>
                    <input type="text" name="caption"
                           x-model="editImage.caption"
                           class="input text-sm"
                           placeholder="e.g. Outdoor play time">
                </div>
                <div class="mb-5">
                    <label class="label">Date Taken</label>
                    <input type="date" name="takenAt"
                           x-model="editImage.takenAt"
                           class="input text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">Save</button>
                    <button type="button" @click="editOpen = false" class="btn-ghost flex-1">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Upload modal --}}
    <div x-show="uploadOpen" x-cloak
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
         @click.self="closeUpload()">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" @click.stop>

            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-800">Upload Photo</h2>
                <button type="button" @click="closeUpload()"
                        class="text-slate-400 hover:text-slate-600 transition-colors"
                        :class="uploading ? 'opacity-30 cursor-not-allowed' : ''">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6">
                <form @submit="doUpload" method="POST"
                      action="{{ route('portal.gallery.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- File picker --}}
                    <div class="mb-5">
                        <label class="block cursor-pointer rounded-xl border-2 border-dashed transition-colors"
                               :class="uploadFileName
                                   ? 'border-primary-400 bg-primary-50'
                                   : 'border-slate-200 hover:border-primary-300 hover:bg-slate-50'">
                            <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                                <template x-if="!uploadFileName">
                                    <div>
                                        <x-icon name="image-plus" class="w-9 h-9 mx-auto mb-3 text-slate-300" />
                                        <p class="text-sm font-medium text-slate-600 mb-1">Click to select a photo</p>
                                        <p class="text-xs text-slate-400">JPG, PNG, GIF, WEBP — up to 10 MB</p>
                                    </div>
                                </template>
                                <template x-if="uploadFileName">
                                    <div>
                                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-3">
                                            <x-icon name="check" class="w-5 h-5 text-primary-600" />
                                        </div>
                                        <p class="text-sm font-medium text-primary-700 break-all" x-text="uploadFileName"></p>
                                        <p class="text-xs text-slate-400 mt-1">Click to change</p>
                                    </div>
                                </template>
                            </div>
                            <input type="file" name="image" required accept="image/*"
                                   class="hidden" @change="onFileChange($event)"
                                   :disabled="uploading">
                        </label>
                    </div>

                    {{-- Caption + Date --}}
                    <div x-show="!uploading" x-transition class="mb-5 space-y-3">
                        <div>
                            <label class="label">Caption <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="text" name="caption" class="input text-sm"
                                   placeholder="e.g. Outdoor play time">
                        </div>
                        <div>
                            <label class="label">Date Taken <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="date" name="takenAt" class="input text-sm">
                        </div>
                    </div>

                    {{-- Progress section --}}
                    <div x-show="uploading" x-transition class="mb-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0">
                                <x-icon name="image" class="w-4 h-4 text-primary-600" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-700 truncate" x-text="uploadFileName"></p>
                                <p class="text-xs text-slate-400" x-text="uploadDone ? 'Upload complete!' : 'Uploading…'"></p>
                            </div>
                            <span class="text-sm font-semibold tabular-nums"
                                  :class="uploadDone ? 'text-primary-600' : 'text-slate-600'"
                                  x-text="uploadProgress + '%'"></span>
                        </div>

                        {{-- Track --}}
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300 ease-out"
                                 :class="uploadDone ? 'bg-primary-500' : 'bg-primary-400'"
                                 :style="'width:' + uploadProgress + '%'">
                            </div>
                        </div>
                    </div>

                    {{-- Error --}}
                    <p x-show="uploadError" x-text="uploadError"
                       class="text-red-500 text-xs mb-4 -mt-2"></p>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button type="submit"
                                class="btn-primary flex-1 justify-center"
                                :disabled="uploading || !uploadFileName">
                            <span x-show="!uploading">Upload Photo</span>
                            <span x-show="uploading" class="flex items-center gap-2 justify-center">
                                <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                </svg>
                                Uploading…
                            </span>
                        </button>
                        <button type="button" @click="closeUpload()"
                                class="btn-ghost flex-1"
                                :disabled="uploading">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
