        /**
         * TOC & Reading Progress Functionality
         */

        function scrollToHeading(id) {
            const element = document.getElementById(id);
            if (element) {
                // Offset for fixed headers (adjust as needed)
                const offset = 100;
                const elementPosition = element.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Update URL hash without jumping
                history.pushState(null, null, '#' + id);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Inject IDs into headings for TOC linking
            const articleContent = document.querySelector('.article-content');
            if (articleContent) {
                const headings = articleContent.querySelectorAll('h2, h3, h4, h5, h6');
                headings.forEach((heading) => {
                    if (!heading.id) {
                        // Generate slug: lowercase, remove special characters, replace spaces with dashes
                        const slug = heading.textContent
                            .toLowerCase()
                            .trim()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                        heading.id = slug;
                    }
                });
            }

            // 2. Reading Progress Logic
            const progressBar = document.getElementById('readingProgress');
            const articleContainer = document.querySelector('.article-content');

            if (progressBar && articleContainer) {
                const updateProgress = () => {
                    const rect = articleContainer.getBoundingClientRect();
                    const articleHeight = articleContainer.offsetHeight;
                    const windowHeight = window.innerHeight;

                    // Calculate how much of the article has passed the bottom of the viewport
                    // We start counting progress when the top of the article enters the viewport
                    // and finish when the bottom of the article leaves the viewport.

                    let progress = 0;
                    const startPoint = articleContainer.offsetTop;
                    const endPoint = startPoint + articleHeight - windowHeight;
                    const currentPos = window.scrollY;

                    if (currentPos > startPoint) {
                        progress = ((currentPos - startPoint) / (articleHeight - windowHeight)) * 100;
                    }

                    progress = Math.min(100, Math.max(0, progress));
                    progressBar.style.width = progress + '%';

                    // Optional: Show/Hide TOC container based on scroll
                    const tocContainer = document.getElementById('toc-container');
                    if (tocContainer) {
                        if (currentPos > startPoint + 200 && currentPos < startPoint + articleHeight - 400) {
                            tocContainer.style.opacity = '1';
                        } else if (currentPos < startPoint) {
                            tocContainer.style.opacity = '0.8';
                        }
                    }
                };

                window.addEventListener('scroll', updateProgress);
                window.addEventListener('resize', updateProgress);
                updateProgress(); // Initial call
            }

            // 3. Active Link Highlighting (Intersection Observer)
            const tocLinks = document.querySelectorAll('.toc-link');
            if (tocLinks.length > 0 && articleContainer) {
                const headings = Array.from(articleContainer.querySelectorAll('h2, h3, h4, h5, h6'));

                const observerOptions = {
                    root: null,
                    rootMargin: '-100px 0px -70% 0px',
                    threshold: 0
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const id = entry.target.id;
                            tocLinks.forEach(link => {
                                link.classList.remove('text-accent', 'dark:text-accent', 'font-bold');
                                if (link.getAttribute('href') === '#' + id) {
                                    link.classList.add('text-accent', 'dark:text-accent', 'font-bold');
                                    // Ensure the active dot is visible
                                    const dot = link.querySelector('span');
                                    if (dot) dot.style.opacity = '1';
                                } else {
                                    const dot = link.querySelector('span');
                                    if (dot) dot.style.opacity = '0';
                                }
                            });
                        }
                    });
                }, observerOptions);

                headings.forEach(heading => observer.observe(heading));
            }
        });
