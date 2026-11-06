// assets/js/app.js
document.addEventListener('DOMContentLoaded', () => {
    
    /* 1. Project Cards Calculator */
    document.querySelectorAll('.project-card').forEach(card => {
        const options = card.querySelectorAll('.p-opt-label');
        const btnPlus = card.querySelector('.plus');
        const btnMinus = card.querySelector('.minus');
        const inputQty = card.querySelector('.val');
        const totalNum = card.querySelector('.total-num');

        if(!options.length) return;

        let currentPrice = parseFloat(card.querySelector('.p-opt-label.active').dataset.price) || 0;
        let qty = 1;

        const updateTotal = () => {
            totalNum.textContent = (currentPrice * qty).toLocaleString('en-US');
        };

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                options.forEach(o => o.classList.remove('active'));
                opt.classList.add('active');
                opt.querySelector('input').checked = true;
                currentPrice = parseFloat(opt.dataset.price);
                updateTotal();
            });
        });

        if(btnPlus) btnPlus.addEventListener('click', () => { qty++; inputQty.value = qty; updateTotal(); });
        if(btnMinus) btnMinus.addEventListener('click', () => { if (qty > 1) { qty--; inputQty.value = qty; updateTotal(); } });
    });

    /* 2. Premium Categories Slider (Arrows) */
    const slider = document.getElementById('catsSlider');
    const slideRight = document.getElementById('slideRight');
    const slideLeft = document.getElementById('slideLeft');

    if (slider && slideRight && slideLeft) {
        slideRight.addEventListener('click', () => {
            slider.scrollBy({ left: 300, behavior: 'smooth' });
        });
        slideLeft.addEventListener('click', () => {
            slider.scrollBy({ left: -300, behavior: 'smooth' });
        });
    }

    /* 3. Cart Functionality */
    window.nourAddToCart = function(item) {
        const CART_KEY = 'nour_cart';
        let cart = [];
        try { cart = JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch(e){}
        cart.push({ ...item, id: Date.now() });
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.textContent = cart.length;
            badge.style.transform = 'scale(1.5)';
            setTimeout(() => badge.style.transform = 'scale(1)', 200);
        }
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'تمت الإضافة',
                text: 'تم إضافة التبرع للسلة بنجاح!',
                showCancelButton: true,
                confirmButtonText: 'الذهاب للسلة',
                cancelButtonText: 'متابعة التصفح',
                confirmButtonColor: '#061B52',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = window.APP_BASE ? window.APP_BASE + '/cart' : '/cart';
                }
            });
        } else {
            alert('تم إضافة التبرع للسلة بنجاح!');
        }
    };

    const cart = JSON.parse(localStorage.getItem('nour_cart')) || [];
    const badge = document.querySelector('.cart-badge');
    if (badge) badge.textContent = cart.length;

    /* 4. Canvas Particle Effect for Swiper Slides */
    const canvases = document.querySelectorAll('.heroCanvas');
    canvases.forEach(canvas => {
        const ctx = canvas.getContext('2d');
        let particles = [];
        
        const resize = () => {
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = canvas.parentElement.clientHeight;
        };
        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 3 + 1.5;
                this.speedY = Math.random() * 1.5 + 0.5;
                this.alpha = Math.random() * 0.6 + 0.2;
            }
            update() {
                this.y -= this.speedY;
                if (this.y < 0) {
                    this.y = canvas.height;
                    this.x = Math.random() * canvas.width;
                }
            }
            draw() {
                ctx.fillStyle = `rgba(255, 255, 255, ${this.alpha})`;
                ctx.shadowBlur = 15;
                ctx.shadowColor = "#ffffff";
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
                ctx.shadowBlur = 0;
            }
        }

        const initParticles = () => {
            particles = [];
            for (let i = 0; i < 60; i++) particles.push(new Particle());
        };

        const animate = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animate);
        };

        initParticles();
        animate();
    });

    /* 5. Swiper Hero Slider Init */
    if (document.querySelector('.heroSwiper')) {
        new Swiper('.heroSwiper', {
            loop: true,
            effect: 'fade',
            speed: 1200,
            parallax: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }
});