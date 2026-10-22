document.addEventListener('DOMContentLoaded', function() {
    const timelineContainer = document.querySelector('.timeline__items');
    const timelineItems = document.querySelectorAll('.timeline__item');
    const prevButton = document.querySelector('.timeline-nav-button--prev');
    const nextButton = document.querySelector('.timeline-nav-button--next');
    let currentIndex = 0;
    const totalItems = timelineItems.length;

    // Calcula a largura total da timeline
    const timelineWidth = Array.from(timelineItems).reduce((width, item) => width + item.offsetWidth, 0);
    timelineContainer.style.width = `${timelineWidth}px`;

    function updateTimeline() {
        const containerWidth = document.querySelector('.timeline__wrap').offsetWidth;
        const itemWidth = timelineItems[0].offsetWidth;
        const visibleItems = Math.floor(containerWidth / itemWidth);
        const maxIndex = totalItems - visibleItems;

        // Limita o currentIndex para não ultrapassar o máximo permitido
        currentIndex = Math.max(0, Math.min(currentIndex, maxIndex));

        const translateX = -currentIndex * itemWidth;
        timelineContainer.style.transform = `translate3d(${translateX}px, 0, 0)`;

        // Atualiza o estado dos botões
        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex >= maxIndex;
    }

    function showPrevious() {
        if (currentIndex > 0) {
            currentIndex--;
            updateTimeline();
        }
    }

    function showNext() {
        const containerWidth = document.querySelector('.timeline__wrap').offsetWidth;
        const itemWidth = timelineItems[0].offsetWidth;
        const visibleItems = Math.floor(containerWidth / itemWidth);
        const maxIndex = totalItems - visibleItems;

        if (currentIndex < maxIndex) {
            currentIndex++;
            updateTimeline();
        }
    }

    prevButton.addEventListener('click', showPrevious);
    nextButton.addEventListener('click', showNext);

    // Inicializar
    updateTimeline();

    // Ajusta a timeline quando a janela é redimensionada
    window.addEventListener('resize', updateTimeline);
});