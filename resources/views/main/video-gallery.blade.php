<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Gallery - FEC Private School</title>
    <meta name="description" content="Browse through our video gallery showcasing school events, activities, and campus life at FEC Private School.">
    <meta name="keywords" content="FEC Private School, Video Gallery, School Events, Campus Life">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
    <style>
        .video-modal {
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
        .video-modal.active {
            display: flex;
        }
        .video-modal-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }
        .video-modal-content iframe {
            max-width: 100%;
            max-height: 90vh;
            border-radius: var(--radius-md);
        }
        .video-modal-close {
            position: absolute;
            top: -40px;
            right: 0;
            color: #FFFFFF;
            font-size: 32px;
            cursor: pointer;
            transition: var(--transition);
        }
        .video-modal-close:hover {
            color: var(--accent-gold);
        }
    </style>
</head>
<body>
    
    @include('partials.header')

    <section class="content-section">
        <div class="section-container">
            <div class="page-header fade-in-up">
                <h1 class="page-title">Video Gallery</h1>
                <p class="page-subtitle">Watch Our Stories Unfold</p>
            </div>
            <div class="gallery-intro fade-in-up">
                <p>Welcome to our video gallery, where we bring the vibrant life and memorable moments at FEC Private School to motion. From academic achievements to cultural celebrations, sports events to community activities, watch the rich experiences that define our school community come alive.</p>
            </div>
        </div>
    </section>

    <section class="content-section light-bg">
        <div class="section-container">
            <h2 class="section-title fade-in-up">School Events</h2>
            <div class="gallery-grid fade-in-up">
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Annual Sports Day 2024" data-views="5,678" data-duration="12:34">
                        <i class="fas fa-play-circle"></i>
                        <span>Event Video 1</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Annual Sports Day 2024</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 5,678</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 12:34</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Science Fair Exhibition" data-views="4,321" data-duration="8:45">
                        <i class="fas fa-play-circle"></i>
                        <span>Event Video 2</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Science Fair Exhibition</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 4,321</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 8:45</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Cultural Festival" data-views="7,892" data-duration="15:20">
                        <i class="fas fa-play-circle"></i>
                        <span>Event Video 3</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Cultural Festival</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 7,892</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 15:20</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Graduation Ceremony" data-views="9,456" data-duration="25:10">
                        <i class="fas fa-play-circle"></i>
                        <span>Event Video 4</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Graduation Ceremony</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 9,456</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 25:10</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
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
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Campus Tour" data-views="3,456" data-duration="10:15">
                        <i class="fas fa-play-circle"></i>
                        <span>Campus Video 1</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Campus Tour</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 3,456</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 10:15</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Student Life" data-views="2,789" data-duration="7:30">
                        <i class="fas fa-play-circle"></i>
                        <span>Campus Video 2</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Student Life</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 2,789</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 7:30</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Facilities Overview" data-views="4,123" data-duration="9:45">
                        <i class="fas fa-play-circle"></i>
                        <span>Campus Video 3</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Facilities Overview</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 4,123</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 9:45</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Extracurricular Activities" data-views="3,890" data-duration="11:20">
                        <i class="fas fa-play-circle"></i>
                        <span>Campus Video 4</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Extracurricular Activities</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 3,890</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 11:20</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
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
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Classroom Sessions" data-views="6,234" data-duration="14:25">
                        <i class="fas fa-play-circle"></i>
                        <span>Academic Video 1</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Classroom Sessions</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 6,234</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 14:25</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Science Experiments" data-views="5,123" data-duration="12:10">
                        <i class="fas fa-play-circle"></i>
                        <span>Academic Video 2</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Science Experiments</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 5,123</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 12:10</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Art & Music Classes" data-views="4,567" data-duration="10:50">
                        <i class="fas fa-play-circle"></i>
                        <span>Academic Video 3</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Art & Music Classes</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 4,567</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 10:50</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="video-placeholder" onclick="openVideoModal(this)" data-title="Sports Training" data-views="5,890" data-duration="13:40">
                        <i class="fas fa-play-circle"></i>
                        <span>Academic Video 4</span>
                        <div class="video-item-info">
                            <span class="video-item-title">Sports Training</span>
                            <div class="video-item-meta">
                                <span class="video-item-views"><i class="fas fa-eye"></i> 5,890</span>
                                <span class="video-item-duration"><i class="fas fa-clock"></i> 13:40</span>
                            </div>
                        </div>
                        <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <!-- Video Modal -->
    <div class="video-modal" id="videoModal" onclick="closeVideoModal(event)">
        <div class="video-modal-content">
            <span class="video-modal-close" onclick="closeVideoModal(event)">&times;</span>
            <div class="video-placeholder" style="height: 70vh; border: none; background: transparent;">
                <i class="fas fa-play-circle" style="font-size: 80px;"></i>
                <span id="videoModalTitle" style="font-size: 24px; font-weight: 700;"></span>
            </div>
        </div>
    </div>

    <script>
        function openVideoModal(element) {
            const modal = document.getElementById('videoModal');
            const title = element.getAttribute('data-title');
            document.getElementById('videoModalTitle').textContent = title;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal(event) {
            if (event.target.classList.contains('video-modal') || event.target.classList.contains('video-modal-close')) {
                const modal = document.getElementById('videoModal');
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('videoModal');
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>
</html>
