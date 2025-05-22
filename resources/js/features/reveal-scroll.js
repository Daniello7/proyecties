if (document.querySelectorAll('.reveal-scroll').length > 0) {

    const revealElements = document.querySelectorAll('.reveal-scroll');

    function percentReveal() {
        const windowHeight = window.innerHeight;

        revealElements.forEach(elem => {
            const rect = elem.getBoundingClientRect();
            const visible = windowHeight - rect.top > 0 ? windowHeight - rect.top : 0;
            const percentVisible = visible / rect.height > 1 ? 1 : visible / rect.height;
            const opacity = 0.4 + (0.6 * percentVisible);
            const scale = 0.4 + (0.6 * percentVisible);

            elem.style.opacity = opacity;
            elem.style.transform = `scale(${scale})`;
        })
    }

    window.addEventListener('load', percentReveal);
    window.addEventListener('scroll', percentReveal);
    window.addEventListener('resize', percentReveal);
}
