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

    // --- 1. VALIDACIÓN DE FORMULARIOS (RNF-US-03) ---
    // Buscamos todos los formularios que tengan la clase 'needs-validation'
    var forms = document.querySelectorAll('.needs-validation');

    // Recorremos cada formulario encontrado
    forms.forEach(function (form) {

        // Escuchamos el evento 'submit' (cuando el usuario hace clic en Enviar)
        form.addEventListener('submit', function (evento) {

            // checkValidity() es una función nativa del navegador que
            // revisa todos los campos con el atributo 'required'
            // Devuelve false si algún campo está vacío o inválido
            if (!form.checkValidity()) {

                // Cancelamos el envío del formulario
                evento.preventDefault();
                evento.stopPropagation();
            }

            // Bootstrap usa la clase 'was-validated' para mostrar
            // los mensajes de error y resaltar los campos en rojo
            form.classList.add('was-validated');
        });
    });


    // --- 2. CUPOS EN TIEMPO REAL (RNF-AF-03) ---
    // Buscamos el elemento que muestra los cupos disponibles
    var cuposEl = document.querySelector('[data-cupos]');

    if (cuposEl) {
        // Leemos los datos del atributo HTML data-cupos y data-capacity
        var cupos    = parseInt(cuposEl.dataset.cupos)    || 0;
        var capacity = parseInt(cuposEl.dataset.capacity) || 1;

        // Quitamos todas las clases de color anteriores
        cuposEl.classList.remove('cupos-mucho', 'cupos-pocos', 'cupos-ninguno');

        // Añadimos la clase correcta según la disponibilidad
        if (cupos <= 0) {
            // Sin cupos: clase roja
            cuposEl.classList.add('cupos-ninguno');
        } else if (cupos / capacity < 0.3) {
            // Menos del 30% disponible: clase amarilla (alerta)
            cuposEl.classList.add('cupos-pocos');
        } else {
            // Muchos cupos: clase verde
            cuposEl.classList.add('cupos-mucho');
        }
    }


    // --- 3. AUTO-CERRAR ALERTAS DESPUÉS DE 4 SEGUNDOS ---
    // Las alertas de éxito desaparecen solas para no molestar
    var alertas = document.querySelectorAll('.alert-success, .alert-info');

    alertas.forEach(function (alerta) {

        // setTimeout ejecuta la función después de 4000ms = 4 segundos
        setTimeout(function () {

            // Hacemos la alerta transparente con una transición suave
            alerta.style.transition = 'opacity 0.5s ease';
            alerta.style.opacity = '0';

            // Después de que termine la animación (0.5s), la removemos del DOM
            setTimeout(function () {
                alerta.remove();
            }, 500);

        }, 4000);
    });


    // --- 4. CONFIRMAR ELIMINACIÓN (RF-07, RF-18) ---
    // Buscamos todos los botones o formularios con data-confirm
    var deleteButtons = document.querySelectorAll('[data-confirm]');

    deleteButtons.forEach(function (btn) {

        btn.addEventListener('click', function (e) {

            // Leemos el mensaje de confirmación del atributo data-confirm
            var mensaje = this.dataset.confirm || '¿Estás seguro? Esta acción no se puede deshacer.';

            // confirm() abre un diálogo nativo del navegador
            // Si el usuario hace clic en "Cancelar", prevenimos la acción
            if (!confirm(mensaje)) {
                e.preventDefault();
            }
        });
    });


    // --- 5. LAZY LOADING DE IMÁGENES (RNF-ED-02) ---
    // Solo cargamos imágenes cuando el usuario las va a ver
    if ('IntersectionObserver' in window) {

        var imagenes = document.querySelectorAll('img[data-src]');

        var observer = new IntersectionObserver(function (entries) {

            entries.forEach(function (entry) {

                if (entry.isIntersecting) {
                    // El elemento es visible: cargamos la imagen real
                    var img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });

        imagenes.forEach(function (img) {
            observer.observe(img);
        });
    }

}); // Fin de DOMContentLoaded


// --- FUNCIÓN GLOBAL: Confirmar acción peligrosa ---
// Se puede llamar desde cualquier formulario con onsubmit="return confirmar()"
function confirmar(mensaje) {
    mensaje = mensaje || '¿Estás seguro? Esta acción no se puede deshacer.';
    return confirm(mensaje);
}
