// ============================================================
// ARCHIVO: public/js/custom.js
// PROYECTO: CulturaManizales - Sistema de Gestión de Eventos
// TECNOLOGÍA: JavaScript Vanilla (sin frameworks)
// CUMPLE: RNF-US-03 (validar y resaltar campos vacíos)
//         RNF-AF-03 (mostrar cupos en tiempo real)
//         RNF-FI-02 (validaciones en cliente)
// ============================================================

// Esperamos a que el HTML esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {

    // --- 1. VALIDACIÓN DE FORMULARIOS ---
    var forms = document.querySelectorAll('form[novalidate]');

    forms.forEach(function (form) {
        var fields = form.querySelectorAll('input, select, textarea');

        // Guardar qué campos ya tenían error del servidor al cargar
        var serverErrors = new Set();
        fields.forEach(function (field) {
            if (field.classList.contains('is-invalid')) {
                serverErrors.add(field.id || field.name);
            }
        });

        // Al abandonar un campo: validar solo ese campo
        fields.forEach(function (field) {
            field.addEventListener('blur', function () {
                validateField(field);
            });

            // Mientras escribe: si ya estaba en rojo, re-evaluar
            field.addEventListener('input', function () {
                if (field.classList.contains('is-invalid')) {
                    validateField(field);
                }
            });
        });

        // Al enviar: validar todos
        form.addEventListener('submit', function (e) {
            var valid = true;
            fields.forEach(function (field) {
                if (!validateField(field)) {
                    valid = false;
                }
            });
            if (!valid) {
                e.preventDefault();
                e.stopPropagation();
                // Hacer scroll al primer campo inválido
                var first = form.querySelector('.is-invalid');
                if (first) first.focus();
            }
        });
    });

    function validateField(field) {
        // Ignorar campos ocultos o deshabilitados
        if (field.type === 'hidden' || field.disabled) return true;

        if (!field.checkValidity()) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        } else {
            field.classList.remove('is-invalid');
            // Solo agregar is-valid si el campo tiene valor (no marcar vacíos opcionales de verde)
            if (field.value.trim() !== '' || field.required) {
                field.classList.add('is-valid');
            }
            return true;
        }
    }


    // --- 2. CUPOS EN TIEMPO REAL ---
    var cuposEl = document.querySelector('[data-cupos]');
    if (cuposEl) {
        var cupos    = parseInt(cuposEl.dataset.cupos)    || 0;
        var capacity = parseInt(cuposEl.dataset.capacity) || 1;
        cuposEl.classList.remove('cupos-mucho', 'cupos-pocos', 'cupos-ninguno');
        if (cupos <= 0) {
            cuposEl.classList.add('cupos-ninguno');
        } else if (cupos / capacity < 0.3) {
            cuposEl.classList.add('cupos-pocos');
        } else {
            cuposEl.classList.add('cupos-mucho');
        }
    }


    // --- 3. AUTO-CERRAR ALERTAS DESPUÉS DE 4 SEGUNDOS ---
    var alertas = document.querySelectorAll('.alert-success, .alert-info');
    alertas.forEach(function (alerta) {
        setTimeout(function () {
            alerta.style.transition = 'opacity 0.5s ease';
            alerta.style.opacity = '0';
            setTimeout(function () { alerta.remove(); }, 500);
        }, 4000);
    });


    // --- 4. CONFIRMAR ELIMINACIÓN ---
    var deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var mensaje = this.dataset.confirm || '¿Estás seguro? Esta acción no se puede deshacer.';
            if (!confirm(mensaje)) {
                e.preventDefault();
            }
        });
    });


    // --- 5. LAZY LOADING DE IMÁGENES ---
    if ('IntersectionObserver' in window) {
        var imagenes = document.querySelectorAll('img[data-src]');
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        imagenes.forEach(function (img) { observer.observe(img); });
    }

});

function confirmar(mensaje) {
    mensaje = mensaje || '¿Estás seguro? Esta acción no se puede deshacer.';
    return confirm(mensaje);
}
