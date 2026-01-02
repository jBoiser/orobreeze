<div x-data="{ isOpen: @entangle('isOpen') }">
    <button wire:click="toggleModal" class="w-full bg-white text-[#062a4d] hover:bg-cyan-50 font-bold py-4 rounded-xl transition-all shadow-lg hover:shadow-cyan-400/10">
        Request a Consultation
    </button>

    <template x-teleport="body">
        <div x-show="isOpen"
            x-transition.opacity
            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">

            <div x-show="isOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="frost-card relative w-[95%] max-w-lg mx-auto rounded-3xl p-6 md:p-8 shadow-2xl"
                @click.away="isOpen = false">

                <button wire:click="toggleModal" class="absolute top-5 right-5 text-white/50 hover:text-white text-2xl z-10">&times;</button>

                @if($submitted)
                <div class="text-center py-10">
                    <div class="w-16 h-16 bg-cyan-400/20 text-cyan-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Request Sent!</h3>
                    <p class="text-blue-100/60">We'll contact you shortly to discuss your air comfort needs.</p>
                    <button wire:click="toggleModal" class="mt-6 text-cyan-400 text-sm font-semibold uppercase tracking-widest">Close</button>
                </div>
                @else
                <h3 class="text-2xl font-bold text-white mb-2 text-left">Free Consultation</h3>
                <p class="text-blue-100/60 mb-6 italic text-left">Innovating your comfort, one step at a time.</p>

                <form wire:submit.prevent="sendRequest" class="space-y-4 text-left">
                    <x-honeypot wire:model="honeypotData" />
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-cyan-300 mb-2">Your Email</label>
                        <input type="email" wire:model="email" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition-all text-white">
                        @error('email') <span class="text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-cyan-300 mb-2">How can we help?</label>
                        <textarea wire:model="message" rows="4" placeholder="e.g. AC Installation for my office..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition-all text-white"></textarea>
                        @error('message') <span class="text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="sendRequest"
                        class="relative w-full bg-cyan-400 hover:bg-cyan-300 disabled:opacity-70 disabled:cursor-not-allowed
               text-slate-900 font-bold py-4 rounded-xl transition-all shadow-lg shadow-cyan-400/20
               mt-4 overflow-hidden"> <span wire:loading.class="opacity-0"
                            wire:target="sendRequest"
                            class="block text-center transition-opacity duration-200">
                            Send Request
                        </span>

                        <div wire:loading.flex
                            wire:target="sendRequest"
                            class="absolute inset-0 flex items-center justify-center">

                            <div class="flex flex-row items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-slate-900"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                </svg>
                            </div>
                        </div>
                    </button>

                </form>
                @endif
            </div>
        </div>
    </template>
</div>