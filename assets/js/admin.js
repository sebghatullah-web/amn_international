/**
 * AMN International - Admin JavaScript
 * Handles AJAX forms, CSRF tokens, file uploads, and UI interactions.
 */

(function () {
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        document.addEventListener('ajaxBeforeSend', (e) => {
            e.detail.xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        });
    }

    document.querySelectorAll('form[data-ajax]').forEach((form) => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);
            const url = form.getAttribute('action');
            const method = form.getAttribute('method') || 'POST';

            showSubmitIndicator(form);

            fetch(url, {
                method: method,
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((err) => { throw err; });
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        showAlert('success', data.message || 'Saved successfully.');
                        setTimeout(() => {
                            window.location.href = data.redirect || window.location.href;
                        }, 600);
                    } else {
                        showAlert('danger', data.message || 'Something went wrong.');
                        if (data.errors) {
                            displayErrors(form, data.errors);
                        }
                    }
                })
                .catch((err) => {
                    showAlert('danger', err.message || 'Network or server error.');
                    console.error(err);
                });
        });
    });

    function showSubmitIndicator(form) {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
        }
    }

    function displayErrors(form, errors) {
        const alertBox = form.querySelector('.alert-danger');
        if (!alertBox) {
            const div = document.createElement('div');
            div.className = 'alert alert-danger alert-dismissible fade show mt-2';
            div.innerHTML = '<strong>Please correct the following:</strong><ul></ul>';
            form.prepend(div);
            alertBox = div;
        }
        const list = alertBox.querySelector('ul');
        errors = errors || {};
        let html = '';
        Object.keys(errors).forEach((field) => {
            const label = document.querySelector(`[name="${field}"]`)?.closest('label') || '';
            html += `<li>${errors[field]}</li>`;
        });
        list.innerHTML = html;
        alertBox.classList.remove('fade');
        alertBox.style.display = 'block';
        const firstErrorField = document.querySelector(`[name="${Object.keys(errors)[0]}"]`);
        if (firstErrorField) {
            setTimeout(() => firstErrorField.focus(), 300);
        }
    }

    function showAlert(type, message) {
        const existing = document.querySelector('.alert-ajax');
        if (existing) existing.remove();
        const div = document.createElement('div');
        div.className = `alert alert-${type} alert-dismissible fade show alert-ajax`;
        div.setAttribute('role', 'alert');
        div.innerHTML = `${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(div);
        const bsAlert = new bootstrap.Alert(div);
        setTimeout(() => bsAlert.close(), 4000);
    }

    document.addEventListener('change', (e) => {
        if (e.target.id === 'upload_image_1' || e.target.id === 'upload_image_2' || e.target.id === 'upload_image_3') {
            const preview = document.getElementById('preview_' + e.target.id.replace('upload_image_', ''));
            if (preview && e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = (ev) => { preview.src = ev.target.result; preview.classList.remove('d-none'); };
                reader.readAsDataURL(e.target.files[0]);
            }
        }
    });
})();

