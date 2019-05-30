
/*--- Account Profile ---*/

if (profileImages = document.getElementById('profileImages')) {
    var mediaLayer = document.getElementById('mediaLayer');
    var melClose = document.querySelector(".mel-close");
    var imageItems = profileImages.querySelectorAll('.image-item')
    imageItems.forEach((item) => {
        var imageLink = item.querySelector('.image-link');
        var melImage  = mediaLayer.querySelector('.mel-image');
        var melTitle  = mediaLayer.querySelector('.mel-title');
        var melText   = mediaLayer.querySelector('.mel-text');
        imageLink.addEventListener('click', (event) => {
            event.preventDefault();
            document.body.classList.add('no-scrolling');
            mediaLayer.style.display = 'block';
            melImage.src = imageLink.href;
            melTitle.innerText = imageLink.getAttribute('data-title');
            melText.innerText = imageLink.getAttribute('data-text')
                .replace(/\s?(<br\s?\/?>)\s?/g, "\r\n");
        });
    });
    melClose.addEventListener('click', () => {
        mediaLayer.style.display = 'none';
        document.body.classList.remove('no-scrolling');
    });
    document.addEventListener('keydown', (event) => {
        event = event || window.event;
        if (event.keyCode === 27) {
            mediaLayer.style.display = 'none';
            document.body.classList.remove('no-scrolling');
        }
    });
}
