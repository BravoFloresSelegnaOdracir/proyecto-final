function showContent(sectionId) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => section.style.display = 'none');

    const welcomeImage = document.getElementById('welcomeImage');
    if (welcomeImage) {
        welcomeImage.style.display = (sectionId === 'close') ? 'block' : 'none';
    }

    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
    }
}

