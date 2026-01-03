@extends('layouts.app')

@section('title', 'WINWIN Makeup - Makeup Artist Profesional')

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        overflow-x: hidden;
    }

    /* Hero Section dengan Parallax */
    .hero-section {
        position: relative;
        height: 100vh;
        min-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f5f5f5;
        padding-top: 80px;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 120%;
        background-image: url('{{ $muaProfile->hero_image ? asset('storage/' . $muaProfile->hero_image) : 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1920&q=80' }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        will-change: transform;
        z-index: 0;
    }

    .hero-background::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
    }

    .hero-content {
        position: relative;
        z-index: 10;
        text-align: center;
        color: white;
        max-width: 900px;
        padding: 0 20px;
        width: 100%;
    }

    .hero-subtitle {
        font-size: 0.9rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        font-weight: 400;
        margin-bottom: 20px;
        opacity: 0.9;
    }

    .hero-title {
        font-size: clamp(2.5rem, 8vw, 5.5rem);
        font-weight: 300;
        line-height: 1.1;
        margin-bottom: 30px;
        letter-spacing: -2px;
    }

    .hero-title strong {
        font-weight: 600;
    }

    .hero-description {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 50px;
        opacity: 0.95;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        font-weight: 300;
    }

    .hero-cta {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-hero {
        padding: 16px 40px;
        font-size: 0.95rem;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1px solid white;
        background: transparent;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-hero:hover {
        background: white;
        color: #1a1a1a;
    }

    .btn-hero-primary {
        background: white;
        color: #1a1a1a;
        border-color: white;
    }

    .btn-hero-primary:hover {
        background: transparent;
        color: white;
    }

    /* Section Styles */
    .content-section {
        padding: 100px 0;
        position: relative;
    }

    .section-header {
        text-align: center;
        margin-bottom: 80px;
    }

    .section-label {
        font-size: 0.85rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #999;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .section-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 300;
        color: #1a1a1a;
        line-height: 1.2;
        letter-spacing: -1px;
    }

    /* About Section */
    .about-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 60px;
        margin-top: 60px;
    }

    .about-item {
        text-align: center;
    }

    .about-number {
        font-size: 3.5rem;
        font-weight: 300;
        color: #1a1a1a;
        line-height: 1;
        margin-bottom: 15px;
        letter-spacing: -2px;
    }

    .about-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #666;
        font-weight: 500;
    }

    .about-description {
        margin-top: 40px;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    /* Portfolio Section */
    .portfolio-section {
        background: #fafafa;
    }

    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 60px;
    }

    .portfolio-item {
        position: relative;
        overflow: hidden;
        background: #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Variasi ukuran untuk layout yang menarik */
    .portfolio-item:nth-child(1) {
        grid-row: span 2;
        aspect-ratio: auto;
        min-height: 500px;
    }

    .portfolio-item:nth-child(2),
    .portfolio-item:nth-child(3) {
        aspect-ratio: 1;
    }

    .portfolio-item:nth-child(4) {
        grid-column: span 2;
        aspect-ratio: 2 / 1;
    }

    .portfolio-item:nth-child(5),
    .portfolio-item:nth-child(6) {
        aspect-ratio: 1;
    }

    .portfolio-item:nth-child(7) {
        grid-column: span 3;
        aspect-ratio: 3 / 1;
    }

    .portfolio-item:nth-child(8),
    .portfolio-item:nth-child(9) {
        aspect-ratio: 1;
    }

    .portfolio-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .portfolio-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .portfolio-item:hover img {
        transform: scale(1.08);
    }

    .portfolio-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0);
        transition: background 0.3s ease;
        pointer-events: none;
    }

    .portfolio-item:hover::after {
        background: rgba(0, 0, 0, 0.05);
    }

    /* Packages Section */
    .packages-list {
        max-width: 900px;
        margin: 60px auto 0;
    }

    .package-item {
        padding: 40px 0;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 30px;
        transition: padding 0.3s ease;
    }

    .package-item:first-child {
        border-top: 1px solid #e0e0e0;
    }

    .package-item:hover {
        padding-left: 20px;
        padding-right: 20px;
    }

    .package-info {
        flex: 1;
        min-width: 300px;
    }

    .package-name {
        font-size: 1.5rem;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .package-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .package-details {
        display: flex;
        gap: 30px;
        font-size: 0.9rem;
        color: #999;
    }

    .package-price {
        font-size: 1.8rem;
        font-weight: 300;
        color: #1a1a1a;
        letter-spacing: -1px;
        white-space: nowrap;
    }

    .package-cta {
        padding: 12px 30px;
        background: #1a1a1a;
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        border: 1px solid #1a1a1a;
    }

    .package-cta:hover {
        background: transparent;
        color: #1a1a1a;
    }

    .package-cta-outline {
        padding: 12px 30px;
        background: transparent;
        color: #1a1a1a;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        border: 1px solid #1a1a1a;
        cursor: pointer;
    }

    .package-cta-outline:hover {
        background: #1a1a1a;
        color: white;
    }

    /* Contact Section */
    .contact-section {
        background: #1a1a1a;
        color: white;
    }

    .contact-section .section-label {
        color: rgba(255, 255, 255, 0.6);
    }

    .contact-section .section-title {
        color: white;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        margin-top: 60px;
    }

    .contact-item {
        text-align: center;
    }

    .contact-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 15px;
    }

    .contact-value {
        font-size: 1.1rem;
        color: white;
    }

    .contact-value a {
        color: white;
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .contact-value a:hover {
        opacity: 0.7;
    }

    /* Footer */
    footer {
        background: #1a1a1a;
        color: rgba(255, 255, 255, 0.6);
        padding: 40px 0;
        text-align: center;
        font-size: 0.9rem;
    }

    /* Package Detail Modal */
    .package-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        opacity: 0;
        transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .package-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        animation: fadeInModal 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes fadeInModal {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .package-modal-content {
        background: transparent;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 60px;
        max-width: 1000px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        border-radius: 20px;
        border: none;
        box-shadow: none;
        transform: scale(0.85) translateY(30px);
        transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
    }

    .package-modal.show .package-modal-content {
        transform: scale(1) translateY(0);
        opacity: 1;
        animation: slideUpModal 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideUpModal {
        0% {
            transform: scale(0.85) translateY(30px);
            opacity: 0;
        }
        60% {
            transform: scale(1.02) translateY(-5px);
        }
        100% {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .package-modal-close {
        position: absolute;
        top: 25px;
        right: 25px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        font-size: 1.5rem;
        color: white;
        cursor: pointer;
        padding: 0;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .package-modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg) scale(1.1);
    }

    .package-modal-header {
        margin-bottom: 40px;
        padding-bottom: 25px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .package-modal-title {
        font-size: 2.5rem;
        font-weight: 400;
        color: white;
        margin: 0 0 20px 0;
        letter-spacing: -1.5px;
        line-height: 1.2;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .package-modal-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        gap: 20px;
    }

    .package-modal-price {
        font-size: 2rem;
        font-weight: 300;
        color: white;
        letter-spacing: -1px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .package-modal-booking-btn {
        padding: 14px 35px;
        background: transparent;
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        border: 1px solid white;
        border-radius: 0;
        cursor: pointer;
        white-space: nowrap;
    }

    .package-modal-booking-btn:hover {
        background: #1a1a1a;
        color: white;
        border-color: #1a1a1a;
    }

    .package-modal-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin: 40px 0;
    }

    .package-modal-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 12px;
        border: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .package-modal-image:hover {
        transform: scale(1.08) translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }

    .package-modal-details {
        margin-top: 40px;
    }

    .package-modal-detail-item {
        padding: 20px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        transition: padding 0.3s ease;
    }

    .package-modal-detail-item:hover {
        padding-left: 10px;
    }

    .package-modal-detail-item:last-child {
        border-bottom: none;
    }

    .package-modal-detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .package-modal-detail-value {
        font-size: 1.05rem;
        color: white;
        line-height: 1.8;
        font-weight: 300;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            height: calc(100vh - 60px);
            min-height: 500px;
            padding-top: 60px;
        }

        .hero-background {
            background-attachment: scroll;
            height: 100%;
        }

        .hero-content {
            padding: 0 20px;
            width: 100%;
        }

        .hero-subtitle {
            font-size: 0.75rem;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .hero-title {
            font-size: clamp(1.8rem, 8vw, 3rem);
            margin-bottom: 20px;
        }

        .hero-description {
            font-size: 0.95rem;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .hero-cta {
            flex-direction: column;
            gap: 15px;
            width: 100%;
            padding: 0 20px;
        }

        .btn-hero {
            width: 100%;
            text-align: center;
            padding: 14px 30px;
        }

        .content-section {
            padding: 50px 0;
        }

        .section-header {
            margin-bottom: 40px;
            padding: 0 20px;
        }

        .section-label {
            font-size: 0.75rem;
            letter-spacing: 2px;
        }

        .section-title {
            font-size: clamp(1.5rem, 6vw, 2.5rem);
        }

        .about-grid {
            gap: 30px;
            padding: 0 20px;
        }

        .about-number {
            font-size: 2.5rem;
        }

        .about-description {
            padding: 0 20px;
            font-size: 1rem;
        }

        .portfolio-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 40px;
        }

        .portfolio-item:nth-child(1),
        .portfolio-item:nth-child(4),
        .portfolio-item:nth-child(7) {
            grid-column: span 2;
            grid-row: span 1;
            min-height: 250px;
            aspect-ratio: 2 / 1;
        }

        .portfolio-item:nth-child(n) {
            aspect-ratio: 1;
        }

        .packages-list {
            padding: 0 20px;
            margin-top: 40px;
        }

        .package-item {
            flex-direction: column;
            align-items: center;
            padding: 30px 0;
            gap: 20px;
            text-align: center;
        }

        .package-item:hover {
            padding-left: 0;
            padding-right: 0;
        }

        .package-info {
            min-width: 100%;
            width: 100%;
            text-align: center;
        }

        .package-name {
            font-size: 1.3rem;
            text-align: center;
        }

        .package-description {
            font-size: 0.9rem;
            text-align: center;
        }

        .package-details {
            flex-direction: column;
            gap: 10px;
            font-size: 0.85rem;
            justify-content: center;
            align-items: center;
        }

        .package-price {
            width: 100%;
        }

        .package-actions {
            flex-direction: column;
            gap: 12px !important;
        }

        .package-cta,
        .package-cta-outline {
            width: 100%;
            text-align: center;
            padding: 14px 25px;
        }

        .contact-grid {
            gap: 30px;
            padding: 0 20px;
            margin-top: 40px;
        }

        .contact-item {
            padding: 0 10px;
        }

        .package-modal-content {
            padding: 30px 20px;
            width: 95%;
            max-height: 85vh;
        }

        .package-modal-close {
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
            font-size: 1.2rem;
        }

        .package-modal-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
        }

        .package-modal-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .package-modal-price-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .package-modal-price {
            font-size: 1.3rem;
        }

        .package-modal-booking-btn {
            width: 100%;
            text-align: center;
            padding: 12px 25px;
        }

        .package-modal-images {
            grid-template-columns: 1fr;
            gap: 15px;
            margin: 30px 0;
        }

        .package-modal-image {
            height: 200px;
        }

        .package-modal-details {
            margin-top: 30px;
        }

        .package-modal-detail-item {
            padding: 15px 0;
        }

        .package-modal-detail-label {
            font-size: 0.7rem;
        }

        .package-modal-detail-value {
            font-size: 0.95rem;
        }
    }

    @media (max-width: 480px) {
        .hero-section {
            height: calc(100vh - 60px);
            min-height: 450px;
            padding-top: 60px;
        }

        .hero-title {
            font-size: clamp(1.5rem, 8vw, 2.2rem);
        }

        .hero-description {
            font-size: 0.85rem;
        }

        .content-section {
            padding: 40px 0;
        }

        .section-header {
            margin-bottom: 30px;
        }

        .about-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .portfolio-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .package-item {
            padding: 25px 0;
        }

        .package-name {
            font-size: 1.2rem;
        }

        .package-modal-content {
            padding: 25px 15px;
            width: 98%;
        }

        .package-modal-title {
            font-size: 1.3rem;
        }

        .package-modal-price {
            font-size: 1.2rem;
        }

        .package-modal-image {
            height: 180px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Parallax effect untuk hero
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroBackground = document.querySelector('.hero-background');
        if (heroBackground) {
            heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Package Detail Modal
    function openPackageDetailModal(packageId) {
        const modal = document.getElementById('packageDetailModal');
        const content = document.getElementById('packageDetailContent');
        
        content.innerHTML = '<div style="text-align: center; padding: 40px;"><p>Memuat data...</p></div>';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        // Fetch package data
        fetch(`/api/package/${packageId}`)
            .then(response => response.json())
            .then(data => {
                renderPackageDetail(data);
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<div style="padding: 20px;"><p>Terjadi kesalahan saat memuat data.</p></div>';
            });
    }

    function renderPackageDetail(package) {
        const content = document.getElementById('packageDetailContent');
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        const isCustomer = {{ (auth()->check() && auth()->user()->isCustomer()) ? 'true' : 'false' }};
        
        let bookingUrl = isLoggedIn && isCustomer 
            ? `/booking/create?package_id=${package.id}`
            : '/login';
        
        let html = `
            <div class="package-modal-header">
                <h2 class="package-modal-title">${package.name}</h2>
                <div class="package-modal-price-row">
                    <div class="package-modal-price">Rp ${new Intl.NumberFormat('id-ID').format(package.price)}</div>
                    <a href="${bookingUrl}" class="package-modal-booking-btn">Booking</a>
                </div>
            </div>
        `;

        if (package.images && package.images.length > 0) {
            html += '<div class="package-modal-images">';
            package.images.forEach(image => {
                html += `<img src="/storage/${image}" alt="${package.name}" class="package-modal-image">`;
            });
            html += '</div>';
        }

        html += '<div class="package-modal-details">';
        
        if (package.description) {
            html += `
                <div class="package-modal-detail-item">
                    <div class="package-modal-detail-label">Deskripsi</div>
                    <div class="package-modal-detail-value">${package.description}</div>
                </div>
            `;
        }

        html += `
            <div class="package-modal-detail-item">
                <div class="package-modal-detail-label">Durasi</div>
                <div class="package-modal-detail-value">${package.duration} menit</div>
            </div>
        `;

        if (package.includes) {
            html += `
                <div class="package-modal-detail-item">
                    <div class="package-modal-detail-label">Include</div>
                    <div class="package-modal-detail-value">${package.includes}</div>
                </div>
            `;
        }

        html += '</div>';

        content.innerHTML = html;
    }

    function closePackageDetailModal() {
        document.getElementById('packageDetailModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('packageDetailModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePackageDetailModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePackageDetailModal();
        }
    });
</script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background" id="heroBackground"></div>
        <div class="hero-content">
                        <div class="hero-subtitle">WINWIN Makeup</div>
        <h1 class="hero-title">
            <strong>Follow Your</strong><br>
            Wedding Dream
        </h1>
                        <p class="hero-description">
            {{ $muaProfile->bio ?? 'Makeup Artist profesional dengan pengalaman bertahun-tahun dalam berbagai jenis acara. Spesialisasi dalam makeup wedding, prewedding, dan event khusus lainnya.' }}
        </p>
                        <div class="hero-cta">
                            @auth
                                @if(Auth::user()->isCustomer())
                    <a href="{{ route('booking.index') }}" class="btn-hero btn-hero-primary">
                        Booking Saya
                                    </a>
                                @endif
                            @else
                <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                    Daftar Sekarang
                                </a>
                <a href="{{ route('login') }}" class="btn-hero">
                    Login
                                </a>
                            @endauth
        </div>
    </div>
</section>

<!-- About Section -->
@if($muaProfile->specialization || $muaProfile->experience_years)
<section class="content-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Tentang Kami</div>
            <h2 class="section-title">Pengalaman & Komitmen</h2>
                </div>
        
        <div class="about-grid">
            @if($muaProfile->experience_years > 0)
            <div class="about-item">
                <div class="about-number">{{ $muaProfile->experience_years }}+</div>
                <div class="about-label">Tahun Pengalaman</div>
            </div>
            @endif
            
            @if($muaProfile->rating > 0)
            <div class="about-item">
                <div class="about-number">{{ number_format($muaProfile->rating, 1) }}</div>
                <div class="about-label">Rating</div>
            </div>
            @endif
            
            @if($muaProfile->total_reviews > 0)
            <div class="about-item">
                <div class="about-number">{{ $muaProfile->total_reviews }}+</div>
                <div class="about-label">Review</div>
            </div>
            @endif
        </div>

        @if($muaProfile->specialization)
        <div class="about-description">
            {{ $muaProfile->specialization }}
        </div>
        @endif
    </div>
</section>
@endif

<!-- Portfolio Section -->
@if($featuredPortfolios->count() > 0)
<section class="content-section portfolio-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Portfolio</div>
            <h2 class="section-title">Karya Terpilih</h2>
        </div>
        <div class="portfolio-grid">
            @foreach($featuredPortfolios as $portfolio)
                <div class="portfolio-item">
                    <img src="{{ asset('storage/' . $portfolio->image) }}" alt="Portfolio" loading="lazy">
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Packages Section -->
@if($packages->count() > 0)
<section class="content-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Paket</div>
            <h2 class="section-title">Layanan Makeup</h2>
                        </div>
        <div class="packages-list">
            @foreach($packages as $package)
                <div class="package-item">
                    <div class="package-info">
                        <div class="package-name">{{ $package->name }}</div>
                            @if($package->description)
                            <div class="package-description">{{ $package->description }}</div>
                            @endif
                        <div class="package-details">
                            <span><i class="bi bi-clock"></i> {{ $package->duration }} menit</span>
                            @if($package->includes)
                                <span><i class="bi bi-check-circle"></i> {{ $package->includes }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="package-actions" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; width: 100%;">
                        <button type="button" class="package-cta-outline" onclick="openPackageDetailModal({{ $package->id }})">
                            Detail
                        </button>
                            @auth
                                @if(Auth::user()->isCustomer())
                                <a href="{{ route('booking.create', ['package_id' => $package->id]) }}" class="package-cta">
                                    Pesan
                                    </a>
                                @endif
                            @else
                            <a href="{{ route('login') }}" class="package-cta">
                                Booking
                                </a>
                            @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Contact Section -->
@if($muaProfile->whatsapp || $muaProfile->email || $muaProfile->phone || $muaProfile->instagram)
<section class="content-section contact-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Kontak</div>
            <h2 class="section-title">Hubungi Kami</h2>
        </div>
        <div class="contact-grid">
            @if($muaProfile->whatsapp)
            <div class="contact-item">
                <div class="contact-label">WhatsApp</div>
                <div class="contact-value">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $muaProfile->whatsapp) }}" target="_blank">
                        {{ $muaProfile->whatsapp }}
                    </a>
                </div>
            </div>
            @endif
            
            @if($muaProfile->email)
            <div class="contact-item">
                <div class="contact-label">Email</div>
                <div class="contact-value">
                    <a href="mailto:{{ $muaProfile->email }}">{{ $muaProfile->email }}</a>
                </div>
            </div>
            @endif
            
            @if($muaProfile->phone)
            <div class="contact-item">
                <div class="contact-label">Telepon</div>
                <div class="contact-value">
                    <a href="tel:{{ $muaProfile->phone }}">{{ $muaProfile->phone }}</a>
                </div>
            </div>
            @endif
            
            @if($muaProfile->instagram)
            <div class="contact-item">
                <div class="contact-label">Instagram</div>
                <div class="contact-value">
                    <a href="https://instagram.com/{{ str_replace('@', '', $muaProfile->instagram) }}" target="_blank">
                        {{ $muaProfile->instagram }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Package Detail Modal -->
<div id="packageDetailModal" class="package-modal">
    <div class="package-modal-content">
        <button class="package-modal-close" onclick="closePackageDetailModal()">&times;</button>
        <div id="packageDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
@endsection
