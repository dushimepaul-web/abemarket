// ==================== DÉFILEMENT HORIZONTAL DU MENU ====================
document.addEventListener('DOMContentLoaded', function() {
    var container = document.getElementById('navScrollContainer');
    var leftBtn = document.querySelector('.nav-scroll-left');
    var rightBtn = document.querySelector('.nav-scroll-right');
    
    if (!container || !leftBtn || !rightBtn) return;
    
    // Vérifier si le défilement est nécessaire
    function checkScroll() {
        var hasScroll = container.scrollWidth > container.clientWidth;
        
        if (hasScroll) {
            leftBtn.style.display = 'flex';
            rightBtn.style.display = 'flex';
            updateButtonsOpacity();
        } else {
            leftBtn.style.display = 'none';
            rightBtn.style.display = 'none';
        }
    }
    
    // Mettre à jour l'opacité des boutons selon la position
    function updateButtonsOpacity() {
        var scrollLeft = container.scrollLeft;
        var maxScrollLeft = container.scrollWidth - container.clientWidth;
        
        // Bouton gauche
        if (scrollLeft <= 5) {
            leftBtn.style.opacity = '0.3';
            leftBtn.style.cursor = 'not-allowed';
        } else {
            leftBtn.style.opacity = '1';
            leftBtn.style.cursor = 'pointer';
        }
        
        // Bouton droit
        if (scrollLeft + container.clientWidth >= maxScrollLeft - 5) {
            rightBtn.style.opacity = '0.3';
            rightBtn.style.cursor = 'not-allowed';
        } else {
            rightBtn.style.opacity = '1';
            rightBtn.style.cursor = 'pointer';
        }
    }
    
    // Défilement vers la gauche
    leftBtn.addEventListener('click', function() {
        if (container.scrollLeft > 0) {
            container.scrollBy({ left: -250, behavior: 'smooth' });
        }
    });
    
    // Défilement vers la droite
    rightBtn.addEventListener('click', function() {
        if (container.scrollLeft + container.clientWidth < container.scrollWidth) {
            container.scrollBy({ left: 250, behavior: 'smooth' });
        }
    });
    
    // Événements
    container.addEventListener('scroll', updateButtonsOpacity);
    window.addEventListener('resize', function() {
        checkScroll();
        updateButtonsOpacity();
    });
    
    // Attendre un peu pour que le DOM soit complètement chargé
    setTimeout(function() {
        checkScroll();
    }, 100);
    
    // Re-vérifier après le chargement des polices/images
    window.addEventListener('load', function() {
        checkScroll();
    });
});




// Flash Sale Timer
function startFlashSaleTimer(endTime) {
    var timerElement = document.getElementById('flashSaleTimer');
    if (!timerElement) return;
    
    var hoursEl = document.getElementById('flashHours');
    var minutesEl = document.getElementById('flashMinutes');
    var secondsEl = document.getElementById('flashSeconds');
    
    function updateFlashTimer() {
        var now = new Date().getTime();
        var distance = endTime - now;
        
        if (distance < 0) {
            if (hoursEl) hoursEl.textContent = '00';
            if (minutesEl) minutesEl.textContent = '00';
            if (secondsEl) secondsEl.textContent = '00';
            return;
        }
        
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
        if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
        if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
    }
    
    updateFlashTimer();
    setInterval(updateFlashTimer, 1000);
}

// Démarrer le timer (fin de promotion dans 24h par défaut)
var flashSaleEndTime = new Date();
flashSaleEndTime.setHours(flashSaleEndTime.getHours() + 24);
startFlashSaleTimer(flashSaleEndTime.getTime());






// Initialiser les timers pour les offres flash
document.querySelectorAll('.product-timer').forEach(function(timer) {
    var endTime = parseInt(timer.getAttribute('data-end-time'));
    if (endTime) {
        var daysEl = timer.querySelector('.days');
        var hoursEl = timer.querySelector('.hours');
        var minutesEl = timer.querySelector('.minutes');
        var secondsEl = timer.querySelector('.seconds');
        
        function updateTimer() {
            var now = new Date().getTime();
            var distance = endTime - now;
            
            if (distance < 0) {
                timer.style.display = 'none';
                return;
            }
            
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            if (daysEl) daysEl.textContent = days.toString().padStart(2, '0');
            if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
            if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
            if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    }
});

// Initialiser les swipers pour les produits en offre
document.querySelectorAll('[class^="swiper-main-"]').forEach(function(swiperMain, idx) {
    var mainId = 'swiper-main-' + idx;
    var thumbId = 'swiper-thumbnail-' + idx;
    
    if (document.querySelector('.' + swiperMain.className)) {
        // Le swiper sera initialisé par custom-swiper.js
        // Cette fonction est laissée pour référence
    }
});