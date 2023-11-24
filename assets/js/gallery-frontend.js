const galleryItems = document.querySelectorAll('.jm-gallery .gallery-item');
const modal = document.createElement('div');
modal.classList.add('jm-modal');
document.body.appendChild(modal);

let currentIndex = 0;

galleryItems.forEach((item, index) => {
    const imgSrc = item.querySelector('img').src;
    const imgAlt = item.querySelector('img').alt;

    item.addEventListener('click', () => {
        currentIndex = index;
        showModal(imgSrc, imgAlt);
    });

    // Accessibility: Allow opening the modal using Enter key
    item.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            currentIndex = index;
            showModal(imgSrc, imgAlt);
        }
    });
});

function showModal(imgSrc, imgAlt) {
    const modalContent = `
        <span class="close"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M12 11.293l10.293-10.293.707.707-10.293 10.293 10.293 10.293-.707.707-10.293-10.293-10.293 10.293-.707-.707 10.293-10.293-10.293-10.293.707-.707 10.293 10.293z"/></svg></span>
        <div class="modal-content">
            <img src="${imgSrc}" alt="${imgAlt}" />
            <div class="image-count"></div>
            <a class="prev"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M20 .755l-14.374 11.245 14.374 11.219-.619.781-15.381-12 15.391-12 .609.755z"/></svg></a>
            <a class="next"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M4 .755l14.374 11.245-14.374 11.219.619.781 15.381-12-15.391-12-.609.755z"/></svg></a>
        </div>
    `;

    modal.innerHTML = modalContent;
    modal.style.display = 'block';
    updateImageCount();

    const closeModal = modal.querySelector('.close');
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
        galleryItems[currentIndex].focus();
        document.removeEventListener('keydown', handleKeyboardNavigation);
    });

    const prevBtn = modal.querySelector('.prev');
    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
        const { src, alt } = galleryItems[currentIndex].querySelector('img');
        showModal(src, alt);
        updateImageCount();
    });

    const nextBtn = modal.querySelector('.next');
    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % galleryItems.length;
        const { src, alt } = galleryItems[currentIndex].querySelector('img');
        showModal(src, alt);
        updateImageCount();
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            galleryItems[currentIndex].focus();
            document.removeEventListener('keydown', handleKeyboardNavigation);
        }
    });

    // Function to handle keyboard arrow navigation
    const handleKeyboardNavigation = (e) => {
        if (e.key === 'ArrowLeft') {
            currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
            const { src, alt } = galleryItems[currentIndex].querySelector('img');
            showModal(src, alt);
            updateImageCount();
        } else if (e.key === 'ArrowRight') {
            currentIndex = (currentIndex + 1) % galleryItems.length;
            const { src, alt } = galleryItems[currentIndex].querySelector('img');
            showModal(src, alt);
            updateImageCount();
        } else if (e.key === 'Escape') {
            modal.style.display = 'none';
            galleryItems[currentIndex].focus();
            document.removeEventListener('keydown', handleKeyboardNavigation);
        }
    };

    document.addEventListener('keydown', handleKeyboardNavigation);

    modal.setAttribute('tabindex', '-1');
    modal.focus();
}

function updateImageCount() {
    const countElement = modal.querySelector('.image-count');
    const countText = `${currentIndex + 1}/${galleryItems.length}`;
    countElement.textContent = countText;
}
