p<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery - FEC International School</title>
    <meta name="description" content="Browse through our photo gallery showcasing school events, activities, and campus life at FEC International School.">
    <meta name="keywords" content="FEC International School, Photo Gallery, School Events, Campus Life">
    <link rel="icon" type="image/png" href="{{ asset('image/logo_01.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/animations.js') }}" defer></script>
    <style>
        .gallery-modal {
                    display: none;
                    position: fixed;
                    inset: 0;
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
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    width: 90vw;
                    max-height: calc(100vh - 32px);
                    padding-top: 48px;
                    box-sizing: border-box;
                }
                .gallery-modal-content img {
                    display: block;
                    width: auto;
                    max-width: 100%;
                    max-height: calc(100vh - 180px);
                    border-radius: var(--radius-md);
                    object-fit: contain;
                }
                .gallery-modal-caption {
                    flex: 0 0 auto;
                    max-width: 100%;
                    margin: 12px 0 0;
                    color: #FFFFFF;
                    font-size: 18px;
                    font-weight: 700;
                    text-align: center;
                    overflow-wrap: anywhere;
                }
                .gallery-placeholder.has-photo {
                    padding: 0;
                    border: 0;
                    background: #0f132a;
                }
                .gallery-photo {
                    position: absolute;
                    inset: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                .gallery-placeholder.has-photo .gallery-item-info,
                .gallery-placeholder.has-photo .gallery-zoom-btn {
                    z-index: 1;
                }
                .gallery-modal-close {
                    position: absolute;
                    top: 0;
                    right: 0;
                    z-index: 1;
                    display: grid;
                    width: 40px;
                    height: 40px;
                    place-items: center;
                    border-radius: 50%;
                    background: rgba(0, 0, 0, 0.72);
                    color: #FFFFFF;
                    font-size: 32px;
                    line-height: 1;
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

        <section class="news-events-hero fade-in-up">
            <h1 class="page-title">Photo Gallery</h1>
            <p class="page-subtitle">Capturing Moments at FEC International School</p>
        </section>

        <section class="content-section">
            <div class="section-container">                    
                <div class="gallery-intro fade-in-up">
                    <p>Welcome to our photo gallery, where we showcase the vibrant life and memorable moments at FEC International School. From academic achievements to cultural celebrations, sports events to community activities, explore the rich experiences that define our school community.</p>
                </div>
            </div>
        </section>

        <section class="content-section light-bg">
                <div class="section-container">
                    <h2 class="section-title fade-in-up">School Events</h2>
                    <div class="gallery-grid fade-in-up">
                        <div class="gallery-item">
                            <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="School Gathering" data-views="1,234">
                                <img class="gallery-photo" src="{{ asset('image/all_student_activity_01.jpg') }}" alt="Students gathered outdoors at school">
                                <div class="gallery-item-info">
                                    <span class="gallery-item-title">School Gathering</span>
                                    <span class="gallery-item-views"><i class="fas fa-eye"></i> 1,234</span>
                                </div>
                                <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                            </div>
                        </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="Student Project Showcase" data-views="987">
                        <img class="gallery-photo" src="{{ asset('image/early_student_activity_01.jpg') }}" alt="Early Years students presenting a classroom project">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Student Project Showcase</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 987</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="Student Activity" data-views="2,156">
                        <img class="gallery-photo" src="{{ asset('image/all_student_activity_02.jpg') }}" alt="Students taking part in a classroom activity">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Student Activity</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 2,156</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="School Assembly" data-views="3,421">
                        <img class="gallery-photo" src="{{ asset('image/secondary_student_activity_02.jpg') }}" alt="Secondary students gathered for a school event">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">School Assembly</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 3,421</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>            
        </section>

    <section class="content-section">
        <div class="section-container">
            <h2 class="section-title fade-in-up">Campus Life</h2>
            <div class="gallery-grid fade-in-up">
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="FEC School Building" data-views="856">
                        <img class="gallery-photo" src="{{ asset('image/fec_building_01.jpg') }}" alt="Exterior view of the FEC school building">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">FEC School Building</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 856</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="Campus Grounds" data-views="743">
                        <img class="gallery-photo" src="{{ asset('image/fec_building_02.jpg') }}" alt="FEC campus grounds and school building">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Campus Grounds</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 743</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="School Campus" data-views="1,102">
                        <img class="gallery-photo" src="{{ asset('image/fec_building.jpg') }}" alt="FEC International School campus">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">School Campus</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 1,102</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="FEC Campus Overview" data-views="634">
                        <img class="gallery-photo" src="{{ asset('image/fec_wallpaper.jpg') }}" alt="Illustrated overview of the FEC campus and school community">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">FEC Campus Overview</span>
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
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="Classroom Learning" data-views="1,567">
                        <img class="gallery-photo" src="{{ asset('image/secondary_student_activity_01.jpg') }}" alt="Secondary students working in their classroom">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Classroom Learning</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 1,567</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="Group Study Session" data-views="923">
                        <img class="gallery-photo" src="{{ asset('image/primary_student_activity_01.jpg') }}" alt="Primary students studying together in class">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Group Study Session</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 923</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="Early Years Classroom" data-views="1,089">
                        <img class="gallery-photo" src="{{ asset('image/early_student_activity_04.jpg') }}" alt="Early Years students learning with their teacher">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">Early Years Classroom</span>
                            <span class="gallery-item-views"><i class="fas fa-eye"></i> 1,089</span>
                        </div>
                        <div class="gallery-zoom-btn"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder has-photo" onclick="openModal(this)" data-title="GED Student Activity" data-views="756">
                        <img class="gallery-photo" src="{{ asset('image/ged_student_activity_01.jpg') }}" alt="GED students participating in a school activity">
                        <div class="gallery-item-info">
                            <span class="gallery-item-title">GED Student Activity</span>
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
            <img id="modalImage" src="" alt="">
            <p class="gallery-modal-caption" id="modalTitle"></p>
        </div>
    </div>

    <script>
        function openModal(element) {
            const modal = document.getElementById('galleryModal');
            const title = element.getAttribute('data-title');
            const image = element.querySelector('.gallery-photo');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = image.src;
            modalImage.alt = image.alt;
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
