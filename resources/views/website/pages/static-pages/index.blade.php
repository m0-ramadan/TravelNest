@extends('website.layouts.master')

@section('title', 'Home - Luxor and Aswan Travel')


@section('css')

@endsection

@section('content')

@endsection

@section('js')
    <!-- JavaScript -->
    <script src="{{ request()->root() }}/website/js/new/jquery.min.js" type="bbfb53b5999c6c3f61fbade4-text/javascript">
    </script>
    <script src="{{ request()->root() }}/website/js/new/bootstrap.bundle.min.js"
        type="bbfb53b5999c6c3f61fbade4-text/javascript"></script>


    <!-- Custom JavaScript -->
    <script type="bbfb53b5999c6c3f61fbade4-text/javascript">
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });





        // Enhanced card interactions
        document.querySelectorAll('.deal-card, .destination-card, .testimonial-card, .feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Newsletter form handling
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                // Simulate form submission
                alert('Thank you for subscribing! You will receive our latest deals and updates.');
                this.querySelector('input[type="email"]').value = '';
            }
        });

        // Loading optimization
        window.addEventListener('load', () => {
            document.body.style.opacity = '1';
            document.body.style.transition = 'opacity 0.3s ease';
            
            // Lazy load images
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.addEventListener('load', () => {
                    img.style.opacity = '1';
                });
            });
        });

        // Performance optimization for scroll events
        let ticking = false;
        function updateOnScroll() {
            // Update any scroll-dependent animations here
            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateOnScroll);
                ticking = true;
            }
        });

        // Enhanced mobile experience
        if (window.innerWidth < 768) {
            // Reduce animation complexity on mobile
            document.querySelectorAll('.feature-card, .deal-card').forEach(card => {
                card.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
            });
        }



        // WhatsApp button interaction
        document.querySelector('.whatsapp-fixed').addEventListener('click', function() {
            // Optional: Track WhatsApp clicks for analytics
            console.log('WhatsApp contact initiated');
        });
    </script>
@endsection
