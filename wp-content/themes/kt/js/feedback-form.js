(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

/* --------------------------
    Client-Side Validation for hCaptcha (Required)
--------------------------- */
const form = document.getElementById('ktFeedbackForm');

form.addEventListener('submit', function (e) {

    if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
    }

    const captcha = form.querySelector('textarea[name="h-captcha-response"]');

    if (!captcha || !captcha.value) {
        e.preventDefault();
        alert("Please complete the captcha.");
        return;
    }

    form.classList.add('was-validated');
});