import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

Alpine.plugin(persist);
window.Alpine = Alpine;

// Schedule slider widget — used on the child edit page
// Slots: 0 = 7:00 AM, 20 = 5:00 PM (30-minute increments)
Alpine.data('scheduleWidget', (init) => ({
    days: init,
    dragging: null,  // { day: string, handle: 'dropoff'|'pickup' }

    slotToTime(slot) {
        const totalMins = 420 + slot * 30;
        const h = Math.floor(totalMins / 60);
        const m = totalMins % 60;
        const ampm = h >= 12 ? 'PM' : 'AM';
        const h12 = h === 0 ? 12 : h > 12 ? h - 12 : h;
        return `${h12}:${String(m).padStart(2, '0')} ${ampm}`;
    },

    slotToValue(slot) {
        const totalMins = 420 + slot * 30;
        const h = Math.floor(totalMins / 60);
        const m = totalMins % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    },

    startDrag(day, handle) {
        this.dragging = { day, handle };
    },

    onMouseMove(e) {
        if (!this.dragging) return;
        const bar = document.getElementById('strack-' + this.dragging.day);
        if (!bar) return;
        const rect = bar.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const pct = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
        const slot = Math.round(pct * 20);
        const d = this.days[this.dragging.day];
        if (this.dragging.handle === 'dropoff') {
            d.dropoff = Math.max(0, Math.min(slot, d.pickup - 1));
        } else {
            d.pickup = Math.min(20, Math.max(slot, d.dropoff + 1));
        }
    },

    stopDrag() {
        this.dragging = null;
    },

    handleTrackClick(e, day) {
        const rect = e.currentTarget.getBoundingClientRect();
        const pct = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
        const slot = Math.round(pct * 20);
        const d = this.days[day];
        const mid = (d.dropoff + d.pickup) / 2;
        if (slot <= mid) {
            d.dropoff = Math.max(0, Math.min(slot, d.pickup - 1));
            this.dragging = { day, handle: 'dropoff' };
        } else {
            d.pickup = Math.min(20, Math.max(slot, d.dropoff + 1));
            this.dragging = { day, handle: 'pickup' };
        }
    },
}));

Alpine.data('childPhotoUpload', () => ({
    file: null,
    preview: null,
    progress: 0,
    uploading: false,
    done: false,
    error: '',
    dragging: false,
    confirmRemove: false,

    pickFile(files) {
        const f = files[0];
        if (!f) return;
        if (f.size > 5 * 1024 * 1024) {
            this.error = 'File must be 5 MB or less.';
            return;
        }
        if (this.preview) URL.revokeObjectURL(this.preview);
        this.file    = f;
        this.preview = URL.createObjectURL(f);
        this.error   = '';
    },

    upload(url) {
        if (!this.file || this.uploading) return;
        const fd = new FormData();
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        fd.append('photo', this.file);

        const xhr = new XMLHttpRequest();
        this.uploading = true;
        this.progress  = 0;
        this.error     = '';

        xhr.upload.addEventListener('progress', ev => {
            if (ev.lengthComputable)
                this.progress = Math.round(ev.loaded / ev.total * 100);
        });

        xhr.addEventListener('load', () => {
            this.progress = 100;
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.ok) {
                    this.done = true;
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    this.error    = res.message ?? 'Upload failed.';
                    this.uploading = false;
                }
            } catch {
                this.error    = 'Upload failed.';
                this.uploading = false;
            }
        });

        xhr.addEventListener('error', () => {
            this.error    = 'Upload failed. Please try again.';
            this.uploading = false;
        });

        xhr.open('POST', url);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.send(fd);
    },

    reset() {
        if (this.preview) URL.revokeObjectURL(this.preview);
        this.file          = null;
        this.preview       = null;
        this.progress      = 0;
        this.uploading     = false;
        this.done          = false;
        this.error         = '';
        this.dragging      = false;
        this.confirmRemove = false;
    },
}));

Alpine.start();
