@extends('layouts.app')

@section('title', 'Buat Booking - WINWIN Makeup')
 
@push('styles')
<style>
    /* Custom Calendar Picker Styles (Synced from Customer Dashboard) */
    .date-picker-wrapper {
        position: relative;
    }
 
    .calendar-container {
        margin-top: 10px;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
 
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
 
    .calendar-nav-btn {
        background: transparent;
        border: 1px solid #e0e0e0;
        padding: 6px 10px;
        cursor: pointer;
        font-size: 0.85rem;
        color: #1a1a1a;
        transition: all 0.3s ease;
    }
 
    .calendar-nav-btn:hover {
        background: #fafafa;
        border-color: #1a1a1a;
    }
 
    .calendar-month-year {
        font-size: 0.9rem;
        font-weight: 500;
        color: #1a1a1a;
    }
 
    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
        margin-bottom: 8px;
        background: #f8f9fa;
        padding: 8px 4px;
        border-radius: 4px;
        border: 1px solid #e0e0e0;
    }
 
    .calendar-weekday {
        text-align: center;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1a1a;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 8px 4px;
        background: white;
        border-radius: 3px;
        border: 1px solid #ddd;
    }
 
    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
    }
 
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: #1a1a1a;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        position: relative;
    }
 
    .calendar-day:hover:not(.disabled):not(.booked) {
        background: #fafafa;
        border-color: #1a1a1a;
    }
 
    .calendar-day.disabled {
        color: #ccc;
        cursor: not-allowed;
        background: #f9f9f9;
    }
 
    .calendar-day.other-month {
        color: #ddd;
    }
 
    .calendar-day.available {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #0369a1;
    }
 
    .calendar-day.booked-1 {
        background: #fef9c3;
        color: #854d0e;
        border-color: #eab308;
    }
 
    .calendar-day.booked-2 {
        background: #ffedd5;
        color: #9a3412;
        border-color: #f97316;
    }
 
    .calendar-day.booked-3 {
        background: #ef4444;
        color: #ffffff;
        cursor: not-allowed;
        position: relative;
    }
 
 
    .calendar-day.selected {
        background: #1a1a1a;
        color: white;
        border-color: #1a1a1a;
    }
 
    .calendar-day.today {
        border: 2px solid #1a1a1a;
        font-weight: 600;
    }
 
    .calendar-legend {
        display: flex;
        gap: 12px;
        margin: 12px 0;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 4px;
        font-size: 0.75rem;
        color: #333;
        flex-wrap: wrap;
        justify-content: center;
        border: 1px solid #e0e0e0;
    }
 
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        background: white;
        border-radius: 3px;
        border: 1px solid #ddd;
    }
 
    .legend-color {
        width: 18px;
        height: 18px;
        border: 1px solid #e0e0e0;
        border-radius: 3px;
        flex-shrink: 0;
    }
    
    .legend-item span {
        font-weight: 500;
        white-space: nowrap;
    }
 
    .legend-color.booked-1 {
        background: #fef9c3;
        border-color: #eab308;
    }
 
    .legend-color.booked-2 {
        background: #ffedd5;
        border-color: #f97316;
    }
 
    .legend-color.booked-3 {
        background: #ef4444;
        border-color: #ef4444;
    }
 
    .legend-color.available {
        background: #e0f2fe;
        border-color: #0369a1;
    }

    #date-display-btn {
        text-align: left;
        cursor: pointer;
        background: white;
        border: 1px solid #ced4da;
        width: 100%;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    #date-display-btn:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
</style>
@endpush

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
                        <div class="date-picker-wrapper">
                            <input type="date" class="form-control @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required style="display: none;">
                            <button type="button" id="date-display-btn" onclick="toggleCalendar(); return false;">
                                {{ old('booking_date') ? date('j F Y', strtotime(old('booking_date'))) : 'Pilih Tanggal' }}
                            </button>
                            <div id="calendar-picker" class="calendar-container" style="display: none;"></div>
                        </div>
                        @error('booking_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
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
                            Setiap pemesanan berlaku untuk 5 jam.<br>
                            Last order di pukul 17.00 WIB dikarenakan kami close pukul 22.00 WIB.
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
                        <label for="notes" class="form-label">Detail Alamat</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="8" placeholder="Contoh: Nama Jalan, Gang, No Rumah, RT/RW, Kelurahan, Kecamatan, Patokan">{{ old('notes') ?? "Nama Jalan  :\nNama Gang :\nNomor Rumah :\nRT/RW :\nKelurahan :\nKecamatan :\nPatokan (Opsional) :" }}</textarea>
                        <small class="form-text text-muted">Silakan isi dengan detail alamat lokasi acara secara lengkap.</small>
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
    let bookedDates = {}; // Object with key: date, value: count
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedDate = null;
 
    // Fetch booked dates from API
    async function loadBookedDates() {
        try {
            const response = await fetch('/api/booked-dates');
            const data = await response.json();
            bookedDates = data.booked_dates || {};
            if (document.getElementById('calendar-picker').style.display !== 'none') {
                renderCalendar();
            }
        } catch (error) {
            console.error('Error loading booked dates:', error);
        }
    }
 
    function toggleCalendar() {
        const calendar = document.getElementById('calendar-picker');
        if (calendar.style.display === 'none') {
            calendar.style.display = 'block';
            loadBookedDates();
            renderCalendar();
        } else {
            calendar.style.display = 'none';
        }
    }
 
    function renderCalendar() {
        const calendar = document.getElementById('calendar-picker');
        const today = new Date();
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();
 
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
 
        let html = `
            <div class="calendar-header">
                <button type="button" class="calendar-nav-btn" onclick="previousMonth(event)">‹</button>
                <div class="calendar-month-year">${monthNames[currentMonth]} ${currentYear}</div>
                <button type="button" class="calendar-nav-btn" onclick="nextMonth(event)">›</button>
            </div>
            <div class="calendar-weekdays">
                ${dayNames.map(day => `<div class="calendar-weekday">${day}</div>`).join('')}
            </div>
            <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-color available"></div>
                    <span>Tersedia</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color booked-1"></div>
                    <span>1 Booking</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color booked-2"></div>
                    <span>2 Booking</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color booked-3"></div>
                    <span>Penuh (3+)</span>
                </div>
            </div>
            <div class="calendar-days">
        `;
 
        for (let i = 0; i < startingDayOfWeek; i++) {
            html += `<div class="calendar-day other-month"></div>`;
        }
 
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dateObj = new Date(currentYear, currentMonth, day);
            const isToday = dateObj.toDateString() === today.toDateString();
            const isPast = dateObj < today && !isToday;
            const bookingCount = bookedDates[dateStr] || 0;
            const isSelected = selectedDate === dateStr;
 
            let classes = 'calendar-day';
            if (isPast) classes += ' disabled';
            if (bookingCount === 0 && !isPast) classes += ' available';
            if (bookingCount === 1) classes += ' booked-1';
            if (bookingCount === 2) classes += ' booked-2';
            if (bookingCount >= 3) classes += ' booked-3';
            if (isSelected) classes += ' selected';
            if (isToday) classes += ' today';
 
            const onClick = (isPast || bookingCount >= 3) ? '' : `onclick="selectDate('${dateStr}')"`;
 
            html += `<div class="${classes}" ${onClick}>${day}</div>`;
        }
 
        html += `</div>`;
        calendar.innerHTML = html;
    }
 
    function previousMonth(event) {
        event.stopPropagation();
        event.preventDefault();
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    }
 
    function nextMonth(event) {
        event.stopPropagation();
        event.preventDefault();
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    }
 
    function selectDate(dateStr) {
        selectedDate = dateStr;
        document.getElementById('booking_date').value = dateStr;
        const dateObj = new Date(dateStr);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('date-display-btn').textContent = dateObj.toLocaleDateString('id-ID', options);
        document.getElementById('calendar-picker').style.display = 'none';
        renderCalendar();
        loadAvailableTimes(dateStr);
    }
 
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
 
            const allTimeSlots = [];
            for (let hour = 5; hour <= 17; hour++) {
                const timeStr = String(hour).padStart(2, '0') + ':00';
                allTimeSlots.push(timeStr);
            }
 
            timeSelect.innerHTML = '<option value="">Pilih Waktu</option>';
            
            allTimeSlots.forEach(time => {
                const hour = parseInt(time.split(':')[0]);
                const endTimeStr = String(hour + 5).padStart(2, '0') + ':00';
                
                const option = document.createElement('option');
                option.value = time;
                option.textContent = `${time} WIB - Selesai ${endTimeStr} WIB`;
                
                const slotHour = hour;
                const slotEndHour = slotHour + 6;
                let isBooked = false;
 
                for (const bookedTime of bookedTimes) {
                    const bookedHour = parseInt(bookedTime.split(':')[0]);
                    const bookedEndHour = bookedHour + 6;
                    if (slotHour < bookedEndHour && slotEndHour > bookedHour) {
                        isBooked = true;
                        break;
                    }
                }
 
                if (isBooked) {
                    option.textContent = `${time} WIB - Selesai ${endTimeStr} WIB (Sudah di-booking)`;
                    option.disabled = true;
                    option.style.color = '#ef4444'; 
                } else {
                    option.textContent = `${time} WIB - Selesai ${endTimeStr} WIB`;
                }
 
                if (time === '{{ old("booking_time") }}' || time === '{{ $booking->booking_time ?? "" }}') {
                    option.selected = true;
                }
                timeSelect.appendChild(option);
            });
            timeSelect.disabled = false;
        } catch (error) {
            console.error('Error loading available times:', error);
            timeSelect.innerHTML = '<option value="">Error memuat waktu</option>';
            timeSelect.disabled = false;
        }
    }
 
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('booking_date');
        if (dateInput && dateInput.value) {
            selectedDate = dateInput.value;
            const dateObj = new Date(selectedDate);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('date-display-btn').textContent = dateObj.toLocaleDateString('id-ID', options);
            currentMonth = dateObj.getMonth();
            currentYear = dateObj.getFullYear();
            loadAvailableTimes(selectedDate);
        }
    });

    // Close calendar when clicking outside
    document.addEventListener('click', function(event) {
        const calendar = document.getElementById('calendar-picker');
        const datePickerWrapper = document.querySelector('.date-picker-wrapper');
        
        if (calendar && datePickerWrapper && calendar.style.display !== 'none') {
            const clickedInside = datePickerWrapper.contains(event.target) || 
                                 event.target.closest('.calendar-container') ||
                                 event.target.closest('.calendar-nav-btn') ||
                                 event.target.closest('.calendar-day') ||
                                 event.target.closest('.calendar-header');
            
            if (!clickedInside) {
                calendar.style.display = 'none';
            }
        }
    });
</script>
@endpush
@endsection
