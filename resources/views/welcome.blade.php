<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pesan tiket penerbangan internasional dengan mudah bersama AirTicket Intl.">
    <title>AirTicket Intl | Jelajahi Dunia</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <script>
        (() => {
            const savedTheme = localStorage.getItem('airticket-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('theme-dark', savedTheme === 'dark' || (!savedTheme && prefersDark));
        })();
    </script>
    <style>
        html,
        body,
        section,
        article,
        form,
        input,
        select,
        button {
            transition: background-color 180ms ease, border-color 180ms ease, color 180ms ease;
        }

        html.theme-dark body {
            background: #0b1220 !important;
            color: #e2e8f0 !important;
        }

        html.theme-dark .bg-white {
            background-color: #111c2e !important;
        }

        html.theme-dark .bg-slate-50,
        html.theme-dark .bg-\[\#f6f8fb\] {
            background-color: #0b1220 !important;
        }

        html.theme-dark .bg-cyan-50,
        html.theme-dark .bg-blue-50 {
            background-color: #123047 !important;
        }

        html.theme-dark .text-slate-950,
        html.theme-dark .text-slate-900,
        html.theme-dark .text-slate-800,
        html.theme-dark .text-slate-700,
        html.theme-dark .text-gray-900,
        html.theme-dark .text-gray-800 {
            color: #f8fafc !important;
        }

        html.theme-dark .text-slate-600,
        html.theme-dark .text-slate-500,
        html.theme-dark .text-gray-600,
        html.theme-dark .text-gray-500,
        html.theme-dark .text-gray-400 {
            color: #94a3b8 !important;
        }

        html.theme-dark .border-slate-200,
        html.theme-dark .border-slate-100,
        html.theme-dark .border-gray-100 {
            border-color: #263750 !important;
        }

        html.theme-dark input,
        html.theme-dark select {
            color: #f8fafc !important;
            background-color: transparent !important;
        }

        html.theme-dark option {
            background: #111c2e;
            color: #f8fafc;
        }

        html.theme-dark #cara-kerja {
            background-color: #0f1a2b !important;
            border-color: #263750 !important;
        }

        html.theme-dark #theme-toggle {
            background-color: rgba(255, 255, 255, .12);
            color: #f8fafc;
            border-color: rgba(255, 255, 255, .2);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f6f8fb] font-sans text-slate-900 antialiased">
    @php
        $routeImages = [
            'PEN' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1000&q=85',
            'KUL' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?auto=format&fit=crop&w=1000&q=85',
            'BKK' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=1000&q=85',
            'DXB' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1000&q=85',
            'SIN' => 'https://images.unsplash.com/photo-1565967511849-76a60a516170?auto=format&fit=crop&w=1000&q=85',
            'HND' => 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&w=1000&q=85',
            'LHR' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=1000&q=85',
            'JFK' => 'https://images.unsplash.com/photo-1485871981521-5b1fd3805eee?auto=format&fit=crop&w=1000&q=85',
        ];
        $fallbackRoutes = [
            ['from' => 'Medan', 'from_code' => 'KNO', 'to' => 'Penang', 'to_code' => 'PEN', 'airline' => 'Indonesia AirAsia', 'price' => 850000, 'badge' => 'Direct'],
            ['from' => 'Jakarta', 'from_code' => 'CGK', 'to' => 'Kuala Lumpur', 'to_code' => 'KUL', 'airline' => 'AirAsia', 'price' => 1250000, 'badge' => 'Direct'],
            ['from' => 'Yogyakarta', 'from_code' => 'YIA', 'to' => 'Singapore', 'to_code' => 'SIN', 'airline' => 'Scoot', 'price' => 1350000, 'badge' => 'Direct'],
        ];
    @endphp

    <header class="absolute inset-x-0 top-0 z-30">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 lg:px-8" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-white"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-xl ring-1 ring-white/25">✈</span><span class="text-lg font-extrabold tracking-tight">AirTicket <span class="text-cyan-200">Intl</span></span></a>
            <div class="hidden items-center gap-8 text-sm font-semibold text-white/80 md:flex"><a href="#destinasi" class="transition hover:text-white">Destinasi</a><a href="#cara-kerja" class="transition hover:text-white">Cara kerja</a><a href="#faq" class="transition hover:text-white">Bantuan</a></div>
            <div class="flex items-center gap-2 text-sm font-bold"><button id="theme-toggle" type="button" class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/20 bg-white/10 text-lg text-white transition hover:bg-white/20" aria-label="Aktifkan tema gelap" title="Ganti tema"><span id="theme-icon" aria-hidden="true">☾</span></button>@auth<a href="{{ route('dashboard') }}" class="rounded-lg bg-white px-4 py-2.5 text-slate-900 shadow-lg transition hover:bg-cyan-50">Dashboard</a>@else<a href="{{ route('login') }}" class="hidden px-3 py-2.5 text-white/90 transition hover:text-white sm:block">Masuk</a><a href="{{ route('register') }}" class="rounded-lg bg-cyan-400 px-4 py-2.5 text-slate-950 shadow-lg shadow-cyan-950/20 transition hover:bg-cyan-300">Daftar</a>@endauth</div>
        </nav>
    </header>

    <main>
        <section id="hero" class="relative isolate min-h-[680px] overflow-hidden bg-slate-950 text-white">
            <div class="absolute inset-0 -z-20 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=2200&q=90');"></div>
            <div class="absolute inset-0 -z-10 bg-slate-950/65"></div><div class="absolute inset-0 -z-10 bg-[linear-gradient(110deg,rgba(5,19,38,.88),rgba(7,45,74,.56),rgba(5,19,38,.18))]"></div>
            <div class="mx-auto max-w-7xl px-5 pb-36 pt-36 lg:px-8"><div class="max-w-3xl"><p class="mb-5 inline-flex items-center gap-2 rounded-full border border-cyan-200/30 bg-cyan-200/10 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.18em] text-cyan-100"><span class="h-1.5 w-1.5 rounded-full bg-cyan-300"></span> Perjalanan internasional dimulai di sini</p><h1 class="max-w-2xl text-5xl font-extrabold leading-[1.04] tracking-[-0.04em] sm:text-7xl">Dunia terasa lebih dekat dari sini.</h1><p class="mt-6 max-w-xl text-base leading-7 text-slate-200 sm:text-lg">Temukan penerbangan terbaik, pilih kursi favorit, dan terima e-ticket resmi dalam satu perjalanan pemesanan yang tenang.</p></div>
                <form action="{{ route('booking.create') }}" method="GET" class="mt-12 rounded-2xl bg-white p-3 text-slate-900 shadow-2xl shadow-slate-950/30 sm:p-5"><div class="mb-4 flex flex-wrap items-center gap-5 border-b border-slate-100 px-2 pb-4 text-sm font-bold"><label class="flex cursor-pointer items-center gap-2 text-slate-900"><input type="radio" name="trip_type" value="one_way" checked class="text-cyan-600 focus:ring-cyan-500"> Sekali jalan</label><label class="flex cursor-pointer items-center gap-2 text-slate-500"><input type="radio" name="trip_type" value="round_trip" class="text-cyan-600 focus:ring-cyan-500"> Pulang pergi</label></div><div class="grid gap-3 md:grid-cols-2 lg:grid-cols-5">
                    <label class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-cyan-500 focus-within:bg-white"><span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Dari</span><select name="origin" class="mt-1 w-full bg-transparent text-sm font-bold outline-none"><option value="CGK">Jakarta (CGK)</option><option value="SUB">Surabaya (SUB)</option><option value="DPS">Bali (DPS)</option></select></label>
                    <label class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-cyan-500 focus-within:bg-white"><span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Ke</span><select name="destination" class="mt-1 w-full bg-transparent text-sm font-bold outline-none"><option value="DXB">Dubai (DXB)</option><option value="SIN">Singapore (SIN)</option><option value="HND">Tokyo (HND)</option><option value="LHR">London (LHR)</option><option value="JFK">New York (JFK)</option></select></label>
                    <label class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-cyan-500 focus-within:bg-white"><span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal pergi</span><input type="date" name="date" min="{{ now()->toDateString() }}" value="{{ now()->addDay()->toDateString() }}" class="mt-1 w-full bg-transparent text-sm font-bold outline-none"></label>
                    <label class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-cyan-500 focus-within:bg-white"><span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Kelas</span><select name="seat_class" class="mt-1 w-full bg-transparent text-sm font-bold outline-none"><option value="Economy">Economy</option><option value="Business">Business</option><option value="First Class">First Class</option></select></label>
                    <label class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition focus-within:border-cyan-500 focus-within:bg-white"><span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Penumpang</span><select name="passengers_count" class="mt-1 w-full bg-transparent text-sm font-bold outline-none"><option value="1">1 penumpang</option><option value="2">2 penumpang</option><option value="3">3 penumpang</option><option value="4">4 penumpang</option></select></label></div><button type="submit" class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-cyan-500 px-6 py-3.5 text-sm font-extrabold text-slate-950 transition hover:bg-cyan-400 lg:mt-4">Cari penerbangan <span aria-hidden="true">→</span></button></form>
            </div>
        </section>

        <section class="relative z-10 mx-auto -mt-16 max-w-6xl px-5 lg:px-8"><div class="grid overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xl shadow-slate-900/10 sm:grid-cols-2 lg:grid-cols-4"><div class="border-b border-slate-100 p-6 sm:border-r lg:border-b-0"><div class="mb-3 text-xl text-cyan-600">◈</div><strong class="block text-sm">Midtrans secure</strong><span class="mt-1 block text-xs text-slate-500">Pembayaran terenkripsi</span></div><div class="border-b border-slate-100 p-6 lg:border-b-0 lg:border-r"><div class="mb-3 text-xl text-cyan-600">◎</div><strong class="block text-sm">50+ destinasi</strong><span class="mt-1 block text-xs text-slate-500">Rute internasional pilihan</span></div><div class="border-b border-slate-100 p-6 sm:border-r sm:border-b-0 lg:border-r"><div class="mb-3 text-xl text-cyan-600">▣</div><strong class="block text-sm">E-ticket instan</strong><span class="mt-1 block text-xs text-slate-500">PDF siap setelah lunas</span></div><div class="p-6"><div class="mb-3 text-xl text-cyan-600">◌</div><strong class="block text-sm">Support 24/7</strong><span class="mt-1 block text-xs text-slate-500">Bantuan saat dibutuhkan</span></div></div></section>

        <section class="border-b border-slate-200/80 py-14"><div class="mx-auto flex max-w-6xl flex-col gap-6 px-5 sm:flex-row sm:items-center sm:justify-between lg:px-8"><p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Partner perjalanan kami</p><div class="flex flex-wrap items-center gap-x-8 gap-y-4 text-sm font-extrabold text-slate-500">@forelse ($partnerAirlines as $airline)<span class="flex items-center gap-2"><span class="flex h-7 w-7 items-center justify-center rounded border border-slate-200 bg-white text-[9px] text-slate-700">{{ $airline->code }}</span>{{ $airline->name }}</span>@empty<span>Emirates</span><span>Garuda Indonesia</span><span>Singapore Airlines</span><span>Qatar Airways</span><span>ANA</span>@endforelse</div></div></section>

        <section id="destinasi" class="mx-auto max-w-6xl px-5 py-20 lg:px-8"><div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><p class="mb-3 text-xs font-extrabold uppercase tracking-[0.18em] text-cyan-700">Inspirasi perjalanan</p><h2 class="text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">Rute yang sedang banyak dipilih</h2></div><a href="{{ route('booking.create') }}" class="text-sm font-bold text-cyan-700 hover:text-cyan-900">Lihat semua penerbangan →</a></div><div class="grid gap-5 md:grid-cols-3">
            @if ($popularFlights->isNotEmpty())
                @foreach ($popularFlights as $flight)<article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"><div class="relative h-48 overflow-hidden bg-slate-800"><img src="{{ $routeImages[$flight->destinationAirport->code] ?? $routeImages['SIN'] }}" alt="{{ $flight->destinationAirport->city }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"><div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-transparent"></div><span class="absolute bottom-4 left-4 rounded-md bg-cyan-400 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-950">{{ $flight->stops === 0 ? 'Direct' : $flight->stops.' transit' }}</span><h3 class="absolute bottom-3 right-4 text-lg font-extrabold text-white">{{ $flight->destinationAirport->city }}</h3></div><div class="space-y-4 p-5"><div class="flex items-center justify-between gap-3 text-xs"><span class="font-bold text-slate-600">{{ $flight->originAirport->code }} → {{ $flight->destinationAirport->code }}</span><span class="font-bold text-cyan-700">{{ $flight->airline->name }}</span></div><div class="flex items-end justify-between border-t border-slate-100 pt-4"><div><span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Mulai dari</span><strong class="text-lg text-slate-950">Rp {{ number_format($flight->price, 0, ',', '.') }}</strong></div><a href="{{ route('booking.create', ['origin' => $flight->originAirport->code, 'destination' => $flight->destinationAirport->code]) }}" class="rounded-lg bg-slate-950 px-3 py-2 text-xs font-bold text-white transition hover:bg-cyan-600">Pilih rute</a></div></div></article>@endforeach
            @else
                @foreach ($fallbackRoutes as $route)<article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"><div class="relative h-48 overflow-hidden bg-slate-800"><img src="{{ $routeImages[$route['to_code']] }}" alt="{{ $route['to'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"><div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-transparent"></div><span class="absolute bottom-4 left-4 rounded-md bg-cyan-400 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-950">{{ $route['badge'] }}</span><h3 class="absolute bottom-3 right-4 text-lg font-extrabold text-white">{{ $route['to'] }}</h3></div><div class="space-y-4 p-5"><div class="flex items-center justify-between gap-3 text-xs"><span class="font-bold text-slate-600">{{ $route['from_code'] }} → {{ $route['to_code'] }}</span><span class="font-bold text-cyan-700">{{ $route['airline'] }}</span></div><div class="flex items-end justify-between border-t border-slate-100 pt-4"><div><span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Mulai dari</span><strong class="text-lg text-slate-950">Rp {{ number_format($route['price'], 0, ',', '.') }}</strong></div><a href="{{ route('booking.create', ['origin' => $route['from_code'], 'destination' => $route['to_code']]) }}" class="rounded-lg bg-slate-950 px-3 py-2 text-xs font-bold text-white transition hover:bg-cyan-600">Pilih rute</a></div></div></article>@endforeach
            @endif
        </div></section>

        <section id="cara-kerja" class="border-y border-slate-200 bg-white py-20"><div class="mx-auto max-w-6xl px-5 lg:px-8"><div class="max-w-xl"><p class="mb-3 text-xs font-extrabold uppercase tracking-[0.18em] text-cyan-700">Perjalanan tanpa ribet</p><h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Empat langkah, lalu berangkat.</h2></div><div class="mt-12 grid gap-8 md:grid-cols-4">@foreach ([['01', 'Cari penerbangan', 'Tentukan kota, tanggal, kelas, dan jumlah penumpang.'], ['02', 'Atur perjalanan', 'Pilih kursi, bagasi tambahan, makanan, dan asuransi.'], ['03', 'Bayar aman', 'Selesaikan pembayaran melalui kanal Midtrans pilihanmu.'], ['04', 'Terima e-ticket', 'E-ticket PDF dan kode verifikasi siap digunakan.']] as $step)<div class="relative border-l-2 border-cyan-200 pl-5"><span class="text-sm font-extrabold text-cyan-600">{{ $step[0] }}</span><h3 class="mt-3 font-extrabold text-slate-900">{{ $step[1] }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $step[2] }}</p></div>@endforeach</div></div></section>

        <section class="mx-auto grid max-w-6xl gap-12 px-5 py-20 lg:grid-cols-[.9fr_1.1fr] lg:px-8"><div><p class="mb-3 text-xs font-extrabold uppercase tracking-[0.18em] text-cyan-700">Cerita perjalanan</p><h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Dibuat untuk membuat keberangkatan terasa ringan.</h2><p class="mt-5 text-sm leading-7 text-slate-500">Dari pemilihan kursi sampai e-ticket, semua detail perjalanan tersimpan rapi dalam satu kode booking.</p></div><div class="grid gap-4 sm:grid-cols-2"><blockquote class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-sm leading-6 text-slate-600">“Prosesnya jelas, pilihan kursinya enak dilihat, dan e-ticket langsung tersedia setelah pembayaran.”</p><footer class="mt-5 text-xs font-extrabold text-slate-900">Nadia R. <span class="font-medium text-slate-400">· Jakarta</span></footer></blockquote><blockquote class="rounded-2xl border border-slate-200 bg-slate-950 p-6 text-white shadow-sm"><p class="text-sm leading-6 text-slate-300">“Saya bisa membandingkan rute dan harga tanpa berpindah halaman. Detail booking-nya juga lengkap.”</p><footer class="mt-5 text-xs font-extrabold">Raka P. <span class="font-medium text-slate-500">· Bandung</span></footer></blockquote></div></section>

        <section id="faq" class="bg-slate-950 py-20 text-white"><div class="mx-auto max-w-3xl px-5 lg:px-8"><div class="text-center"><p class="mb-3 text-xs font-extrabold uppercase tracking-[0.18em] text-cyan-300">Bantuan</p><h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Pertanyaan yang sering muncul</h2></div><div class="mt-10 divide-y divide-white/10 rounded-2xl border border-white/10 bg-white/[.04] px-5">@foreach ([['Bagaimana cara mendapatkan e-ticket?', 'Setelah pembayaran berhasil dikonfirmasi Midtrans, e-ticket dapat dibuka, dicetak, atau diunduh dari dashboard.'], ['Apakah saya dapat memilih kursi?', 'Ya. Denah kursi menampilkan kursi yang tersedia dan terisi untuk penerbangan yang dipilih.'], ['Apakah voucher dapat digunakan berulang?', 'Setiap voucher memiliki batas pemakaian dan tanggal berlaku. Sistem akan memeriksa keduanya saat booking dibuat.'], ['Metode pembayaran apa yang tersedia?', 'Midtrans menyediakan QRIS, transfer bank, kartu, dan kanal pembayaran lain sesuai konfigurasi akun.']] as $faq)<details class="group py-5"><summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold">{{ $faq[0] }}<span class="text-xl font-normal text-cyan-300 transition group-open:rotate-45">+</span></summary><p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">{{ $faq[1] }}</p></details>@endforeach</div></div></section>
    </main>

    <footer class="bg-[#07111f] py-12 text-slate-400"><div class="mx-auto grid max-w-6xl gap-10 px-5 sm:grid-cols-2 lg:grid-cols-4 lg:px-8"><div><a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-extrabold text-white"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-400 text-slate-950">✈</span>AirTicket Intl</a><p class="mt-4 max-w-xs text-sm leading-6">Pemesanan penerbangan internasional yang rapi, aman, dan siap menemani perjalananmu.</p></div><div><h3 class="font-bold text-white">Navigasi</h3><ul class="mt-4 space-y-3 text-sm"><li><a href="#destinasi" class="transition hover:text-white">Destinasi populer</a></li><li><a href="#cara-kerja" class="transition hover:text-white">Cara kerja</a></li><li><a href="{{ route('booking.create') }}" class="transition hover:text-white">Cari tiket</a></li></ul></div><div><h3 class="font-bold text-white">Pembayaran</h3><p class="mt-4 text-sm leading-6">Didukung Midtrans untuk QRIS, transfer bank, dan kartu kredit.</p><div class="mt-4 flex flex-wrap gap-2 text-[10px] font-bold"><span class="rounded border border-white/10 px-2 py-1">QRIS</span><span class="rounded border border-white/10 px-2 py-1">BCA</span><span class="rounded border border-white/10 px-2 py-1">MANDIRI</span><span class="rounded border border-white/10 px-2 py-1">VISA</span></div></div><div><h3 class="font-bold text-white">Bantuan</h3><p class="mt-4 text-sm leading-6">support@airticket-intl.com<br>Jakarta, Indonesia</p><div class="mt-4 flex gap-4 text-sm font-bold"><a href="#faq" class="transition hover:text-white">FAQ</a><a href="https://wa.me/" target="_blank" rel="noopener" class="transition hover:text-white">WhatsApp</a></div></div></div><div class="mx-auto mt-10 max-w-6xl border-t border-white/10 px-5 pt-6 text-xs text-slate-500 lg:px-8">© {{ date('Y') }} AirTicket Intl. Built for modern travel.</div></footer>
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        function updateThemeButton() {
            const isDark = document.documentElement.classList.contains('theme-dark');
            themeIcon.textContent = isDark ? '☀' : '☾';
            themeToggle.setAttribute('aria-label', isDark ? 'Aktifkan tema terang' : 'Aktifkan tema gelap');
        }

        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('theme-dark');
            localStorage.setItem('airticket-theme', isDark ? 'dark' : 'light');
            updateThemeButton();
        });

        updateThemeButton();
    </script>
</body>
</html>
