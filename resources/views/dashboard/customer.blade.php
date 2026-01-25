@extends('layouts.app')

@section('title', 'Dashboard - WINWIN Makeup')

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
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 60px;
    }

    .stat-card {
        background: white;
        padding: 30px 20px;
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
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 300;
        color: #1a1a1a;
        line-height: 1;
        letter-spacing: -2px;
    }

    .stat-value.warning {
        color: #f59e0b;
    }

    .stat-value.info {
        color: #3b82f6;
    }

    .stat-value.success {
        color: #10b981;
    }

    .section {
        margin-bottom: 60px;
    }

    .section-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 400;
        color: #1a1a1a;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .btn-edit {
        padding: 8px 20px;
        background: transparent;
        color: #1a1a1a;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid #1a1a1a;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        background: #1a1a1a;
        color: white;
    }

    .profile-section {
        background: white;
        border: none;
        padding: 40px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .profile-item {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
    }

    .profile-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .profile-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .profile-value {
        font-size: 1rem;
        color: #1a1a1a;
        font-weight: 400;
    }

    .bookings-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: white;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .bookings-list:has(.booking-item) {
        padding: 20px;
    }

    .bookings-header {
        padding: 30px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bookings-header h3 {
        font-size: 1.2rem;
        font-weight: 500;
        color: #1a1a1a;
        margin: 0;
    }

    .btn-link {
        padding: 8px 20px;
        background: #1a1a1a;
        color: white;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        border: 1px solid #1a1a1a;
    }

    .btn-link:hover {
        background: transparent;
        color: #1a1a1a;
    }

    .booking-item {
        padding: 25px 30px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 30px;
        align-items: center;
        transition: all 0.3s ease;
        background: white;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .booking-item:hover {
        background: #fafafa;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .booking-info {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 20px;
        align-items: center;
    }

    .booking-info h4 {
        font-size: 1rem;
        font-weight: 500;
        color: #1a1a1a;
        margin: 0;
    }

    .booking-detail {
        font-size: 0.85rem;
        color: #666;
        line-height: 1.5;
    }

    .booking-detail strong {
        color: #333;
        font-weight: 500;
        display: block;
        margin-bottom: 4px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .booking-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .booking-price {
        font-size: 1.1rem;
        font-weight: 400;
        color: #1a1a1a;
        text-align: right;
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

    .status-badge.cancelled {
        color: #ef4444;
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
    }

    .btn-detail {
        padding: 10px 24px;
        background: transparent;
        color: #1a1a1a;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid #1a1a1a;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-detail:hover {
        background: #1a1a1a;
        color: white;
    }

    .empty-state {
        padding: 60px 30px;
        text-align: center;
        color: #999;
        background: white;
    }

    .empty-state p {
        margin: 0;
        font-size: 1rem;
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
            padding: 25px 20px;
        }

        .stat-value {
            font-size: 2.5rem;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.1rem;
        }

        .profile-section {
            padding: 30px 20px;
        }

        .profile-info {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .bookings-list {
            gap: 15px;
            padding: 20px 15px;
        }

        .bookings-header {
            padding: 20px 15px;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .bookings-header h3 {
            font-size: 1rem;
        }

        .booking-item {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 20px 15px;
        }

        .booking-info {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .booking-info h4 {
            font-size: 0.95rem;
        }

        .booking-detail {
            font-size: 0.8rem;
        }

        .booking-price {
            text-align: left;
            font-size: 1rem;
        }

        .btn-detail {
            width: 100%;
            text-align: center;
        }

        .btn-booking {
            width: 100%;
            text-align: center;
        }

        .dashboard-header-actions {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
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
            padding: 20px 15px;
        }

        .stat-value {
            font-size: 2rem;
        }

        .profile-section {
            padding: 25px 15px;
        }

        .bookings-header {
            padding: 15px;
        }

        .booking-item {
            padding: 15px;
        }
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

    .form-input::placeholder {
        color: #999;
        font-weight: 300;
    }

    textarea.form-input {
        resize: vertical;
        min-height: 80px;
    }

    select.form-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0 center;
        padding-right: 30px;
    }

    input[type="file"].form-input {
        padding: 12px 0;
        cursor: pointer;
    }

    input[type="file"].form-input::-webkit-file-upload-button {
        padding: 8px 16px;
        background: #1a1a1a;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
        margin-right: 10px;
    }

    .form-error {
        font-size: 0.85rem;
        color: #ef4444;
        margin-top: 8px;
        display: block;
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

    .btn-booking {
        padding: 12px 30px;
        background: #1a1a1a;
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid #1a1a1a;
        transition: all 0.3s ease;
        display: inline-block;
        cursor: pointer;
    }

    .btn-booking:hover {
        background: transparent;
        color: #1a1a1a;
    }

    .btn-packages {
        padding: 12px 30px;
        background: transparent;
        color: #1a1a1a;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid #1a1a1a;
        transition: all 0.3s ease;
        display: inline-block;
        cursor: pointer;
    }

    .btn-packages:hover {
        background: #1a1a1a;
        color: white;
    }

    .dashboard-header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-top: 20px;
    }

    /* Custom Calendar Picker Styles */
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

    .calendar-day.booked-1 {
        background: #fff4e6;
        color: #f97316;
        border-color: #f97316;
    }

    .calendar-day.booked-2 {
        background: #fee;
        color: #ef4444;
        border-color: #ef4444;
    }

    .calendar-day.booked-3 {
        background: #fee;
        color: #ef4444;
        cursor: not-allowed;
        position: relative;
    }

    .calendar-day.booked-3::after {
        content: '×';
        position: absolute;
        font-size: 1.2rem;
        font-weight: bold;
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
        background: #fff4e6;
        border-color: #f97316;
    }

    .legend-color.booked-2 {
        background: #fee;
        border-color: #ef4444;
    }

    .legend-color.booked-3 {
        background: #fee;
        border-color: #ef4444;
    }

    .legend-color.available {
        background: white;
        border-color: #1a1a1a;
    }

    /* Packages Modal Styles */
    .packages-modal-content {
        display: flex;
        flex-direction: column;
        gap: 25px;
        max-height: 70vh;
        overflow-y: auto;
        padding-right: 10px;
    }

    .package-modal-item {
        padding: 25px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background: #fafafa;
        transition: all 0.3s ease;
    }

    .package-modal-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .package-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
        gap: 20px;
    }

    .package-modal-name {
        font-size: 1.3rem;
        font-weight: 500;
        color: #1a1a1a;
        margin: 0;
        flex: 1;
    }

    .package-modal-price {
        font-size: 1.2rem;
        font-weight: 400;
        color: #1a1a1a;
        white-space: nowrap;
    }

    .package-modal-images {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 15px;
    }

    .package-modal-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .package-modal-image:hover {
        transform: scale(1.1);
        border-color: #1a1a1a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .package-modal-description {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .package-modal-details {
        margin-bottom: 15px;
    }

    .package-detail-item {
        display: flex;
        gap: 10px;
        margin-bottom: 8px;
    }

    .package-detail-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        min-width: 80px;
    }

    .package-detail-value {
        font-size: 0.9rem;
        color: #1a1a1a;
    }

    .package-modal-includes {
        padding-top: 15px;
        border-top: 1px solid #e0e0e0;
    }

    .package-includes-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .package-includes-content {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.8;
        white-space: pre-line;
    }

    @media (max-width: 768px) {
        .modal-content {
            padding: 30px 20px;
        }

        .dashboard-header-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-booking,
        .btn-packages {
            width: 100%;
            text-align: center;
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

        .package-modal-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .package-modal-name {
            font-size: 1.1rem;
        }

        .package-modal-price {
            font-size: 1rem;
        }

        .package-modal-item {
            padding: 20px 15px;
        }

        .package-modal-image {
            width: 50px;
            height: 50px;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-page">
    <div class="dashboard-background"></div>
    <div class="container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Selamat datang, {{ $user->name }}</p>
            @if($packages->count() > 0)
            <div class="dashboard-header-actions">
                <button type="button" class="btn-booking" onclick="openBookingModal()">Buat Booking</button>
                <button type="button" class="btn-packages" onclick="openPackagesModal()">Lihat Paket</button>
    </div>
            @endif
</div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Pending</div>
                <div class="stat-value warning">{{ $pendingBookings }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Confirmed</div>
                <div class="stat-value info">{{ $confirmedBookings }}</div>
        </div>
            <div class="stat-card">
                <div class="stat-label">Completed</div>
                <div class="stat-value success">{{ $completedBookings }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Profil Saya</h2>
                <button type="button" class="btn-edit" onclick="openProfileModal()">Edit Profil</button>
            </div>
            <div class="profile-section">
                <div class="profile-info">
                    <div class="profile-item">
                        <div class="profile-label">Nama</div>
                        <div class="profile-value">{{ $user->name }}</div>
                    </div>
                    <div class="profile-item">
                        <div class="profile-label">Email</div>
                        <div class="profile-value">{{ $user->email }}</div>
                    </div>
                    @if($user->phone)
                    <div class="profile-item">
                        <div class="profile-label">Telepon</div>
                        <div class="profile-value">{{ $user->phone }}</div>
                    </div>
                    @endif
                    @if($user->address)
                    <div class="profile-item">
                        <div class="profile-label">Alamat</div>
                        <div class="profile-value">{{ $user->address }}</div>
                    </div>
                    @endif
        </div>
    </div>
</div>

        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Semua Booking</h2>
            </div>
            <div class="bookings-list">
                @forelse($bookings as $booking)
                    <div class="booking-item">
                        <div class="booking-info">
                            <div>
                                <h4>{{ $booking->package->name }}</h4>
                                <div class="booking-detail" style="margin-top: 8px;">
                                    <strong>Kode</strong>
                                    {{ $booking->booking_code }}
                                </div>
                            </div>
                            <div class="booking-meta">
                                <div class="booking-detail">
                                    <strong>Tanggal</strong>
                                    {{ $booking->booking_date->format('d M Y') }}
                                </div>
                                <div class="booking-detail">
                                    <strong>Waktu</strong>
                                    {{ $booking->booking_time }}
                                </div>
                            </div>
                            <div class="booking-meta">
                                @if($booking->event_location)
                                <div class="booking-detail">
                                    <strong>Lokasi</strong>
                                    {{ strlen($booking->event_location) > 30 ? substr($booking->event_location, 0, 30) . '...' : $booking->event_location }}
                                </div>
                                @endif
                                <div class="booking-detail">
                                    <strong>Status</strong>
                                    <span class="status-badge {{ $booking->status }}" style="margin-top: 4px; display: inline-block;">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="booking-price">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn-detail" onclick="openBookingDetailModal({{ $booking->id }})">Detail</button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>Belum ada booking.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Profile Edit Modal -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeProfileModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Edit Profil</h2>
        </div>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="modal_name" class="form-label">Nama</label>
                <input type="text" class="form-input @error('name') is-invalid @enderror" id="modal_name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="modal_email" class="form-label">Email</label>
                <input type="email" class="form-input @error('email') is-invalid @enderror" id="modal_email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="modal_phone" class="form-label">Telepon</label>
                <input type="text" class="form-input @error('phone') is-invalid @enderror" id="modal_phone" name="phone" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="modal_address" class="form-label">Alamat</label>
                <textarea class="form-input @error('address') is-invalid @enderror" id="modal_address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="modal_avatar" class="form-label">Avatar</label>
                <input type="file" class="form-input @error('avatar') is-invalid @enderror" id="modal_avatar" name="avatar" accept="image/*">
                @error('avatar')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                @if($user->avatar)
                    <small style="color: #999; margin-top: 8px; display: block;">Avatar saat ini: <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="max-height: 50px; margin-left: 10px;"></small>
                @endif
            </div>

            <div class="form-group">
                <label for="modal_password" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" class="form-input @error('password') is-invalid @enderror" id="modal_password" name="password">
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="modal_password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-input" id="modal_password_confirmation" name="password_confirmation">
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary">Update Profil</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeProfileModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Booking Modal -->
@if($packages->count() > 0)
<div id="bookingModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <button class="modal-close" onclick="closeBookingModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Buat Booking</h2>
        </div>
        <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
            @csrf
            <input type="hidden" name="package_id" id="booking_package_id" value="{{ $packages->first()->id }}">

            <div class="form-group">
                <label class="form-label">Pilih Paket</label>
                <select class="form-input" id="package_select" onchange="updatePackage()" required>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" data-price="{{ $package->price }}" data-name="{{ $package->name }}" data-duration="{{ $package->duration }}">
                            {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="addons-section" style="display: none;">
                <label class="form-label">Add-On (Opsional)</label>
                <div id="addons-list" style="display: grid; grid-template-columns: 1fr; gap: 10px; margin-top: 10px; max-height: 300px; overflow-y: auto; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px;">
                    <!-- Add-ons will be loaded here -->
                </div>
            </div>

            <div class="form-group">
                <label for="booking_date" class="form-label">Tanggal Booking</label>
                <div class="date-picker-wrapper">
                    <input type="date" class="form-input @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required style="display: none;">
                    <div id="calendar-picker" class="calendar-container" style="display: none;"></div>
                    <button type="button" class="form-input" id="date-display" style="text-align: left; cursor: pointer; background: transparent; border: none; border-bottom: 1px solid #e0e0e0; width: 100%; padding: 12px 0;" onclick="toggleCalendar(); return false;">
                        Pilih Tanggal
                    </button>
                    @error('booking_date')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="booking_time" class="form-label">Waktu Booking</label>
                <select class="form-input @error('booking_time') is-invalid @enderror" id="booking_time" name="booking_time" required>
                    <option value="">Pilih Waktu (Pilih tanggal terlebih dahulu)</option>
                </select>
                @error('booking_time')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <small class="form-text" style="color: #666; font-size: 0.85rem; margin-top: 5px;">
                    Setiap booking membutuhkan waktu 5 jam
                </small>
            </div>

            <div class="form-group">
                <label for="event_location" class="form-label">Lokasi Acara</label>
                <select class="form-input @error('event_location') is-invalid @enderror" id="event_location" name="event_location" required>
                    <option value="">Pilih Daerah</option>
                    <option value="Tegal Kota" {{ old('event_location') == 'Tegal Kota' ? 'selected' : '' }}>Tegal Kota</option>
                    <option value="Kabupaten Tegal" {{ old('event_location') == 'Kabupaten Tegal' ? 'selected' : '' }}>Kabupaten Tegal</option>
                    <option value="Brebes Kota" {{ old('event_location') == 'Brebes Kota' ? 'selected' : '' }}>Brebes Kota</option>
                    <option value="Brebes Kabupaten" {{ old('event_location') == 'Brebes Kabupaten' ? 'selected' : '' }}>Brebes Kabupaten</option>
                    <option value="Pemalang" {{ old('event_location') == 'Pemalang' ? 'selected' : '' }}>Pemalang</option>
                </select>
                @error('event_location')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="event_type" class="form-label">Jenis Acara</label>
                <select class="form-input @error('event_type') is-invalid @enderror" id="event_type" name="event_type" required>
                    <option value="">Pilih Jenis Acara</option>
                    <option value="wedding" {{ old('event_type') == 'wedding' ? 'selected' : '' }}>Wedding</option>
                    <option value="khitan" {{ old('event_type') == 'khitan' ? 'selected' : '' }}>Khitan</option>
                    <option value="engagement" {{ old('event_type') == 'engagement' ? 'selected' : '' }}>Engagement</option>
                    <option value="wisuda" {{ old('event_type') == 'wisuda' ? 'selected' : '' }}>Wisuda</option>
                    <option value="event" {{ old('event_type') == 'event' ? 'selected' : '' }}>Event</option>
                    <option value="lainnya" {{ old('event_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('event_type')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="custom_addon_id" class="form-label">Add On</label>
                <select class="form-input" id="custom_addon_id" name="custom_addon_id" onchange="calculateTotalPrice()">
                    <option value="" data-price="0">Pilih Add On (Opsional)</option>
                    @foreach($addOns as $addOn)
                        <option value="{{ $addOn->id }}" data-price="{{ $addOn->default_price }}">{{ $addOn->name }} - Rp {{ number_format($addOn->default_price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Catatan Khusus</label>
                <textarea class="form-input @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="padding: 20px; background: #fafafa; border: 1px solid #e0e0e0; margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: #999; font-weight: 500;">Total Pembayaran</span>
                    <span id="total_price" style="font-size: 1.5rem; font-weight: 300; color: #1a1a1a; letter-spacing: -1px;">Rp {{ number_format($packages->first()->price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div id="selected_addons_inputs" style="display: none;">
                <!-- Hidden inputs for selected add-ons will be added here -->
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary">Buat Booking</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeBookingModal()">Batal</button>
            </div>
        </form>
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

<!-- Payment Upload Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <button class="modal-close" onclick="closePaymentModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Pembayaran</h2>
        </div>
        <form id="paymentForm">
            @csrf
            <input type="hidden" id="payment_booking_id" name="booking_id">
            
            <div class="form-group">
                <p style="color: #666; line-height: 1.6; margin-bottom: 20px;">
                    Anda akan diarahkan ke halaman pembayaran aman Xendit. 
                    Silakan selesaikan pembayaran dalam waktu 1 jam.
                </p>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border: 1px solid #e0e0e0;">
                    <div style="font-size: 0.85rem; color: #999; margin-bottom: 5px;">TOTAL PEMBAYARAN</div>
                    <div class="payment-amount" style="font-size: 1.5rem; color: #1a1a1a; font-weight: 500;">
                        <!-- Amount filled by JS -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="payment_notes" class="form-label">Catatan (Opsional)</label>
                <textarea class="form-input" id="payment_notes" name="notes" rows="3"></textarea>
                <span class="form-error" id="payment_notes_error"></span>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary" id="payment_submit_btn">Bayar Sekarang</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closePaymentModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Packages Modal -->
@if($packages->count() > 0)
<div id="packagesModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <button class="modal-close" onclick="closePackagesModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Paket Layanan</h2>
        </div>
        <div class="packages-modal-content">
            @foreach($packages as $package)
            <div class="package-modal-item">
                <div class="package-modal-header">
                    <h3 class="package-modal-name">{{ $package->name }}</h3>
                    <div class="package-modal-price">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                </div>
                @if($package->images && count($package->images) > 0)
                <div class="package-modal-images">
                    @foreach($package->images as $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $package->name }}" class="package-modal-image">
                    @endforeach
                </div>
                @endif
                @if($package->description)
                <div class="package-modal-description">{{ $package->description }}</div>
                @endif
                <div class="package-modal-details">
                    <div class="package-detail-item">
                        <span class="package-detail-label">Durasi:</span>
                        <span class="package-detail-value">{{ $package->duration }} menit</span>
                    </div>
                </div>
                @if($package->includes)
                <div class="package-modal-includes">
                    <div class="package-includes-label">Yang termasuk:</div>
                    <div class="package-includes-content">{!! nl2br(e($package->includes)) !!}</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    function openProfileModal() {
        document.getElementById('profileModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeProfileModal() {
        document.getElementById('profileModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    let bookedDates = {}; // Object dengan key: date, value: count
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedDate = null;

    // Fetch booked dates
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
        const dateInput = document.getElementById('booking_date');
        
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

        // Empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            html += `<div class="calendar-day other-month"></div>`;
        }

        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dateObj = new Date(currentYear, currentMonth, day);
            const isToday = dateObj.toDateString() === today.toDateString();
            const isPast = dateObj < today;
            const bookingCount = bookedDates[dateStr] || 0;
            const isSelected = selectedDate === dateStr;

            let classes = 'calendar-day';
            if (isPast) classes += ' disabled';
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
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    }

    function nextMonth(event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
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
        document.getElementById('date-display').textContent = dateObj.toLocaleDateString('id-ID', options);
        document.getElementById('calendar-picker').style.display = 'none';
        renderCalendar();
        loadAvailableTimes(dateStr);
    }

    // Listen for date input changes (if user changes date manually)
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('booking_date');
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                if (this.value) {
                    selectedDate = this.value;
                    const dateObj = new Date(this.value);
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    const dateDisplay = document.getElementById('date-display');
                    if (dateDisplay) {
                        dateDisplay.textContent = dateObj.toLocaleDateString('id-ID', options);
                    }
                    loadAvailableTimes(this.value);
                }
            });
        }
    });

    // Load available times for selected date
    async function loadAvailableTimes(dateStr) {
        const timeSelect = document.getElementById('booking_time');
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

    // Close calendar when clicking outside (tapi jangan tutup kalau klik di dalam calendar)
    document.addEventListener('click', function(event) {
        const calendar = document.getElementById('calendar-picker');
        const datePickerWrapper = document.querySelector('.date-picker-wrapper');
        
        if (calendar && datePickerWrapper && calendar.style.display !== 'none') {
            // Jangan tutup jika klik di dalam calendar container atau date picker wrapper
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

    function openBookingModal() {
        document.getElementById('bookingModal').classList.add('show');
        document.body.style.overflow = 'hidden';
        // Reset calendar state
        const dateInput = document.getElementById('booking_date');
        const oldValue = dateInput.value;
        
        if (oldValue) {
            selectedDate = oldValue;
            const dateObj = new Date(oldValue);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('date-display').textContent = dateObj.toLocaleDateString('id-ID', options);
            currentMonth = dateObj.getMonth();
            currentYear = dateObj.getFullYear();
            // Load available times if date is already selected
            loadAvailableTimes(oldValue);
        } else {
            selectedDate = null;
            document.getElementById('date-display').textContent = 'Pilih Tanggal';
            currentMonth = new Date().getMonth();
            currentYear = new Date().getFullYear();
            // Reset time select
            const timeSelect = document.getElementById('booking_time');
            timeSelect.innerHTML = '<option value="">Pilih Tanggal terlebih dahulu</option>';
            timeSelect.disabled = true;
        }
        
        // Trigger package update
        updatePackage();
        
        // Load add-ons for initial package
        updatePackage();
        
        // Langsung buka calendar saat modal dibuka
        setTimeout(() => {
            document.getElementById('calendar-picker').style.display = 'block';
            loadBookedDates();
            renderCalendar();
        }, 100);
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function openPackagesModal() {
        document.getElementById('packagesModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closePackagesModal() {
        document.getElementById('packagesModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    async function updatePackage() {
        const select = document.getElementById('package_select');
        const selectedOption = select.options[select.selectedIndex];
        const packageId = selectedOption.value;
        const price = selectedOption.getAttribute('data-price');
        
        document.getElementById('booking_package_id').value = packageId;
        
        // Load add-ons for this package
        try {
            const response = await fetch(`/api/package/${packageId}/addons`);
            if (response.ok) {
                const data = await response.json();
                const addOns = data.add_ons || [];
                const addonsSection = document.getElementById('addons-section');
                const addonsList = document.getElementById('addons-list');
                
                if (addOns.length > 0) {
                    let html = '';
                    addOns.forEach(addOn => {
                        if (addOn.is_active) {
                            html += `
                                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; border: 1px solid #e0e0e0; border-radius: 4px; background: #fafafa;">
                                    <input type="checkbox" class="addon-checkbox" data-addon-id="${addOn.id}" data-addon-price="${addOn.price}" style="width: 18px; height: 18px; flex-shrink: 0;" onchange="calculateTotalPrice()">
                                    <div style="flex: 1;">
                                        <div style="font-size: 0.9rem; color: #333; font-weight: 500;">${addOn.name}</div>
                                        ${addOn.description ? `<div style="font-size: 0.8rem; color: #666; margin-top: 4px;">${addOn.description}</div>` : ''}
                                    </div>
                                    <div style="font-size: 0.9rem; color: #1a1a1a; font-weight: 500; white-space: nowrap;">
                                        Rp ${parseInt(addOn.price).toLocaleString('id-ID')}
                                    </div>
                                </div>
                            `;
                        }
                    });
                    addonsList.innerHTML = html;
                    addonsSection.style.display = 'block';
                } else {
                    addonsList.innerHTML = '<div style="padding: 15px; text-align: center; color: #999; font-size: 0.9rem;">Tidak ada add-on tersedia untuk paket ini</div>';
                    addonsSection.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Error loading add-ons:', error);
            document.getElementById('addons-section').style.display = 'none';
        }
        
        // Update total price
        calculateTotalPrice();
    }
    
    function calculateTotalPrice() {
        const select = document.getElementById('package_select');
        const selectedOption = select.options[select.selectedIndex];
        const basePrice = parseFloat(selectedOption.getAttribute('data-price') || 0);
        
        // Custom Addon
        // Custom Addon
        const customAddonSelect = document.getElementById('custom_addon_id');
        const selectedCustomAddon = customAddonSelect ? customAddonSelect.options[customAddonSelect.selectedIndex] : null;
        const customAddonPrice = selectedCustomAddon ? (parseFloat(selectedCustomAddon.getAttribute('data-price')) || 0) : 0;

        let addonsTotal = 0;
        const selectedAddOns = [];
        document.querySelectorAll('.addon-checkbox:checked').forEach(checkbox => {
            const addonId = checkbox.getAttribute('data-addon-id');
            const addonPrice = parseFloat(checkbox.getAttribute('data-addon-price') || 0);
            addonsTotal += addonPrice;
            selectedAddOns.push(addonId);
        });
        
        // Update hidden inputs
        const container = document.getElementById('selected_addons_inputs');
        container.innerHTML = '';
        selectedAddOns.forEach(addonId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_add_ons[]';
            input.value = addonId;
            container.appendChild(input);
        });
        
        const totalPrice = basePrice + addonsTotal + customAddonPrice;
        document.getElementById('total_price').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    }
    

    function openBookingDetailModal(bookingId) {
        const modal = document.getElementById('bookingDetailModal');
        const content = document.getElementById('bookingDetailContent');
        
        // Show loading
        content.innerHTML = '<div style="text-align: center; padding: 40px;"><p>Memuat data...</p></div>';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        // Get booking data from the bookings array
        const bookings = @json($bookings);
        const booking = bookings.find(b => b.id === bookingId);
        
        if (booking) {
            renderBookingDetail(booking);
        } else {
            content.innerHTML = '<div style="padding: 20px;"><p>Data booking tidak ditemukan.</p></div>';
        }
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
                    <div class="profile-label">Paket</div>
                    <div class="profile-value">${booking.package.name}</div>
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
                </div>
                ` : `
                <div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
                    <button type="button" class="btn-modal btn-modal-primary" onclick="openPaymentModal(${booking.id}, ${booking.total_price})">Bayar Sekarang</button>
                </div>
                `}
            </div>
        `;

        content.innerHTML = html;
    }

    function closeBookingDetailModal() {
        document.getElementById('bookingDetailModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    // Close modal when clicking outside
    document.getElementById('profileModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProfileModal();
        }
    });

    @if($packages->count() > 0)
    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });
    @endif

    document.getElementById('bookingDetailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingDetailModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProfileModal();
            @if($packages->count() > 0)
            closeBookingModal();
            closePackagesModal();
            @endif
            closeBookingDetailModal();
        }
    });

    // Close packages modal when clicking outside
    document.getElementById('packagesModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
    closePackagesModal();
        }
    });

    // Payment Modal Functions
    function openPaymentModal(bookingId, amount) {
        document.getElementById('payment_booking_id').value = bookingId;
        document.querySelector('.payment-amount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        document.getElementById('bookingDetailModal').classList.remove('show');
        document.getElementById('paymentModal').classList.add('show');
        document.body.style.overflow = 'hidden';
        // Reset form
        document.getElementById('paymentForm').reset();
        // Clear errors
        document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.remove('show');
        document.body.style.overflow = 'auto';
        document.getElementById('paymentForm').reset();
        document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    }

    // Handle payment form submit
    document.getElementById('paymentForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const bookingId = document.getElementById('payment_booking_id').value;
        const submitBtn = document.getElementById('payment_submit_btn');
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';

        const formData = new FormData(this);

        try {
            const response = await fetch(`/payment/${bookingId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Redirect to invoice URL
                window.location.href = data.invoice_url;
            } else {
                alert(data.message || 'Terjadi kesalahan saat membuat pembayaran');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Bayar Sekarang';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Bayar Sekarang';
        }
    });

    // Close payment modal when clicking outside
    document.getElementById('paymentModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentModal();
        }
    });

    // Close payment modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePaymentModal();
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
