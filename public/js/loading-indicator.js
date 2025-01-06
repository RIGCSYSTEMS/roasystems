// Mostrar el indicador de carga
function showLoading() {
    document.getElementById('loading-indicator').style.display = 'block';
}

// Ocultar el indicador de carga
function hideLoading() {
    document.getElementById('loading-indicator').style.display = 'none';
}

// Interceptar todas las peticiones AJAX
$(document).ajaxSend(function() {
    showLoading();
});

$(document).ajaxComplete(function() {
    hideLoading();
});

// Para peticiones fetch
const originalFetch = window.fetch;
window.fetch = function() {
    showLoading();
    return originalFetch.apply(this, arguments).then(function(response) {
        hideLoading();
        return response;
    }).catch(function(error) {
        hideLoading();
        throw error;
    });
};