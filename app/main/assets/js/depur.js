document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded and parsed');

    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeButton = document.getElementById('closeModal');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    const thumbnails = document.querySelectorAll('.thumbnail');

    console.log('Modal:', modal);
    console.log('Modal Image:', modalImage);
    console.log('Close Button:', closeButton);
    console.log('Prev Button:', prevButton);
    console.log('Next Button:', nextButton);
    console.log('Thumbnails:', thumbnails);

    if (thumbnails.length === 0) {
        console.error('No thumbnails found. Make sure your images have the "thumbnail" class.');
        return;
    }

    function openModal(index) {
        console.log('Opening modal with image index:', index);
        modal.classList.remove('hidden');
        modalImage.src = thumbnails[index].src;
    }

    function closeModal() {
        console.log('Closing modal');
        modal.classList.add('hidden');
    }

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', () => {
            console.log('Thumbnail clicked:', index);
            openModal(index);
        });
    });

    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    } else {
        console.error('Close button not found');
    }
});