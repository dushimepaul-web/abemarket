/**
 * =====================
 * Wishlist Notify Js - Version avec AJAX et Base de données
 * =====================
 */

document.addEventListener("DOMContentLoaded", () => {
    
    // Base URL pour les appels AJAX
    const BASE_URL = window.location.origin + '/abemarket/';
    
    // État de la wishlist depuis localStorage (fallback)
    let wishlistStates = JSON.parse(localStorage.getItem('wishlistStates')) || {};
    
    // Flag pour éviter les doublons de requêtes
    let isProcessing = false;
    
    // ============================================
    // FONCTIONS PRINCIPALES
    // ============================================
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    function isUserLoggedIn() {
        // Vérifier via un élément DOM ou session
        const userElement = document.querySelector('.user-logged');
        return userElement ? userElement.getAttribute('data-logged') === 'true' : false;
    }
    
    /**
     * Afficher l'alerte de notification
     */
    function showWishlistAlert(productImage, productName, isAdding, productId) {
        const alertBox = document.getElementById('alertBox');
        const alertMessage = document.getElementById('alertMessage');
        const progressBar = document.getElementById('progressBar');
        
        if (!alertBox || !alertMessage) {
            console.warn("⚠️ Alert box missing in DOM");
            return;
        }
        
        const message = isAdding ? 'Produit ajouté à la liste de souhaits' : 'Produit retiré de la liste de souhaits';
        
        alertMessage.innerHTML = `
            <h4>${message}</h4>
            <div class="alert-image">
                <img src="${productImage}" alt="Product" class="img-fluid" onerror="this.src='${BASE_URL}assets/frontend/images/product/placeholder.png'">
                <h5>${productName}</h5>
            </div>
        `;
        
        // Changer le style selon l'action
        alertBox.classList.toggle('added', isAdding);
        alertBox.classList.toggle('removed', !isAdding);
        
        // Animation d'entrée
        alertBox.style.display = 'block';
        alertBox.style.opacity = '0';
        alertBox.style.transform = 'translateY(-20px)';
        
        let opacity = 0;
        let translateY = -20;
        const fadeIn = setInterval(() => {
            if (opacity < 1) {
                opacity += 0.05;
                translateY += 1;
                alertBox.style.opacity = opacity;
                alertBox.style.transform = `translateY(${translateY}px)`;
            } else {
                clearInterval(fadeIn);
            }
        }, 16);
        
        // Barre de progression
        if (progressBar) {
            progressBar.style.animation = 'none';
            progressBar.offsetHeight; // Force reflow
            progressBar.style.animation = 'progressBarAnimation 3s linear forwards';
        }
        
        // Auto fermeture après 3 secondes
        let fadeOutTimeout;
        
        function startFadeOut() {
            if (fadeOutTimeout) clearTimeout(fadeOutTimeout);
            fadeOutTimeout = setTimeout(() => {
                let opacityOut = 1;
                let translateOut = 0;
                const fadeOut = setInterval(() => {
                    if (opacityOut > 0) {
                        opacityOut -= 0.05;
                        translateOut -= 1;
                        alertBox.style.opacity = opacityOut;
                        alertBox.style.transform = `translateY(${translateOut}px)`;
                    } else {
                        clearInterval(fadeOut);
                        alertBox.style.display = 'none';
                    }
                }, 16);
            }, 3000);
        }
        
        startFadeOut();
        
        // Pause au survol
        alertBox.onmouseenter = () => {
            if (fadeOutTimeout) clearTimeout(fadeOutTimeout);
            if (progressBar) progressBar.style.animationPlayState = 'paused';
        };
        
        alertBox.onmouseleave = () => {
            startFadeOut();
            if (progressBar) progressBar.style.animationPlayState = 'running';
        };
    }
    
    /**
     * Mettre à jour le compteur de wishlist dans l'interface
     */
    function updateWishlistCounter(count) {
        const counters = document.querySelectorAll('.wishlist-count, .wishlist_count, .wishlist-counter');
        counters.forEach(counter => {
            if (counter) {
                counter.textContent = count;
            }
        });
        
        // Mettre à jour le localStorage
        localStorage.setItem('wishlist_total_count', count);
    }
    
    /**
     * Rafraîchir l'offcanvas wishlist
     */
    function refreshWishlistOffcanvas() {
        fetch(BASE_URL + 'home/getWishlistOffcanvas')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const wishlistItemsList = document.getElementById('wishlistItemsList');
                    if (wishlistItemsList) {
                        wishlistItemsList.innerHTML = data.html;
                    }
                }
            })
            .catch(error => console.error('Erreur refresh offcanvas:', error));
    }
    
    /**
     * Appel AJAX pour ajouter/supprimer de la wishlist
     */
    function toggleWishlist(productId, isAdding, icon, productImage, productName) {
        if (isProcessing) return;
        isProcessing = true;
        
        const url = isAdding ? BASE_URL + 'home/addToWishlist' : BASE_URL + 'home/removeFromWishlist';
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'icône
                if (isAdding) {
                    icon.classList.add('show');
                } else {
                    icon.classList.remove('show');
                }
                
                // Mettre à jour le compteur
                updateWishlistCounter(data.wishlist_count);
                
                // Rafraîchir l'offcanvas
                refreshWishlistOffcanvas();
                
                // Afficher l'alerte
                showWishlistAlert(productImage, productName, isAdding, productId);
            } else {
                if (data.message === 'Veuillez vous connecter') {
                    // Rediriger vers la page de connexion
                    if (confirm('Veuillez vous connecter pour ajouter des produits à votre liste de souhaits')) {
                        window.location.href = BASE_URL + 'auth/login';
                    }
                } else {
                    console.error('Erreur:', data.message);
                    // Afficher erreur dans l'alerte
                    showWishlistAlert(productImage, productName, false, productId);
                }
            }
        })
        .catch(error => {
            console.error('Erreur AJAX:', error);
        })
        .finally(() => {
            isProcessing = false;
        });
    }
    
    /**
     * Initialiser l'état des icônes wishlist depuis le serveur
     */
    function initWishlistStatesFromServer() {
        fetch(BASE_URL + 'home/getUserWishlistIds')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.wishlist_ids) {
                    const wishlistIds = data.wishlist_ids.map(id => parseInt(id));
                    
                    document.querySelectorAll('.wishlistProduct').forEach((icon, index) => {
                        const productId = parseInt(icon.getAttribute('data-product-id'));
                        const isInWishlist = wishlistIds.includes(productId);
                        
                        if (isInWishlist) {
                            icon.classList.add('show');
                            wishlistStates[`wishlist-${productId}`] = true;
                        } else {
                            icon.classList.remove('show');
                            wishlistStates[`wishlist-${productId}`] = false;
                        }
                    });
                    
                    // Sauvegarder dans localStorage
                    localStorage.setItem('wishlistStates', JSON.stringify(wishlistStates));
                    
                    // Mettre à jour le compteur
                    updateWishlistCounter(data.wishlist_count);
                }
            })
            .catch(error => console.error('Erreur init wishlist:', error));
    }
    
    // ============================================
    // INITIALISATION DES BOUTONS WISHLIST
    // ============================================
    
    function initWishlistButtons() {
        document.querySelectorAll('.wishlistProduct').forEach((icon, index) => {
            // Supprimer l'ancien événement pour éviter les doublons
            const newIcon = icon.cloneNode(true);
            icon.parentNode.replaceChild(newIcon, icon);
            
            const productBox = newIcon.closest('.productMain') || newIcon.closest('.vertical-product-box') || newIcon.closest('.product-box');
            
            let productImageEl = null;
            let productNameEl = null;
            
            if (productBox) {
                productImageEl = productBox.querySelector('.productImage') || productBox.querySelector('.product-image img');
                productNameEl = productBox.querySelector('.productName') || productBox.querySelector('.name');
            }
            
            const productImage = productImageEl ? (productImageEl.src || productImageEl.getAttribute('src')) : '';
            const productName = productNameEl ? productNameEl.textContent.trim() : 'Produit';
            const productId = newIcon.getAttribute('data-product-id');
            
            // Récupérer l'état depuis localStorage
            const storageKey = `wishlist-${productId}`;
            if (wishlistStates[storageKey]) {
                newIcon.classList.add('show');
            }
            
            // Ajouter l'événement click
            newIcon.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const isCurrentlyAdded = newIcon.classList.contains('show');
                
                toggleWishlist(
                    productId, 
                    !isCurrentlyAdded, 
                    newIcon, 
                    productImage, 
                    productName
                );
            });
        });
    }
    
    // ============================================
    // INITIALISATION
    // ============================================
    
    initWishlistButtons();
    initWishlistStatesFromServer();
    
    // Observer pour les produits chargés dynamiquement (Swiper)
    const observer = new MutationObserver(function(mutations) {
        let shouldReinit = false;
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && (node.classList?.contains('swiper-slide') || node.querySelector?.('.wishlistProduct'))) {
                        shouldReinit = true;
                    }
                });
            }
        });
        if (shouldReinit) {
            setTimeout(() => {
                initWishlistButtons();
            }, 100);
        }
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
});