/**
 * AMN International — public site scripts.
 * Newsletter AJAX (product filter / forms have inline page scripts).
 */
(function () {
    'use strict';

    function initNewsletter() {
        var forms = document.querySelectorAll('form[data-ajax="newsletter"]');
        forms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var input = form.querySelector('input[name="email"]');
                var feedback = form.parentElement.querySelector('.form-feedback');
                var btn = form.querySelector('button[type="submit"]');
                var original = btn.innerHTML;
                var email = (input.value || '').trim();

                if (!feedback) {
                    feedback = document.createElement('p');
                    feedback.className = 'form-feedback small mt-2 mb-0';
                    form.insertAdjacentElement('afterend', feedback);
                }

                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    feedback.textContent = 'Please enter a valid email address.';
                    feedback.style.color = '#b42318';
                    input.focus();
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = 'Subscribing…';
                feedback.textContent = '';
                feedback.style.color = '';

                var fd = new FormData();
                fd.append('email', email);
                fd.append('_token', (form.querySelector('input[name="_token"]') || {}).value || '');

                fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: fd
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        btn.disabled = false;
                        btn.innerHTML = original;
                        if (data.success) {
                            feedback.textContent = data.message;
                            feedback.style.color = '#175237';
                            form.reset();
                        } else {
                            feedback.textContent = data.message || 'Subscription failed. Please try again.';
                            feedback.style.color = '#b42318';
                        }
                    })
                    .catch(function () {
                        btn.disabled = false;
                        btn.innerHTML = original;
                        feedback.textContent = 'Network error. Please try again.';
                        feedback.style.color = '#b42318';
                    });
            });
        });
    }

    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var id = a.getAttribute('href');
                if (id.length > 1) {
                    var target = document.querySelector(id);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initNewsletter();
        initSmoothScroll();
    });
})();
