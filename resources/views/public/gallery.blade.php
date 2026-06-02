@extends('layouts.public')
@section('title', 'Gallery — Roberts Family ChildCare')

@section('content')
<section class="bg-linear-to-br from-primary-500 to-primary-700 text-white">
    <div class="wide py-8 lg:py-10">
        <p class="text-primary-200 text-sm font-medium uppercase tracking-widest mb-4">Our Space</p>
        <h1 class="text-5xl lg:text-6xl font-bold mb-5">Gallery</h1>
        <p class="text-primary-100 text-xl max-w-xl">A glimpse into our warm, joyful environment.</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="wide">
        @if($images->isEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
                @foreach(['from-primary-100 to-primary-200','from-amber-100 to-amber-200','from-emerald-100 to-emerald-200','from-sky-100 to-sky-200','from-rose-100 to-rose-200','from-violet-100 to-violet-200'] as $g)
                    <div class="aspect-3/2 rounded-2xl bg-linear-to-br {{ $g }}"></div>
                @endforeach
            </div>
            <p class="text-center text-slate-400 text-sm">Gallery images coming soon — check back!</p>
        @else

        @php
            $imageData = $images->map(fn($img) => [
                'url'     => route('gallery.image', $img->id),
                'caption' => $img->caption ?? '',
                'date'    => $img->takenAt ? $img->takenAt->format('M j, Y') : '',
            ])->values()->toArray();
        @endphp

        <div x-data="{
                images:   {{ Js::from($imageData) }},
                visible:  3,
                galleryH: Math.max(150, Math.round(window.innerHeight * 0.62)),
                cur:      0,
                noAnim:   false,
                running:  false,
                timer:    null,
                wts:      [],

                get n() { return this.images.length; },

                get display() {
                    if (this.n <= this.visible) return this.images;
                    return [
                        ...this.images.slice(-this.visible),
                        ...this.images,
                        ...this.images.slice(0, this.visible),
                    ];
                },

                get offsetPx() {
                    if (!this.wts.length) return 0;
                    const start = (this.n > this.visible) ? this.visible : 0;
                    let px = 0;
                    for (let i = 0; i < start + this.cur; i++) px += (this.wts[i] || 0);
                    return px;
                },

                get trackStyle() {
                    return 'height:' + this.galleryH + 'px;' +
                           'transform:translateX(-' + this.offsetPx + 'px);' +
                           'transition:transform ' + (this.noAnim ? '0s' : '0.65s cubic-bezier(0.25,0.46,0.45,0.94)');
                },

                measureWidths() {
                    const els = this.$refs.track?.children;
                    if (!els || !els.length) return;
                    const ws = Array.from(els).map(el => el.offsetWidth);
                    if (ws.some(w => w > 0)) this.wts = ws;
                },

                snap(newCur) {
                    this.noAnim = true;
                    this.cur = newCur;
                    this.$nextTick(() => setTimeout(() => { this.noAnim = false; this.running = false; }, 20));
                },

                advance() {
                    if (this.running) return;
                    this.running = true;
                    this.cur++;
                    if (this.cur >= this.n) {
                        setTimeout(() => this.snap(0), 650);
                    } else {
                        setTimeout(() => { this.running = false; }, 650);
                    }
                },

                prev() {
                    clearInterval(this.timer);
                    if (this.running) return;
                    this.running = true;
                    this.cur--;
                    if (this.cur < 0) {
                        setTimeout(() => this.snap(this.n - 1), 650);
                    } else {
                        setTimeout(() => { this.running = false; }, 650);
                    }
                    this.startTimer();
                },

                next() {
                    clearInterval(this.timer);
                    this.advance();
                    this.startTimer();
                },

                startTimer() {
                    this.timer = setInterval(() => this.advance(), 5000);
                },

                dotActive(i) {
                    return i === ((this.cur % this.n + this.n) % this.n);
                },

                init() {
                    // Pre-fill with 3:2 assumption so translation is correct before images load
                    this.wts = this.display.map(() => Math.round(this.galleryH * 1.5) + 12);
                    this.startTimer();
                    // Keep height in sync when the window is resized
                    window.addEventListener('resize', () => {
                        this.galleryH = Math.max(150, Math.round(window.innerHeight * 0.62));
                        this.wts = this.display.map(() => Math.round(this.galleryH * 1.5) + 12);
                        this.$nextTick(() => this.measureWidths());
                    });
                    // Re-measure once real image dimensions are available
                    this.$nextTick(() => {
                        const imgs = Array.from(this.$refs.track.querySelectorAll('img'));
                        let pending = imgs.filter(i => !i.complete).length;
                        if (pending === 0) { this.measureWidths(); return; }
                        imgs.forEach(img => {
                            if (!img.complete) {
                                img.addEventListener('load',  () => { if (--pending <= 0) this.measureWidths(); }, { once: true });
                                img.addEventListener('error', () => { if (--pending <= 0) this.measureWidths(); }, { once: true });
                            }
                        });
                    });
                }
            }">

            <div class="relative">
                <div class="overflow-hidden rounded-2xl">
                    {{-- No static style here — height is owned entirely by :style / trackStyle --}}
                    <div class="flex" x-ref="track" :style="trackStyle">
                        <template x-for="(img, i) in display" :key="i">
                            <div class="shrink-0 h-full px-1.5">
                                <div class="relative h-full overflow-hidden rounded-xl bg-slate-100"
                                     style="width:max-content">
                                    <img :src="img.url" :alt="img.caption"
                                         class="h-full w-auto block">
                                    <div x-show="img.caption || img.date"
                                         class="absolute inset-x-0 bottom-0 bg-linear-to-t from-black/60 to-transparent px-3 py-3">
                                        <p x-show="img.date"
                                           class="text-white/60 text-xs font-semibold uppercase tracking-wide mb-0.5 leading-none"
                                           x-text="img.date"></p>
                                        <p x-show="img.caption"
                                           class="text-white text-sm font-medium leading-snug"
                                           x-text="img.caption"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Prev arrow --}}
                <button @click="prev()"
                        class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm shadow-md hover:shadow-lg text-slate-600 hover:text-primary-600 flex items-center justify-center transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </button>

                {{-- Next arrow --}}
                <button @click="next()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm shadow-md hover:shadow-lg text-slate-600 hover:text-primary-600 flex items-center justify-center transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
            </div>

            {{-- Dot indicators --}}
            <div class="flex justify-center gap-2 mt-5">
                <template x-for="(img, i) in images" :key="i">
                    <button @click="cur = i; clearInterval(timer); startTimer()"
                            class="rounded-full transition-all duration-300"
                            :class="dotActive(i)
                                ? 'w-5 h-2 bg-primary-500'
                                : 'w-2 h-2 bg-slate-300 hover:bg-slate-400'">
                    </button>
                </template>
            </div>

        </div>
        @endif
    </div>
</section>
@endsection
