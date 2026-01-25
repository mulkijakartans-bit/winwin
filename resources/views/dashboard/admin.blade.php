@extends('layouts.app')

@section('title', 'Dashboard Admin - WINWIN Makeup')

@push('styles')
<style>
    .dashboard-page {
        position: relative;
        padding: 100px 0 60px;
        background: #fafafa;
        min-height: 100vh;
    }

    .dashboard-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=1920&q=80');
        background-size: cover;
        background-position: center;
        z-index: 0;
    }

    .dashboard-background::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .dashboard-page .container {
        position: relative;
        z-index: 1;
    }

    .dashboard-header {
        margin-bottom: 60px;
    }

    .dashboard-title {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 300;
        letter-spacing: -1px;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .dashboard-subtitle {
        font-size: 1rem;
        color: #1a1a1a;
        font-weight: 300;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .stat-card {
        background: white;
        padding: 40px 30px;
        border: none;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover {
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 3rem;
        font-weight: 300;
        color: #1a1a1a;
        line-height: 1;
        letter-spacing: -2px;
    }

    .stat-value.warning {
        color: #f59e0b;
    }

    /* Tab Styles */
    .admin-tabs {
        background: white;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .tab-nav {
        display: flex;
        flex-wrap: wrap;
        border-bottom: 1px solid #e0e0e0;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .tab-item {
        flex: 1;
        min-width: 150px;
    }

    .tab-button {
        width: 100%;
        padding: 20px;
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        color: #666;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .tab-button:hover {
        color: #1a1a1a;
        background: #fafafa;
    }

    .tab-button.active {
        color: #1a1a1a;
        border-bottom-color: #1a1a1a;
        background: #fafafa;
    }

    .tab-content {
        display: none;
        padding: 40px;
        min-height: 400px;
    }

    .tab-content.active {
        display: block;
    }

    .tab-content.loading {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    /* Table Styles */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table thead {
        border-bottom: 1px solid #e0e0e0;
    }

    .admin-table th {
        padding: 15px;
        text-align: left;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
        font-weight: 500;
    }

    .admin-table td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
        font-size: 0.9rem;
    }

    .admin-table tbody tr:hover {
        background: #fafafa;
    }

    .admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid;
    }

    .status-badge.pending {
        color: #f59e0b;
        border-color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
    }

    .status-badge.confirmed {
        color: #3b82f6;
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
    }

    .status-badge.completed {
        color: #10b981;
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.1);
    }

    .status-badge.verified {
        color: #10b981;
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.1);
    }

    .status-badge.rejected {
        color: #ef4444;
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
    }

    .btn-action {
        padding: 8px 16px;
        background: transparent;
        color: #1a1a1a;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid #1a1a1a;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-action:hover {
        background: #1a1a1a;
        color: white;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #333;
        margin-bottom: 10px;
        display: block;
    }

    .form-input {
        width: 100%;
        padding: 12px 0;
        border: none;
        border-bottom: 1px solid #e0e0e0;
        background: transparent;
        font-size: 1rem;
        color: #1a1a1a;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-bottom-color: #1a1a1a;
    }

    select.form-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0 center;
        padding-right: 30px;
    }

    .btn-submit {
        padding: 12px 30px;
        background: #1a1a1a;
        color: white;
        border: 1px solid #1a1a1a;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: transparent;
        color: #1a1a1a;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .form-error {
        font-size: 0.85rem;
        color: #ef4444;
        margin-top: 8px;
        display: block;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 40px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }

    .modal-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e0e0e0;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 400;
        color: #1a1a1a;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #999;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.3s ease;
    }

    .modal-close:hover {
        color: #1a1a1a;
    }

    .modal-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .btn-modal {
        padding: 12px 30px;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid #1a1a1a;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-modal-primary {
        background: #1a1a1a;
        color: white;
    }

    .btn-modal-primary:hover {
        background: transparent;
        color: #1a1a1a;
    }

    .btn-modal-secondary {
        background: transparent;
        color: #1a1a1a;
    }

    .btn-modal-secondary:hover {
        background: #1a1a1a;
        color: white;
    }

    .modal form .form-group {
        margin-bottom: 25px;
    }

    .modal form .form-label {
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #333;
        margin-bottom: 10px;
        display: block;
    }

    .modal form .form-input,
    .modal form .form-select,
    .modal form textarea {
        width: 100%;
        padding: 12px 0;
        border: none;
        border-bottom: 1px solid #e0e0e0;
        background: transparent;
        font-size: 1rem;
        color: #1a1a1a;
        transition: all 0.3s ease;
    }

    .modal form .form-input:focus,
    .modal form .form-select:focus,
    .modal form textarea:focus {
        outline: none;
        border-bottom-color: #1a1a1a;
    }

    .modal form textarea {
        resize: vertical;
        min-height: 80px;
    }

    .modal form .form-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0 center;
        padding-right: 30px;
    }

    @media (max-width: 768px) {
        .dashboard-page {
            padding: 80px 0 40px;
        }

        .dashboard-page .container {
            padding: 0 15px;
        }

        .dashboard-header {
            margin-bottom: 40px;
            padding: 0 5px;
        }

        .dashboard-title {
            font-size: clamp(1.5rem, 6vw, 2rem);
        }

        .dashboard-subtitle {
            font-size: 0.9rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 40px;
        }

        .stat-card {
            padding: 30px 20px;
        }

        .stat-value {
            font-size: 2.5rem;
        }

        .admin-tabs {
            margin: 0 0 40px 0;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }

        .tab-nav {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
        }

        .tab-item {
            width: 100%;
        }

        .tab-button {
            padding: 15px 10px;
            font-size: 0.75rem;
        }

        .tab-content {
            padding: 25px 15px;
            min-height: auto;
        }

        .admin-table {
            font-size: 0.8rem;
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .admin-table thead {
            display: table-header-group;
        }

        .admin-table tbody {
            display: table-row-group;
        }

        .admin-table tr {
            display: table-row;
        }

        .admin-table th,
        .admin-table td {
            display: table-cell;
            white-space: nowrap;
            min-width: 100px;
        }

        .admin-table th {
            padding: 12px 10px;
            font-size: 0.75rem;
        }

        .admin-table td {
            padding: 12px 10px;
        }

        .btn-action {
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .modal-content {
            width: 95%;
            max-width: 95%;
            padding: 30px 20px;
            margin: 20px auto;
        }

        .modal form .form-input,
        .modal form .form-select,
        .modal form textarea {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .dashboard-page {
            padding: 70px 0 30px;
        }

        .stat-card {
            padding: 25px 15px;
        }

        .stat-value {
            font-size: 2rem;
        }

        .tab-content {
            padding: 20px 10px;
        }
    }

    /* Admin Calendar Styles */
    .admin-calendar-section {
        margin-bottom: 60px;
        background: white;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .admin-calendar-section .section-title {
        font-size: 1.3rem;
        font-weight: 400;
        color: #1a1a1a;
        margin-bottom: 20px;
        letter-spacing: -0.5px;
    }

    #admin-calendar-picker {
        margin-top: 0;
    }

    .calendar-container {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 20px;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .calendar-nav-btn {
        background: transparent;
        border: 1px solid #e0e0e0;
        padding: 8px 15px;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #1a1a1a;
    }

    .calendar-nav-btn:hover {
        background: #1a1a1a;
        color: white;
        border-color: #1a1a1a;
    }

    .calendar-month-year {
        font-size: 1.1rem;
        font-weight: 500;
        color: #1a1a1a;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        margin-bottom: 10px;
    }

    .calendar-weekday {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 500;
        color: #999;
        text-transform: uppercase;
        padding: 8px 0;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        transition: all 0.3s ease;
        position: relative;
    }

    .calendar-day.disabled {
        color: #ccc;
        cursor: not-allowed;
        background: #f9f9f9;
    }

    .calendar-day.other-month {
        color: #ddd;
        background: #fafafa;
    }

    .calendar-day.today {
        border: 2px solid #1a1a1a;
        font-weight: 600;
    }

    .calendar-day.has-booking {
        cursor: pointer;
        position: relative;
        font-weight: 600;
    }

    .calendar-day.has-booking.pending {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border-color: #f59e0b;
    }

    .calendar-day.has-booking.confirmed {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        border-color: #3b82f6;
    }

    .calendar-day.has-booking.on_progress {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border-color: #10b981;
    }

    .calendar-day.has-booking:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .calendar-legend {
        display: flex;
        gap: 20px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #666;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }

    .legend-color.available {
        background: white;
    }

    @media (max-width: 768px) {
        .admin-calendar-section {
            margin-bottom: 40px;
            padding: 20px 15px;
        }

        .calendar-container {
            padding: 15px;
        }

        .calendar-day {
            font-size: 0.8rem;
        }

        .calendar-legend {
            flex-direction: column;
            gap: 10px;
        }
    }

    .notification-badge {
        position: absolute;
        top: 10px;
        right: 20px;
        background-color: #ef4444;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.7rem;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        line-height: 1;
    }
    
    @media (max-width: 768px) {
        .notification-badge {
            top: 5px;
            right: 5px;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-page">
    <div class="dashboard-background"></div>
    <div class="container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard Admin</h1>
            <p class="dashboard-subtitle">Selamat datang, {{ Auth::user()->name }}</p>
</div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Customers</div>
                <div class="stat-value">{{ $totalCustomers }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Bookings</div>
                <div class="stat-value">{{ $totalBookings }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pending Bookings</div>
                <div class="stat-value warning">{{ $pendingBookings }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pending Payments</div>
                <div class="stat-value warning">{{ $pendingPayments }}</div>
            </div>
        </div>

        <!-- Admin Calendar -->
        <div class="admin-calendar-section">
            <h2 class="section-title" style="font-size: 1.3rem; font-weight: 400; color: #1a1a1a; margin-bottom: 20px; letter-spacing: -0.5px;">Kalender Booking</h2>
            <div id="admin-calendar-picker" class="calendar-container"></div>
        </div>

        <div class="admin-tabs">
            <ul class="tab-nav">
                <li class="tab-item">
                    <button class="tab-button active" data-tab="customers">Kelola Customers</button>
                </li>
                <li class="tab-item">
                    <button class="tab-button" data-tab="bookings" style="position: relative;">
                        Kelola Bookings
                        @if($pendingBookings > 0)
                            <span class="notification-badge">{{ $pendingBookings }}</span>
                        @endif
                    </button>
                </li>
                <li class="tab-item">
                    <button class="tab-button" data-tab="payments">Kelola Payments</button>
                </li>
                <li class="tab-item">
                    <button class="tab-button" data-tab="profile">Profil WINWIN</button>
                </li>
                <li class="tab-item">
                    <button class="tab-button" data-tab="portfolio">Portfolio</button>
                </li>
                <li class="tab-item">
                    <button class="tab-button" data-tab="packages">Paket</button>
                </li>
                <li class="tab-item">
                    <button class="tab-button" data-tab="addons">Global Add-Ons</button>
                </li>
                <li class="tab-item">
                    <button class="tab-button" data-tab="reports">Laporan</button>
                </li>
            </ul>

            <!-- Customers Tab -->
            <div id="tab-customers" class="tab-content active">
                @include('dashboard.admin.tabs.customers', ['users' => $users])
            </div>

            <!-- Bookings Tab -->
            <div id="tab-bookings" class="tab-content">
                @include('dashboard.admin.tabs.bookings', ['bookings' => $bookings])
    </div>

            <!-- Payments Tab -->
            <div id="tab-payments" class="tab-content">
                @include('dashboard.admin.tabs.payments', ['payments' => $payments])
            </div>

            <!-- Profile Tab -->
            <div id="tab-profile" class="tab-content">
                @include('dashboard.admin.tabs.profile', ['muaProfile' => $muaProfile])
        </div>

            <!-- Portfolio Tab -->
            <div id="tab-portfolio" class="tab-content">
                @include('dashboard.admin.tabs.portfolio', ['portfolios' => $portfolios, 'muaProfile' => $muaProfile])
    </div>

            <!-- Packages Tab -->
            <div id="tab-packages" class="tab-content">
                @include('dashboard.admin.tabs.packages', ['packages' => $packages, 'muaProfile' => $muaProfile])
            </div>

            <!-- Add-Ons Tab -->
            <div id="tab-addons" class="tab-content">
                @include('dashboard.admin.tabs.addons', ['addOns' => $addOns])
            </div>
            
            <!-- Reports Tab -->
            <div id="tab-reports" class="tab-content">
                @include('dashboard.admin.tabs.reports', ['paidBookings' => $paidBookings])
            </div>
        </div>
    </div>
</div>

<!-- New Order Notification Modal -->
@if($pendingBookings > 0)
<div id="newOrderModal" class="modal">
    <div class="modal-content" style="max-width: 400px; text-align: center; border-radius: 10px;">
        <button class="modal-close" onclick="closeNewOrderModal()">&times;</button>
        <div style="margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; background: #e0f2fe; color: #0ea5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 500; margin-bottom: 10px; color: #1a1a1a;">Pesanan Baru!</h2>
            <p style="color: #666; margin-bottom: 0;">Ada <span style="font-weight: 600; color: #ef4444;">{{ $pendingBookings }} pesanan baru</span> yang perlu diproses.</p>
        </div>
        <div class="modal-actions" style="justify-content: center; border-top: none; margin-top: 0; padding-top: 0;">
            <button onclick="viewNewOrders()" class="btn-modal btn-modal-primary" style="width: 100%;">Lihat Pesanan</button>
        </div>
    </div>
</div>
@endif

<!-- Booking Detail Modal -->
<div id="bookingDetailModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <button class="modal-close" onclick="closeBookingDetailModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Detail Booking</h2>
            </div>
        <div id="bookingDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Payment Detail Modal -->
<div id="paymentDetailModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <button class="modal-close" onclick="closePaymentDetailModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Detail Pembayaran</h2>
            </div>
        <div id="paymentDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab switching
    function switchTab(tabName) {
        // Remove active class from all buttons and contents
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        // Add active class to clicked button and corresponding content
        const button = document.querySelector(`[data-tab="${tabName}"]`);
        const content = document.getElementById('tab-' + tabName);
        if (button) button.classList.add('active');
        if (content) content.classList.add('active');
    }
    
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            switchTab(tabName);
        });
    });
    
    // Set active tab from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        switchTab(tabParam);
    } else {
        @if(isset($activeTab))
        switchTab('{{ $activeTab }}');
        @endif
    }

    // Admin Calendar Variables
    let adminBookingsByDate = {};
    let adminCurrentMonth = new Date().getMonth();
    let adminCurrentYear = new Date().getFullYear();

    // Load admin bookings by date
    async function loadAdminBookingsByDate() {
        try {
            const response = await fetch('/api/admin/bookings-by-date');
            const data = await response.json();
            adminBookingsByDate = data.bookings || {};
            // Only render if element exists
            if(document.getElementById('admin-calendar-picker')) {
                renderAdminCalendar();
            }
        } catch (error) {
            console.error('Error loading admin bookings:', error);
        }
    }

    // New Order Popup Logic
    @if($pendingBookings > 0)
    function openNewOrderModal() {
        document.getElementById('newOrderModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeNewOrderModal() {
        document.getElementById('newOrderModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function viewNewOrders() {
        closeNewOrderModal();
        switchTab('bookings');
        // Scroll to bookings section if needed or just switch tab
    }

    // Check session storage on load
    document.addEventListener('DOMContentLoaded', function() {
        if (!sessionStorage.getItem('newOrderSeen')) {
            // Delay slightly for effect
            setTimeout(() => {
                openNewOrderModal();
                sessionStorage.setItem('newOrderSeen', 'true');
            }, 500);
        }
    });
    @endif

    // Render Admin Calendar
    function renderAdminCalendar() {
        const calendar = document.getElementById('admin-calendar-picker');
        const today = new Date();
        const firstDay = new Date(adminCurrentYear, adminCurrentMonth, 1);
        const lastDay = new Date(adminCurrentYear, adminCurrentMonth + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        let html = `
            <div class="calendar-header">
                <button type="button" class="calendar-nav-btn" onclick="adminPreviousMonth(event)">‹</button>
                <div class="calendar-month-year">${monthNames[adminCurrentMonth]} ${adminCurrentYear}</div>
                <button type="button" class="calendar-nav-btn" onclick="adminNextMonth(event)">›</button>
            </div>
            <div class="calendar-weekdays">
                ${dayNames.map(day => `<div class="calendar-weekday">${day}</div>`).join('')}
            </div>
            <div class="calendar-days">
        `;

        // Empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            html += `<div class="calendar-day other-month"></div>`;
        }

        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${adminCurrentYear}-${String(adminCurrentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dateObj = new Date(adminCurrentYear, adminCurrentMonth, day);
            const isToday = dateObj.toDateString() === today.toDateString();
            const isPast = dateObj < today;
            const bookingData = adminBookingsByDate[dateStr];

            let classes = 'calendar-day';
            let onClick = '';
            
            if (isPast) {
                classes += ' disabled';
            } else if (bookingData) {
                classes += ' has-booking ' + bookingData.status;
                onClick = `onclick="openBookingDetailModal(${bookingData.booking_id})"`;
            }

            if (isToday) classes += ' today';

            html += `<div class="${classes}" ${onClick}>${day}</div>`;
        }

        html += `
            </div>
            <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-color available"></div>
                    <span>Tersedia</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(245, 158, 11, 0.2); border-color: #f59e0b;"></div>
                    <span>Pending</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(59, 130, 246, 0.2); border-color: #3b82f6;"></div>
                    <span>Confirmed</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: rgba(16, 185, 129, 0.2); border-color: #10b981;"></div>
                    <span>On Progress</span>
                </div>
            </div>
        `;

        calendar.innerHTML = html;
    }

    window.adminPreviousMonth = function(event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        adminCurrentMonth--;
        if (adminCurrentMonth < 0) {
            adminCurrentMonth = 11;
            adminCurrentYear--;
        }
        renderAdminCalendar();
    };

    window.adminNextMonth = function(event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        adminCurrentMonth++;
        if (adminCurrentMonth > 11) {
            adminCurrentMonth = 0;
            adminCurrentYear++;
        }
        renderAdminCalendar();
    };

    // Load calendar on page load
    loadAdminBookingsByDate();

    // Update Booking Status Function
    window.updateBookingStatus = function(bookingId, newStatus, selectElement) {
        if (!newStatus) {
            return;
        }

        if (!confirm('Yakin ingin mengupdate status booking ini?')) {
            selectElement.value = '';
            return;
        }

        selectElement.disabled = true;
        
        const formData = new FormData();
        formData.append('status', newStatus);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Jika status rejected, minta alasan
        if (newStatus === 'rejected') {
            const reason = prompt('Masukkan alasan penolakan:');
            if (!reason) {
                selectElement.value = '';
                selectElement.disabled = false;
                return;
            }
            formData.append('rejection_reason', reason);
        }

        fetch(`/booking/${bookingId}/status`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload calendar data
                loadAdminBookingsByDate();
                // Redirect ke dashboard dengan tab bookings
                window.location.href = '/dashboard?tab=bookings';
            } else {
                alert('Terjadi kesalahan saat mengupdate status.');
                selectElement.value = '';
                selectElement.disabled = false;
            }
        })
                        .catch(error => {
                            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupdate status.');
            selectElement.value = '';
            selectElement.disabled = false;
        });
    };

    // Booking Detail Modal
    function openBookingDetailModal(bookingId) {
        const modal = document.getElementById('bookingDetailModal');
        const content = document.getElementById('bookingDetailContent');
        
        content.innerHTML = '<div style="text-align: center; padding: 40px;"><p>Memuat data...</p></div>';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        fetch(`/api/booking/${bookingId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.booking) {
                    renderBookingDetail(data.booking);
                } else {
                    content.innerHTML = '<div style="padding: 20px;"><p>Data booking tidak ditemukan.</p></div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<div style="padding: 20px;"><p>Terjadi kesalahan saat memuat data.</p></div>';
            });
    }

    function renderBookingDetail(booking) {
        const content = document.getElementById('bookingDetailContent');
        const statusClass = {
            'pending': 'pending',
            'confirmed': 'confirmed',
            'on_progress': 'info',
            'completed': 'completed',
            'cancelled': 'cancelled',
            'rejected': 'cancelled'
        }[booking.status] || 'pending';

        const statusText = booking.status.replace('_', ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');

        let html = `
            <div style="display: grid; gap: 25px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <div class="profile-label">Kode Booking</div>
                        <div class="profile-value">${booking.booking_code}</div>
                    </div>
                    <div>
                        <div class="profile-label">Status</div>
                        <div class="profile-value">
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
                    <div class="profile-label">Customer</div>
                    <div class="profile-value">${booking.customer ? booking.customer.name : '-'}</div>
                </div>

                <div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
                    <div class="profile-label">Paket</div>
                    <div class="profile-value">${booking.package ? booking.package.name : '-'}</div>
                </div>

                ${booking.selected_add_ons && booking.selected_add_ons.length > 0 ? `
                <div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
                    <div class="profile-label">Add-Ons</div>
                    <ul style="margin: 5px 0 0 20px; padding: 0; color: #1a1a1a;">
                        ${booking.selected_add_ons.map(addon => `<li style="margin-bottom: 5px;">${addon.name} (Rp ${parseInt(addon.price).toLocaleString('id-ID')})</li>`).join('')}
                    </ul>
                </div>
                ` : ''}

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <div class="profile-label">Tanggal</div>
                        <div class="profile-value">${formatDate(booking.booking_date)}</div>
                    </div>
                    <div>
                        <div class="profile-label">Waktu</div>
                        <div class="profile-value">${booking.booking_time}</div>
                    </div>
                </div>

                ${booking.event_location ? `
                <div>
                    <div class="profile-label">Lokasi Acara</div>
                    <div class="profile-value">${booking.event_location}</div>
                </div>
                ` : ''}

                ${booking.event_type ? `
                <div>
                    <div class="profile-label">Jenis Acara</div>
                    <div class="profile-value">${booking.event_type}</div>
                </div>
                ` : ''}

                ${booking.notes ? `
                <div>
                    <div class="profile-label">Catatan</div>
                    <div class="profile-value">${booking.notes}</div>
                </div>
                ` : ''}

                <div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="profile-label">Total Pembayaran</div>
                        <div style="font-size: 1.5rem; font-weight: 300; color: #1a1a1a; letter-spacing: -1px;">
                            Rp ${parseInt(booking.total_price).toLocaleString('id-ID')}
                        </div>
                    </div>
                </div>

                ${booking.payment ? `
                <div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
                    <h4 style="font-size: 1rem; font-weight: 500; margin-bottom: 15px;">Informasi Pembayaran</h4>
                    <div class="profile-label">Status Pembayaran</div>
                    <div class="profile-value">
                        <span class="status-badge ${booking.payment.status === 'verified' ? 'completed' : booking.payment.status === 'rejected' ? 'cancelled' : 'pending'}">
                            ${booking.payment.status.charAt(0).toUpperCase() + booking.payment.status.slice(1)}
                        </span>
                    </div>
                    ${booking.payment.payment_method ? `
                    <div style="margin-top: 15px;">
                        <div class="profile-label">Metode Pembayaran</div>
                        <div class="profile-value">${booking.payment.payment_method.replace('_', ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')}</div>
                    </div>
                    ` : ''}
                    ${booking.payment.payment_proof ? `
                    <div style="margin-top: 15px;">
                        <div class="profile-label">Bukti Pembayaran</div>
                        <div style="margin-top: 10px;">
                            <img src="/storage/${booking.payment.payment_proof}" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 400px; border: 1px solid #e0e0e0; border-radius: 4px; cursor: pointer;" onclick="openImageModal('/storage/${booking.payment.payment_proof}')">
                        </div>
                    </div>
                    ` : ''}
                    ${booking.payment.notes ? `
                    <div style="margin-top: 15px;">
                        <div class="profile-label">Catatan</div>
                        <div class="profile-value">${booking.payment.notes}</div>
                    </div>
                    ` : ''}
                    ${booking.payment.rejection_reason ? `
                    <div style="margin-top: 15px; padding: 15px; background: #fee; border: 1px solid #fcc; border-radius: 4px;">
                        <div class="profile-label" style="color: #ef4444;">Alasan Ditolak</div>
                        <div class="profile-value" style="color: #ef4444;">${booking.payment.rejection_reason}</div>
                    </div>
                    ` : ''}
                    
                    ${booking.payment.status === 'pending' ? `
                    <div style="border-top: 1px solid #e0e0e0; padding-top: 20px; margin-top: 20px;">
                        <h4 style="font-size: 1rem; font-weight: 500; margin-bottom: 15px;">Verifikasi Pembayaran</h4>
                        <form method="POST" action="/payment/${booking.payment.id}/verify" id="paymentVerifyForm">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-input" id="paymentStatusSelect" required>
                                    <option value="verified">Verifikasi</option>
                                    <option value="rejected">Tolak</option>
                                </select>
                            </div>
                            <div class="form-group" id="rejectionReasonGroup" style="display: none;">
                                <label class="form-label">Alasan Penolakan <span style="color: #ef4444;">*</span></label>
                                <textarea name="rejection_reason" id="rejectionReasonText" class="form-input" rows="3" style="min-height: 80px; resize: vertical;"></textarea>
                            </div>
                            <div class="modal-actions" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                                <button type="submit" class="btn-modal btn-modal-primary">Update Status Pembayaran</button>
                            </div>
                        </form>
                    </div>
                    ` : ''}
                </div>
                ` : ''}

                ${['pending', 'confirmed', 'on_progress'].includes(booking.status) ? `
                <div style="border-top: 1px solid #e0e0e0; padding-top: 20px; margin-top: 20px;">
                    <h4 style="font-size: 1rem; font-weight: 500; margin-bottom: 15px;">Update Status Booking</h4>
                    <form method="POST" action="/booking/${booking.id}/status" id="bookingStatusForm">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <div class="form-group">
                            <label class="form-label">Status Baru</label>
                            <select name="status" class="form-input" required>
                                ${booking.status === 'pending' ? `
                                    <option value="confirmed">Terima</option>
                                    <option value="rejected">Tolak</option>
                                ` : booking.status === 'confirmed' ? `
                                    <option value="on_progress">Mulai Pekerjaan</option>
                                ` : booking.status === 'on_progress' ? `
                                    <option value="completed">Selesaikan Pekerjaan</option>
                                ` : ''}
                            </select>
                        </div>
                        ${booking.status === 'pending' ? `
                        <div class="form-group" id="bookingRejectionReasonGroup" style="display: none;">
                            <label class="form-label">Alasan Penolakan <span style="color: #ef4444;">*</span></label>
                            <textarea name="rejection_reason" id="bookingRejectionReasonText" class="form-input" rows="3" style="min-height: 80px; resize: vertical;"></textarea>
                        </div>
                        ` : ''}
                        <div class="modal-actions" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                            <button type="submit" class="btn-modal btn-modal-primary">Update Status Booking</button>
                        </div>
                    </form>
                </div>
                ` : ''}

            </div>
        `;

        content.innerHTML = html;
        
        // Setup payment verification form
        const paymentForm = content.querySelector('#paymentVerifyForm');
        if (paymentForm) {
            const statusSelect = content.querySelector('#paymentStatusSelect');
            const rejectionReasonGroup = content.querySelector('#rejectionReasonGroup');
            const rejectionReasonText = content.querySelector('#rejectionReasonText');
            
            // Show/hide rejection reason field based on status
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    if (this.value === 'rejected') {
                        rejectionReasonGroup.style.display = 'block';
                        rejectionReasonText.setAttribute('required', 'required');
                    } else {
                        rejectionReasonGroup.style.display = 'none';
                        rejectionReasonText.removeAttribute('required');
                        rejectionReasonText.value = '';
                    }
                });
            }
            
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate rejection reason if status is rejected
                if (statusSelect.value === 'rejected' && !rejectionReasonText.value.trim()) {
                    alert('Alasan penolakan harus diisi.');
                    return;
                }
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Memproses...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload booking detail to show updated payment status
                        openBookingDetailModal(booking.id);
                        // Reload calendar
                        loadAdminBookingsByDate();
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat mengupdate status pembayaran.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate status pembayaran.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
            });
        }
        
        // Setup booking status form
        const bookingForm = content.querySelector('#bookingStatusForm');
        if (bookingForm) {
            const bookingStatusSelect = bookingForm.querySelector('select[name="status"]');
            const bookingRejectionReasonGroup = content.querySelector('#bookingRejectionReasonGroup');
            const bookingRejectionReasonText = content.querySelector('#bookingRejectionReasonText');
            
            // Show/hide rejection reason field based on status (only for pending bookings)
            if (bookingStatusSelect && booking.status === 'pending') {
                bookingStatusSelect.addEventListener('change', function() {
                    if (this.value === 'rejected') {
                        if (bookingRejectionReasonGroup) {
                            bookingRejectionReasonGroup.style.display = 'block';
                            if (bookingRejectionReasonText) {
                                bookingRejectionReasonText.setAttribute('required', 'required');
                            }
                        }
                    } else {
                        if (bookingRejectionReasonGroup) {
                            bookingRejectionReasonGroup.style.display = 'none';
                            if (bookingRejectionReasonText) {
                                bookingRejectionReasonText.removeAttribute('required');
                                bookingRejectionReasonText.value = '';
                            }
                        }
                    }
                });
            }
            
            bookingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate rejection reason if status is rejected
                if (bookingStatusSelect && bookingStatusSelect.value === 'rejected' && bookingRejectionReasonText && !bookingRejectionReasonText.value.trim()) {
                    alert('Alasan penolakan harus diisi.');
                    return;
                }
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Memproses...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload booking detail to show updated status
                        openBookingDetailModal(booking.id);
                        // Reload calendar
                        loadAdminBookingsByDate();
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat mengupdate status booking.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate status booking.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
            });
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    function closeBookingDetailModal() {
        document.getElementById('bookingDetailModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Payment Detail Modal
    function openPaymentDetailModal(paymentId) {
        const modal = document.getElementById('paymentDetailModal');
        const content = document.getElementById('paymentDetailContent');
        
        content.innerHTML = '<div style="text-align: center; padding: 40px;"><p>Memuat data...</p></div>';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        fetch(`/payment/${paymentId}`)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const paymentData = doc.querySelector('.card-body');
                
                if (paymentData) {
                    renderPaymentDetail(paymentData, paymentId);
                } else {
                    content.innerHTML = '<div style="padding: 20px;"><p>Data payment tidak ditemukan.</p></div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<div style="padding: 20px;"><p>Terjadi kesalahan saat memuat data.</p></div>';
            });
    }

    function renderPaymentDetail(paymentData, paymentId) {
        const content = document.getElementById('paymentDetailContent');
        const paragraphs = paymentData.querySelectorAll('p');
        const images = paymentData.querySelectorAll('img');
        const alerts = paymentData.querySelectorAll('.alert');
        
        let html = '<div style="display: grid; gap: 25px;">';
        
        paragraphs.forEach(p => {
            const text = p.textContent.trim();
            if (text) {
                html += `<div style="border-bottom: 1px solid #e0e0e0; padding-bottom: 15px;">${p.innerHTML}</div>`;
            }
        });

        images.forEach(img => {
            html += `<div style="margin: 20px 0;"><img src="${img.src}" style="max-width: 100%; max-height: 400px; border: 1px solid #e0e0e0;"></div>`;
        });

        alerts.forEach(alert => {
            html += `<div style="padding: 15px; background: #fee; border: 1px solid #fcc; margin: 20px 0;">${alert.innerHTML}</div>`;
        });

        // Add verification form if admin and pending
        const verifyForm = paymentData.parentElement.nextElementSibling?.querySelector('form');
        if (verifyForm) {
            html += '<div style="border-top: 1px solid #e0e0e0; padding-top: 20px; margin-top: 20px;">';
            html += '<h4 style="font-size: 1rem; font-weight: 500; margin-bottom: 15px;">Verifikasi Pembayaran</h4>';
            html += verifyForm.outerHTML;
            html += '</div>';
            
            // Make form submit via AJAX
            setTimeout(() => {
                const form = content.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.textContent;
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Memproses...';
                        
                        fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closePaymentDetailModal();
                                location.reload();
                            } else {
                                alert('Terjadi kesalahan.');
                                submitBtn.disabled = false;
                                submitBtn.textContent = originalText;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan.');
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        });
                    });
                }
            }, 100);
        }

        html += '</div>';
        content.innerHTML = html;
    }

    function closePaymentDetailModal() {
        document.getElementById('paymentDetailModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modals when clicking outside
    document.getElementById('bookingDetailModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingDetailModal();
        }
    });

    document.getElementById('paymentDetailModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentDetailModal();
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBookingDetailModal();
            closePaymentDetailModal();
            closeImageModal();
        }
    });

    // Image Modal for viewing payment proof
    function openImageModal(imageSrc) {
        const modal = document.createElement('div');
        modal.id = 'imageModal';
        modal.className = 'modal show';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="modal-content" style="max-width: 90%; max-height: 90vh; padding: 20px; background: transparent; box-shadow: none;">
                <button class="modal-close" onclick="closeImageModal()" style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.5); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">&times;</button>
                <img src="${imageSrc}" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 90vh; border-radius: 4px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            </div>
        `;
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.remove();
            document.body.style.overflow = 'auto';
        }
    }

    // Close image modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('imageModal');
        if (modal && e.target === modal) {
            closeImageModal();
        }
    });
</script>
@endpush
