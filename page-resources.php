<?php
/**
 * Template Name: Resources Page
 * Description: Displays downloadable resources in a beautiful grid layout
 */

get_header(); ?>

<main class="resources-page">
    <section class="hero-section">
        <div class="container">
            <h1 class="page-title">Resources</h1>
            <p class="page-subtitle">Free guides and tools to support your Choose90 journey</p>
        </div>
    </section>

    <section class="resources-grid-section">
        <div class="container">
            <div class="resources-grid">

                <!-- Digital Detox Guide -->
                <div class="resource-card">
                    <div class="resource-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M16 13H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M16 17H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 9H9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="resource-title">Digital Detox Guide</h3>
                    <p class="resource-description">A comprehensive guide to help you disconnect from digital
                        distractions and reconnect with what truly matters.</p>
                    <div class="resource-meta">
                        <span class="resource-type">PDF Guide</span>
                        <span class="resource-size">~20 KB</span>
                    </div>
                    <a href="/assets/docs/Choose90-Digital-Detox-Guide.pdf" class="btn btn-primary" download>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Download Guide
                    </a>
                </div>

                <!-- Interactive Digital Detox Guide -->
                <div class="resource-card">
                    <div class="resource-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="resource-title">Interactive Digital Detox Guide</h3>
                    <p class="resource-description">Experience the full 7-day journey with progress tracking,
                        interactive elements, and a beautiful reading experience right in your browser.</p>
                    <div class="resource-meta">
                        <span class="resource-type">Interactive Web Guide</span>
                        <span class="resource-size">✨ Enhanced</span>
                    </div>
                    <a href="/digital-detox-guide.html" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M15 3H21V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10 14L21 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Start Interactive Guide
                    </a>

                    <div class="resource-card resource-card-placeholder">
                        <div class="resource-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                                <path d="M12 8V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <path d="M12 16H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <h3 class="resource-title">More Resources Coming Soon</h3>
                        <p class="resource-description">We're working on additional guides and tools to support your
                            journey. Check back soon!</p>
                    </div>

                </div>
            </div>
    </section>
</main>

<style>
    /* Resources Page Specific Styles */
    .resources-page {
        min-height: 60vh;
    }

    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 4rem 0;
        text-align: center;
        color: white;
        margin-bottom: 3rem;
    }

    .page-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        font-family: 'Outfit', sans-serif;
    }

    .page-subtitle {
        font-size: 1.25rem;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
    }

    .resources-grid-section {
        padding: 2rem 0 4rem;
    }

    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .resource-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
    }

    .resource-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(102, 126, 234, 0.2);
        border-color: #667eea;
    }

    .resource-card-placeholder {
        opacity: 0.7;
        border: 2px dashed #ddd;
        background: #f9f9f9;
    }

    .resource-card-placeholder:hover {
        transform: none;
        border-color: #ddd;
    }

    .resource-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: white;
    }

    .resource-card-placeholder .resource-icon {
        background: #ddd;
        color: #888;
    }

    .resource-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
        font-family: 'Outfit', sans-serif;
    }

    .resource-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .resource-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        color: #888;
    }

    .resource-type,
    .resource-size {
        padding: 0.25rem 0.75rem;
        background: #f0f0f0;
        border-radius: 20px;
    }

    .resource-card .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        width: 100%;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .resource-card .btn svg {
        transition: transform 0.3s ease;
    }

    .resource-card .btn:hover svg {
        transform: translateY(2px);
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }

        .resources-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
</style>

<?php get_footer(); ?>