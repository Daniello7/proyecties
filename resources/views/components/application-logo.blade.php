@props(['dark' => false])
<svg width="50" height="50" viewBox="0 0 190 190" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <path id="flor" d="M10,20 L20,90 L80,80Z M12,30 L30,88 L68,70Z M15.5,40 L40,83 L54,60Z M21.5,50 L44.5,75 L47,56Z M28,57 L45,68 L38 54Z" fill="url(#flor-gradient)" stroke="#2563eb" stroke-width="0.2" fill-rule="evenodd"/>
        <radialGradient id="flor-gradient" cx="50%" cy="50%" r="50%">
            <stop offset="50%" stop-color="#059669"/>
            <stop offset="100%" stop-color="#2563eb"/>
        </radialGradient>
    </defs>
    <use href="#flor" x="0" y="0"/>
    <use href="#flor" x="0" y="-180" transform="rotate(90)"/>
    <use href="#flor" x="-180" y="0" transform="rotate(-90)"/>
    <g transform="scale(1,-1)" transform-origin="100 100">
        <use href="#flor" x="0" y="20"/>
        <use href="#flor" x="-200" y="0" transform="rotate(-90)"/>
        <use href="#flor" x="20" y="-180" transform="rotate(90)"/>
    </g>
    <g transform="scale(-1,1)" transform-origin="100 100">
        <use href="#flor" x="20" y="0"/>
    </g>
    <g transform="scale(-1,-1)" transform-origin="100 100">
        <use href="#flor" x="20" y="20"/>
    </g>
</svg>
<script>
    const florGradientStops = document.querySelectorAll('#flor-gradient stop')
    const documentHtml = document.documentElement
    let isActiveDarkMode = documentHtml.classList.contains('dark');

    const observer = new MutationObserver((mutationsList) => {
        mutationsList.forEach((mutation) => {
            if (mutation.attributeName === "class") {
                isActiveDarkMode = documentHtml.classList.contains("dark");
                return renderLogoColors();
            }
        });
    });

    function renderLogoColors() {
        if (isActiveDarkMode) {
            florGradientStops[0].setAttribute('stop-color', '#7c3aed')
            florGradientStops[1].setAttribute('stop-color', '#db2777')
        } else {
            florGradientStops[0].setAttribute('stop-color', '#059669')
            florGradientStops[1].setAttribute('stop-color', '#2563eb')
        }
    }

    observer.observe(documentHtml, {attributes: true, attributeFilter: ["class"]});
    renderLogoColors();
</script>