document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.navbar a'); // Select all links within the navigation bar

    links.forEach(link => {
        // If the link path matches the current URL
        if (link.href === window.location.href) {
            link.classList.add('active'); // Active the selected choice

            // Check if the link is inside a dropdown
            const dropdown = link.closest('.dropdown'); // Find the nearest dropdown container
            if (dropdown) {
                const dropdownToggle = dropdown.querySelector('.dropdown-toggle'); // find the dropdown button
                if (dropdownToggle) {
                    dropdownToggle.classList.add('active'); // Active dropdown
                    dropdownToggle.classList.add('show'); // Show dropdown
                    dropdownToggle.setAttribute('aria-expanded', 'true'); // open dropdown
                    const m = document.getElementById("ecDropMenu"); 
                    m.classList.add('show'); // show dropdown menu
                }
            }
        }
    });
});

