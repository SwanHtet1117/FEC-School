<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery - FEC Private School</title>
    <meta name="description" content="Browse through our photo gallery showcasing school events, activities, and campus life at FEC Private School.">
    <meta name="keywords" content="FEC Private School, Photo Gallery, School Events, Campus Life">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
    <style>
        .gallery-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .gallery-modal.active {
            display: flex;
        }
        .gallery-modal-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }
        .gallery-modal-content img {
            max-width: 100%;
            max-height: 90vh;
            border-radius: var(--radius-md);
        }
        .gallery-modal-close {
            position: absolute;
            top: -40px;
            right: 0;
            color: #FFFFFF;
            font-size: 32px;
            cursor: pointer;
            transition: var(--transition);
        }
        .gallery-modal-close:hover {
            color: var(--accent-gold);
        }
    </style>
</head>
<body>
    
    @include('partials.header')

    <section class="content-section">
        <div class="section-container">
            <div class="page-header fade-in-up">
                <h1 class="page-title">Photo Gallery</h1>
                <p class="page-subtitle">Capturing Moments at FEC Private School</p>
            </div>
            <div class="gallery-intro fade-in-up">
                <p>Welcome to our photo gallery, where we showcase the vibrant life and memorable moments at FEC Private School. From academic achievements to cultural celebrations, sports events to community activities, explore the rich experiences that define our school community.</p>
            </div>
        </div>
    </section>

    <section class="content-section light-bg">
        <div class="section-container">
            <h2 class="section-title fade-in-up">School Events</h2>
            <div class="gallery-grid fade-in-up">
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Annual Sports Day 2024" data-views="1,234">
                        <i class="fas fa-image"></i>
                        <span>Event Photo 1</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Annual Sports Day 2024</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 1,234</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Science Fair Exhibition" data-views="987">
                        <i class="fas fa-image"></i>
                        <span>Event Photo 2</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Science Fair Exhibition</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 987</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Cultural Festival" data-views="2,156">
                        <i class="fas fa-image"></i>
                        <span>Event Photo 3</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Cultural Festival</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 2,156</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Graduation Ceremony" data-views="3,421">
                        <i class="fas fa-image"></i>
                        <span>Event Photo 4</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Graduation Ceremony</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 3,421</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Campus Life</h2>
            <div class="gallery-grid fade-in-up">
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Modern Library" data-views="856">
                        <i class="fas fa-image"></i>
                        <span>Campus Photo 1</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Modern Library</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 856</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Science Laboratory" data-views="743">
                        <i class="fas fa-image"></i>
                        <span>Campus Photo 2</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Science Laboratory</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 743</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Sports Complex" data-views="1,102">
                        <i class="fas fa-image"></i>
                        <span>Campus Photo 3</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Sports Complex</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 1,102</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Art Studio" data-views="634">
                        <i class="fas fa-image"></i>
                        <span>Campus Photo 4</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Art Studio</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 634</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section light-bg">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Academic Activities</h2>
            <div class="gallery-grid fade-in-up">
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Classroom Learning" data-views="1,567">
                        <i class="fas fa-image"></i>
                        <span>Academic Photo 1</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Classroom Learning</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 1,567</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Group Study Session" data-views="923">
                        <i class="fas fa-image"></i>
                        <span>Academic Photo 2</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Group Study Session</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 923</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Computer Lab" data-views="1,089">
                        <i class="fas fa-image"></i>
                        <span>Academic Photo 3</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Computer Lab</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 1,089</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder" onclick="openModal(this)" data-title="Music Class" data-views="756">
                        <i class="fas fa-image"></i>
                        <span>Academic Photo 4</span>
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Music Class</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 756</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <!-- Gallery Modal -->
    <div class="gallery-modal" id="galleryModal" onclick="closeModal(event)">
        <div class="gallery-modal-content">
            <span class="gallery-modal-close" onclick="closeModal(event)">&times;</span>
            <div class="gallery-placeholder" style="height: 70vh; border: none; background: transparent;">
                <i class="fas fa-image" style="font-size: 80px;"></i>
                <span id="modalTitle" style="font-size: 24px; font-weight: 700;"></span>
            </div>
        </div>
    </div>

    <script>
        function openModal(element) {
            const modal = document.getElementById('galleryModal');
            const title = element.getAttribute('data-title');
            document.getElementById('modalTitle').textContent = title;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(event) {
            if (event.target.classList.contains('gallery-modal') || event.target.classList.contains('gallery-modal-close')) {
                const modal = document.getElementById('galleryModal');
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('galleryModal');
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>
</html>
