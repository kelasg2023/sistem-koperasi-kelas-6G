// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        items: [],
        init() {
            const stored = localStorage.getItem('koperasi_cart');
            if (stored) {
                try {
                    this.items = JSON.parse(stored);
                } catch (e) {
                    this.items = [];
                }
            }
        },
        get count() {
            return this.items.reduce((sum, item) => sum + (item.qty || 1), 0);
        },
        add(product) {
            let existing = this.items.find(i => i.id === product.id);
            if (existing) {
                existing.qty += 1;
            } else {
                this.items.push({ ...product, qty: 1, selected: true });
            }
            this.save();
            
            if (window.Swal) {
                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Ditambahkan ke keranjang',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        },
        save() {
            localStorage.setItem('koperasi_cart', JSON.stringify(this.items));
            // Trigger storage event for other tabs if needed, though alpine reactivity handles current tab
            window.dispatchEvent(new Event('cart-updated'));
        }
    });
});

Alpine.start();

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Laravel Echo & Pusher (Reverb)
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Dengarkan notifikasi global dari Backend
window.Echo.channel('tugas-channel').listen('DataUpdated', (e) => {
    if (window.Swal) {
        window.Swal.fire({
            toast: true, 
            position: 'top-end', 
            icon: 'info',
            title: 'Notifikasi Baru', 
            text: typeof e.payload === 'string' ? e.payload : JSON.stringify(e.payload),
            showConfirmButton: false, 
            timer: 3000
        });
    }
});
