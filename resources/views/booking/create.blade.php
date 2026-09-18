<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Tiket Pesawat Internasional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    @include('layouts.navbar')

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <form method="GET" action="{{ route('booking.create') }}" class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Cari Penerbangan</h5>
                            <a href="{{ route('booking.create') }}" class="small text-decoration-none">Reset filter</a>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold" for="origin">Asal</label>
                                <input id="origin" name="origin" class="form-control" value="{{ request('origin') }}" placeholder="Kota / kode">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold" for="destination">Tujuan</label>
                                <input id="destination" name="destination" class="form-control" value="{{ request('destination') }}" placeholder="Kota / kode">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold" for="date">Tanggal</label>
                                <input id="date" type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold" for="max_price">Harga maksimal</label>
                                <input id="max_price" type="number" name="max_price" min="0" step="500000" class="form-control" value="{{ request('max_price', 25000000) }}">
                            </div>
                            <div class="col-md-6">
                                <span class="form-label small fw-bold d-block">Maskapai</span>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($airlines as $airline)
                                        <label class="small text-muted">
                                            <input type="checkbox" name="airline[]" value="{{ $airline->code }}" @checked(in_array($airline->code, (array) request('airline', []), true))>
                                            {{ $airline->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span class="form-label small fw-bold d-block">Penerbangan</span>
                                <div class="d-flex gap-3">
                                    <label class="small text-muted"><input type="checkbox" name="transit[]" value="direct" @checked(in_array('direct', (array) request('transit', []), true))> Langsung</label>
                                    <label class="small text-muted"><input type="checkbox" name="transit[]" value="1_transit" @checked(in_array('1_transit', (array) request('transit', []), true))> 1 Transit</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Tampilkan Penerbangan</button>
                    </div>
                </form>

                <div class="mb-4">
                    <h6 class="fw-bold text-secondary mb-3">Hasil Penerbangan ({{ $flights->count() }})</h6>
                    @forelse ($flights as $flight)
                        <div class="flight-card card border-0 shadow-sm mb-2" data-flight-card="{{ $flight->id }}">
                            <div class="card-body d-flex flex-wrap align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2" style="min-width: 180px;">
                                    <img src="{{ $flight->airline->logoUrl() }}" onerror="this.onerror=null;this.src='{{ $flight->airline->fallbackLogoUrl() }}';" alt="{{ $flight->airline->name }}" width="40" height="40" class="rounded border bg-white p-1 object-contain" loading="lazy">
                                    <div><strong>{{ $flight->airline->name }}</strong><small class="d-block text-muted">{{ $flight->flight_number }}</small></div>
                                </div>
                                <div class="d-flex align-items-center gap-3 text-center flex-grow-1">
                                    <div><strong>{{ $flight->departure_time->format('H:i') }}</strong><small class="d-block text-muted">{{ $flight->originAirport->code }}</small></div>
                                    <div class="text-muted small">{{ $flight->stops === 0 ? 'Direct' : $flight->stops.' Transit' }}<br><span>────────</span></div>
                                    <div><strong>{{ $flight->arrival_time->format('H:i') }}</strong><small class="d-block text-muted">{{ $flight->destinationAirport->code }}</small></div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small">Mulai dari</div>
                                    <strong class="text-primary">Rp {{ number_format($flight->price, 0, ',', '.') }}</strong>
                                    <button type="button" class="choose-flight btn btn-sm btn-outline-primary d-block mt-1" data-flight-id="{{ $flight->id }}">Pilih</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            <strong>Tidak ada penerbangan yang cocok.</strong>
                            <div class="small mt-1">Coba ubah tanggal, naikkan harga maksimal, atau kurangi filter maskapai/transit.</div>
                        </div>
                    @endforelse
                </div>

                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">✈️ Form Pemesanan Tiket Internasional</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('booking.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-bold">Pilih Rute Penerbangan</label>
                                <select name="flight_id" class="form-select form-select-lg" required>
                                    <option value="" selected disabled>-- Pilih Penerbangan Internasional --</option>
                                    @foreach ($flights as $flight)
                                        <option value="{{ $flight->id }}"
                                            data-total-seats="{{ $flight->total_seats }}"
                                            data-booked-seats="{{ implode(',', $bookedSeatsByFlight[$flight->id] ?? []) }}"
                                                    data-price="{{ $flight->price }}"
                                            @selected(old('flight_id') == $flight->id)>
                                            {{ $flight->airline->name }} ({{ $flight->flight_number }}) :
                                            {{ $flight->originAirport->city }} ({{ $flight->originAirport->code }}) &rarr;
                                            {{ $flight->destinationAirport->city }} ({{ $flight->destinationAirport->code }})
                                            — Rp {{ number_format($flight->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3 text-secondary">🎫 Kelas Penerbangan</h6>

                            <div class="row g-3 mb-4">
                                @foreach (['Economy' => 'Bagasi 20 kg', 'Business' => 'Bagasi 35 kg + prioritas', 'First Class' => 'Layanan premium'] as $class => $description)
                                    <div class="col-md-4">
                                        <label class="border rounded p-3 d-block h-100">
                                            <input type="radio" name="seat_class" value="{{ $class }}" @checked(old('seat_class', 'Economy') === $class)>
                                            <span class="fw-bold ms-1">{{ $class }}</span>
                                            <small class="text-muted d-block mt-1">{{ $description }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <h6 class="fw-bold mb-3 text-secondary">💺 Pilih Nomor Kursi</h6>
                            <input type="hidden" name="passengers[0][seat_number]" id="selected_seat" value="{{ old('passengers.0.seat_number') }}" required>
                            <div id="seat-layout" class="border rounded bg-light p-3 mb-4 text-center">
                                <p class="text-muted mb-0">Pilih penerbangan untuk melihat denah kursi.</p>
                            </div>
                            <div class="d-flex justify-content-center gap-3 small text-muted mb-4">
                                <span><span class="badge bg-white border">A</span> Tersedia</span>
                                <span><span class="badge bg-secondary">A</span> Terisi</span>
                                <span><span class="badge bg-primary">A</span> Dipilih</span>
                            </div>

                            <h6 class="fw-bold mb-3 text-secondary">📄 Data Penumpang (Sesuai Paspor)</h6>

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="passengers[0][full_name]" class="form-control" placeholder="Contoh: NAUFAL ARDRA ANABIL" value="{{ auth()->check() ? auth()->user()->name : '' }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Paspor</label>
                                    <input type="text" name="passengers[0][passport_number]" class="form-control" placeholder="A12345678" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Masa Berlaku Paspor</label>
                                    <input type="date" name="passengers[0][passport_expiry]" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Kewarganegaraan</label>
                                <input type="text" name="passengers[0][nationality]" class="form-control" value="Indonesia" required>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 text-secondary">🧳 Layanan Tambahan</h6>
                                <div class="list-group">
                                    <label class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><strong>Ekstra bagasi +10 kg</strong><small class="d-block text-muted">+Rp 150.000</small></span>
                                        <input type="checkbox" name="extra_baggage" value="1" @checked(old('extra_baggage'))>
                                    </label>
                                    <label class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><strong>In-flight meal</strong><small class="d-block text-muted">+Rp 75.000</small></span>
                                        <input type="checkbox" name="has_meal" value="1" @checked(old('has_meal'))>
                                    </label>
                                    <label class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><strong>Asuransi perjalanan</strong><small class="d-block text-muted">+Rp 50.000</small></span>
                                        <input type="checkbox" name="has_insurance" value="1" @checked(old('has_insurance'))>
                                    </label>
                                </div>
                            </div>

                            <div class="border rounded p-3 mb-4 bg-light">
                                <h6 class="fw-bold">💡 Punya kode promo?</h6>
                                <div class="input-group">
                                    <input type="text" name="voucher_code" id="voucher_code" class="form-control text-uppercase" value="{{ old('voucher_code') }}" placeholder="Contoh: TIKETMURAH">
                                </div>
                                <small class="text-muted">Voucher akan diverifikasi dan dihitung saat pemesanan diproses.</small>
                                @error('voucher_code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="border rounded p-3 mb-4">
                                <h6 class="fw-bold">Rincian Pembayaran</h6>
                                <div class="d-flex justify-content-between small text-muted"><span>Tiket</span><span id="base-price-display">Rp 0</span></div>
                                <div class="d-flex justify-content-between small text-muted"><span>Layanan tambahan</span><span id="addons-price-display">Rp 0</span></div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold"><span>Total sebelum voucher</span><span id="total-price-display" class="text-primary">Rp 0</span></div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">Lanjutkan ke Pembayaran &rarr;</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const flightSelect = document.querySelector('select[name="flight_id"]');
        const seatLayout = document.getElementById('seat-layout');
        const selectedSeat = document.getElementById('selected_seat');
        const priceFormatter = new Intl.NumberFormat('id-ID');
        const totalPriceDisplay = document.getElementById('total-price-display');
        const basePriceDisplay = document.getElementById('base-price-display');
        const addonsPriceDisplay = document.getElementById('addons-price-display');

        document.querySelectorAll('.choose-flight').forEach((button) => {
            button.addEventListener('click', () => {
                flightSelect.value = button.dataset.flightId;
                flightSelect.dispatchEvent(new Event('change'));
                flightSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

        function chooseSeat(button, seatCode) {
            document.querySelectorAll('.seat-button').forEach((seatButton) => {
                if (!seatButton.disabled) {
                    seatButton.classList.remove('bg-primary', 'text-white', 'border-primary');
                    seatButton.classList.add('bg-white', 'text-dark', 'border-secondary');
                }
            });

            button.classList.remove('bg-white', 'text-dark', 'border-secondary');
            button.classList.add('bg-primary', 'text-white', 'border-primary');
            selectedSeat.value = seatCode;
        }

        function renderSeats() {
            const option = flightSelect.options[flightSelect.selectedIndex];
            seatLayout.replaceChildren();
            selectedSeat.value = '';

            if (!option || !option.value) {
                seatLayout.innerHTML = '<p class="text-muted mb-0">Pilih penerbangan untuk melihat denah kursi.</p>';
                updatePrice();
                return;
            }

            const totalSeats = Number(option.dataset.totalSeats);
            const bookedSeats = new Set((option.dataset.bookedSeats || '').split(',').filter(Boolean));
            const header = document.createElement('div');
            header.className = 'row g-2 mb-2 fw-bold text-muted';
            header.innerHTML = '<div class="col">A</div><div class="col">B</div><div class="col">C</div><div class="col">D</div>';
            seatLayout.append(header);

            for (let row = 1; row <= Math.ceil(totalSeats / 4); row++) {
                const rowElement = document.createElement('div');
                rowElement.className = 'row g-2 mb-2';

                ['A', 'B', 'C', 'D'].forEach((column, columnIndex) => {
                    const seatNumber = ((row - 1) * 4) + columnIndex + 1;
                    if (seatNumber > totalSeats) {
                        rowElement.insertAdjacentHTML('beforeend', '<div class="col"></div>');
                        return;
                    }

                    const seatCode = `${row}${column}`;
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = seatCode;
                    button.className = 'seat-button col btn btn-sm bg-white text-dark border-secondary';
                    button.disabled = bookedSeats.has(seatCode);
                    if (button.disabled) {
                        button.classList.replace('bg-white', 'bg-secondary');
                        button.classList.replace('text-dark', 'text-white');
                    } else {
                        button.addEventListener('click', () => chooseSeat(button, seatCode));
                    }
                    rowElement.append(button);
                });

                seatLayout.append(rowElement);
            }

            updatePrice();
        }

        function updatePrice() {
            const option = flightSelect.options[flightSelect.selectedIndex];
            const basePrice = option && option.value ? Number(option.dataset.price) : 0;
            const addonsPrice = (document.querySelector('[name="extra_baggage"]')?.checked ? 150000 : 0)
                + (document.querySelector('[name="has_meal"]')?.checked ? 75000 : 0)
                + (document.querySelector('[name="has_insurance"]')?.checked ? 50000 : 0);
            basePriceDisplay.textContent = `Rp ${priceFormatter.format(basePrice)}`;
            addonsPriceDisplay.textContent = `Rp ${priceFormatter.format(addonsPrice)}`;
            totalPriceDisplay.textContent = `Rp ${priceFormatter.format(basePrice + addonsPrice)}`;
        }

        flightSelect.addEventListener('change', renderSeats);
        document.querySelectorAll('[name="extra_baggage"], [name="has_meal"], [name="has_insurance"]').forEach((checkbox) => {
            checkbox.addEventListener('change', updatePrice);
        });
        renderSeats();

        const oldSeat = @json(old('passengers.0.seat_number'));
        if (oldSeat) {
            const oldSeatButton = [...document.querySelectorAll('.seat-button')].find((button) => button.textContent === oldSeat);
            if (oldSeatButton && !oldSeatButton.disabled) {
                chooseSeat(oldSeatButton, oldSeat);
            }
        }
    </script>
</body>
</html>