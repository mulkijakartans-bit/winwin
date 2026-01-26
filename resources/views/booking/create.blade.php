@extends('layouts.app')

@section('title', 'Buat Booking - WINWIN Makeup')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Buat Booking</h4>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>{{ $muaProfile->name }}</h5>
                    <p><strong>Paket:</strong> {{ $package->name }}</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                    <p><strong>Durasi:</strong> {{ $package->duration }} menit</p>
                </div>

                <form method="POST" action="{{ route('booking.store') }}">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">

                    <div class="mb-3">
                        <label for="booking_date" class="form-label">Tanggal Booking</label>
                        <input type="date" class="form-control @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('booking_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="booking_time" class="form-label">Waktu Booking</label>
                        <select class="form-control @error('booking_time') is-invalid @enderror" id="booking_time" name="booking_time" required>
                            <option value="">Pilih Waktu (Pilih tanggal terlebih dahulu)</option>
                        </select>
                        @error('booking_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Setiap booking membutuhkan waktu 5 jam
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="event_location" class="form-label">Lokasi Acara</label>
                        <select class="form-control @error('event_location') is-invalid @enderror" id="event_location" name="event_location" required>
                            <option value="">Pilih Daerah</option>
                            <option value="Tegal Kota" {{ old('event_location') == 'Tegal Kota' ? 'selected' : '' }}>Tegal Kota</option>
                            <option value="Kabupaten Tegal" {{ old('event_location') == 'Kabupaten Tegal' ? 'selected' : '' }}>Kabupaten Tegal</option>
                            <option value="Brebes Kota" {{ old('event_location') == 'Brebes Kota' ? 'selected' : '' }}>Brebes Kota</option>
                            <option value="Brebes Kabupaten" {{ old('event_location') == 'Brebes Kabupaten' ? 'selected' : '' }}>Brebes Kabupaten</option>
                            <option value="Pemalang" {{ old('event_location') == 'Pemalang' ? 'selected' : '' }}>Pemalang</option>
                        </select>
                        @error('event_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan Khusus</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <strong>Total Pembayaran:</strong> Rp {{ number_format($package->price, 0, ',', '.') }}
                    </div>

                    <button type="submit" class="btn btn-primary">Buat Booking</button>
                    <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Load available times for selected date
    async function loadAvailableTimes(dateStr) {
        const timeSelect = document.getElementById('booking_time');
        if (!timeSelect) return;
        
        timeSelect.innerHTML = '<option value="">Memuat waktu tersedia...</option>';
        timeSelect.disabled = true;

        try {
            const response = await fetch(`/api/booked-times/${dateStr}`);
            const data = await response.json();
            const bookedTimes = data.booked_times || [];

            // Generate all possible time slots (every hour from 06:00 to 20:00)
            const allTimeSlots = [];
            for (let hour = 6; hour <= 20; hour++) {
                const timeStr = String(hour).padStart(2, '0') + ':00';
                allTimeSlots.push(timeStr);
            }

            // Filter out times that overlap with existing bookings
            // Each booking takes 5 hours (300 minutes)
            const availableTimes = allTimeSlots.filter(slotTime => {
                const slotHour = parseInt(slotTime.split(':')[0]);
                const slotEndHour = slotHour + 5; // 5 hours duration

                // Check if this slot overlaps with any existing booking
                for (const bookedTime of bookedTimes) {
                    const bookedHour = parseInt(bookedTime.split(':')[0]);
                    const bookedEndHour = bookedHour + 5; // 5 hours duration

                    // Check for overlap
                    // Slot overlaps if: slot starts before booked ends AND slot ends after booked starts
                    if (slotHour < bookedEndHour && slotEndHour > bookedHour) {
                        return false; // This slot is not available
                    }
                }
                return true; // This slot is available
            });

            // Populate select with available times
            timeSelect.innerHTML = '<option value="">Pilih Waktu</option>';
            if (availableTimes.length === 0) {
                timeSelect.innerHTML = '<option value="">Tidak ada waktu tersedia</option>';
            } else {
                availableTimes.forEach(time => {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = time;
                    if (time === '{{ old("booking_time") }}') {
                        option.selected = true;
                    }
                    timeSelect.appendChild(option);
                });
            }
            timeSelect.disabled = false;
        } catch (error) {
            console.error('Error loading available times:', error);
            timeSelect.innerHTML = '<option value="">Error memuat waktu</option>';
            timeSelect.disabled = false;
        }
    }

    // Listen for date input changes
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('booking_date');
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                if (this.value) {
                    loadAvailableTimes(this.value);
                } else {
                    const timeSelect = document.getElementById('booking_time');
                    timeSelect.innerHTML = '<option value="">Pilih Tanggal terlebih dahulu</option>';
                    timeSelect.disabled = true;
                }
            });

            // Load times if date is already selected (e.g., from old input)
            if (dateInput.value) {
                loadAvailableTimes(dateInput.value);
            }
        }
    });
</script>
@endpush
@endsection
