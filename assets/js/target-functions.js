// Make functions globally accessible
window.toggleTooltip = function(id, isVisible) {
    var element = document.getElementById(id + '-tooltip');

    if (isVisible) {
        element.classList.remove('d-none');
    } else {
        element.classList.add('d-none');
    }
}

window.toggleModal = function(id, isVisible) {
    var modalElem = document.getElementById(id);
    var backdrop = document.getElementById(id + "-backdrop");

    if (isVisible) {
        modalElem.classList.remove("d-none");
        backdrop.classList.remove("d-none");
    } else {
        modalElem.classList.add("d-none");
        backdrop.classList.add("d-none");
    }
}

window.navigateTo = function(url) {
    window.location.href = url;
}

window.handleMouseEnter = function(id) {
    var card = document.getElementById(id);
    if (card) {
        card.classList.remove('unhover');
        card.classList.add('blinds-effect');
    }
}

window.handleMouseLeave = function(id) {
    var card = document.getElementById(id);
    if (card) {
        card.classList.add('unhover');
    }
}

window.blockWholePage = function(event) {
    try {
        if (event) {
            if (event.target.attributes['disabled']) {
                event.preventDefault();
                return;
            };
        }
    } catch (e) {
        console.error(e)
    }

    var pageLoadingElem = document.getElementById("page-loading-spinner");
    if (pageLoadingElem) {
        pageLoadingElem.style.display = "flex";
    }
}
