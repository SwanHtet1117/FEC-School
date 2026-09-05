<script>
        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const topBar = document.querySelector('.top-bar');
            const nav = document.querySelector('.main-nav');
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                topBar.classList.remove('bar-hidden');
                nav.classList.remove('nav-hidden');
            } else if (currentScroll > lastScroll) {
                topBar.classList.add('bar-hidden');
                nav.classList.add('nav-hidden');
            } else if (currentScroll < lastScroll) {
                topBar.classList.remove('bar-hidden');
                nav.classList.remove('nav-hidden');
            }
            lastScroll = currentScroll;
        });
    </script>