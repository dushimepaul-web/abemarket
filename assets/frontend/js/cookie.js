// cookie.js - Version avec animation smooth
(function() {
    'use strict';
    
    const COOKIE_KEY = 'abemarket_cookie_consent';
    const COOKIE_EXPIRY_DAYS = 365;
    
    function setCookiePreference(choice) {
        localStorage.setItem(COOKIE_KEY, choice);
        localStorage.setItem(COOKIE_KEY + '_date', new Date().toISOString());
    }
    
    function getCookiePreference() {
        return localStorage.getItem(COOKIE_KEY);
    }
    
    function hideCookieBarWithAnimation(cookieBar) {
        if (!cookieBar) return;
        
        // Ajouter la classe d'animation de disparition
        cookieBar.classList.add('hide');
        
        // Attendre la fin de l'animation avant de masquer complètement
        setTimeout(() => {
            cookieBar.style.display = 'none';
            cookieBar.classList.remove('hide');
        }, 300);
    }
    
    function initCookieBar() {
        const cookieBar = document.querySelector('.cookie-bar-section, .cookie-bar-box1, .cookie-bar-box2');
        const acceptBtn = document.getElementById('acceptCookieBtn');
        const declineBtn = document.getElementById('declineCookieBtn');
        
        if (!cookieBar) return;
        
        // Vérifier si déjà consenti
        if (getCookiePreference()) {
            cookieBar.style.display = 'none';
            return;
        }
        
        // Afficher la bannière
        cookieBar.style.display = 'block';
        
        // Accepter
        if (acceptBtn) {
            acceptBtn.addEventListener('click', () => {
                setCookiePreference('accepted');
                hideCookieBarWithAnimation(cookieBar);
                document.dispatchEvent(new CustomEvent('cookiesAccepted'));
            });
        }
        
        // Refuser
        if (declineBtn) {
            declineBtn.addEventListener('click', () => {
                setCookiePreference('declined');
                hideCookieBarWithAnimation(cookieBar);
                document.dispatchEvent(new CustomEvent('cookiesDeclined'));
            });
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCookieBar);
    } else {
        initCookieBar();
    }
})();