/* ===== FoodShop App JS ===== */

// ---- TOAST NOTIFICATION ----
function showToast(message, type) {
    type = type || 'success';
    var existing = document.querySelectorAll('.toast');
    existing.forEach(function(t) { t.remove(); });

    var toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(function() { toast.classList.add('toast-hide'); }, 2500);
    setTimeout(function() { toast.remove(); }, 3000);
}

// ---- AJAX ADD TO CART ----
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.js-add-cart');
    if (!btn) return;
    e.preventDefault();

    var form = btn.closest('form');
    if (!form) return;

    var productId = form.querySelector('[name="product_id"]');
    var qtyInput = form.querySelector('[name="quantity"]');
    if (!productId) return;

    var data = {
        product_id: parseInt(productId.value),
        quantity: qtyInput ? parseInt(qtyInput.value) || 1 : 1
    };

    btn.classList.add('btn-loading');
    btn.disabled = true;

    fetch(APP_URL + '/customer/cart/ajax_add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        btn.classList.remove('btn-loading');
        btn.disabled = false;
        if (res.ok) {
            showToast(res.msg, 'success');
            updateCartBadge(res.cart_count);
            btn.classList.add('btn-added');
            setTimeout(function() { btn.classList.remove('btn-added'); }, 600);
        } else {
            showToast(res.msg || 'Failed to add', 'error');
        }
    })
    .catch(function() {
        btn.classList.remove('btn-loading');
        btn.disabled = false;
        form.submit();
    });
});

function updateCartBadge(count) {
    var badges = document.querySelectorAll('.cart-badge');
    badges.forEach(function(b) {
        b.textContent = count;
        b.style.display = count > 0 ? 'flex' : 'none';
        b.classList.add('badge-bounce');
        setTimeout(function() { b.classList.remove('badge-bounce'); }, 400);
    });
}

// ---- WISHLIST TOGGLE ----
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.js-wishlist');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();

    var productId = btn.dataset.id;
    if (!productId) return;

    fetch(APP_URL + '/customer/wishlist/toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: parseInt(productId) })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.ok) {
            if (res.action === 'added') {
                btn.classList.add('wish-active');
                showToast('Added to wishlist!', 'success');
            } else {
                btn.classList.remove('wish-active');
                showToast('Removed from wishlist', 'success');
            }
        }
    })
    .catch(function() {});
});

// ---- HERO CAROUSEL ----
(function() {
    var hero = document.querySelector('.hero');
    if (!hero) return;

    var slides = hero.querySelectorAll('.hero-slide');
    var dots = hero.querySelectorAll('.hero-dots span');
    if (slides.length <= 1) return;

    var current = 0;
    var total = slides.length;
    var autoTimer;

    function goTo(index) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (index + total) % total;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    function next() { goTo(current + 1); }

    function startAuto() {
        autoTimer = setInterval(next, 4000);
    }

    function stopAuto() {
        clearInterval(autoTimer);
    }

    dots.forEach(function(dot, i) {
        dot.addEventListener('click', function() {
            stopAuto();
            goTo(i);
            startAuto();
        });
    });

    // Touch swipe support
    var startX = 0;
    var isDragging = false;

    hero.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        isDragging = true;
        stopAuto();
    }, { passive: true });

    hero.addEventListener('touchend', function(e) {
        if (!isDragging) return;
        isDragging = false;
        var diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) next();
            else goTo(current - 1);
        }
        startAuto();
    }, { passive: true });

    startAuto();
})();

// ---- CART REMOVE CONFIRMATION ----
document.addEventListener('click', function(e) {
    var link = e.target.closest('.js-remove-cart');
    if (!link) return;
    e.preventDefault();

    var name = link.dataset.name || 'this item';
    var url = link.href;

    var overlay = document.createElement('div');
    overlay.className = 'confirm-overlay active';
    overlay.innerHTML = '<div class="confirm-box">' +
        '<div class="confirm-icon">🗑️</div>' +
        '<h3>Remove Item?</h3>' +
        '<p>Remove <strong>' + name + '</strong> from your cart?</p>' +
        '<div class="confirm-actions">' +
        '<button class="btn btn-secondary confirm-cancel">Cancel</button>' +
        '<a href="' + url + '" class="btn btn-danger confirm-ok">Remove</a>' +
        '</div></div>';

    document.body.appendChild(overlay);

    overlay.querySelector('.confirm-cancel').addEventListener('click', function() {
        overlay.remove();
    });
    overlay.addEventListener('click', function(ev) {
        if (ev.target === overlay) overlay.remove();
    });
});

// ---- CHECKOUT CONFIRMATION ----
document.addEventListener('submit', function(e) {
    var form = e.target.closest('.js-checkout');
    if (!form) return;
    e.preventDefault();

    if (document.querySelector('.confirm-overlay.active')) return;

    var overlay = document.createElement('div');
    overlay.className = 'confirm-overlay active';
    overlay.innerHTML = '<div class="confirm-box">' +
        '<div class="confirm-icon">📦</div>' +
        '<h3>Place Order?</h3>' +
        '<p>Are you sure you want to place this order?</p>' +
        '<div class="confirm-actions">' +
        '<button type="button" class="btn btn-secondary confirm-cancel">Cancel</button>' +
        '<button type="button" class="btn btn-primary confirm-ok">Place Order</button>' +
        '</div></div>';

    document.body.appendChild(overlay);

    function closeOverlay() {
        if (overlay.parentNode) overlay.remove();
    }

    overlay.querySelector('.confirm-cancel').addEventListener('click', closeOverlay);
    overlay.querySelector('.confirm-ok').addEventListener('click', function() {
        closeOverlay();
        form.submit();
    });
    overlay.addEventListener('click', function(ev) {
        if (ev.target === overlay) closeOverlay();
    });
});

// ---- SORT/FILTER PANEL ----
document.addEventListener('click', function(e) {
    var trigger = e.target.closest('.js-filter-toggle');
    if (trigger) {
        e.preventDefault();
        var panel = document.querySelector('.filter-panel');
        if (panel) panel.classList.toggle('active');
        return;
    }
    var panel = document.querySelector('.filter-panel.active');
    if (panel && !e.target.closest('.filter-panel')) {
        panel.classList.remove('active');
    }
});

// ---- LAZY LOADING IMAGES ----
(function() {
    if ('loading' in HTMLImageElement.prototype) {
        document.querySelectorAll('img[data-src]').forEach(function(img) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        });
    } else {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        document.querySelectorAll('img[data-src]').forEach(function(img) {
            observer.observe(img);
        });
    }
})();

// ---- BACK TO TOP ----
(function() {
    var btn = document.querySelector('.back-to-top');
    if (!btn) return;

    window.addEventListener('scroll', function() {
        if (window.scrollY > 400) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    });

    btn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();

// ---- MODAL ----
function showModal(id) {
    var m = document.getElementById(id);
    if (m) m.classList.add('active');
}

function hideModal(id) {
    var m = document.getElementById(id);
    if (m) m.classList.remove('active');
}
