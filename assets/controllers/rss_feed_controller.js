import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.fetchRss();
        this.startAutoScroll();

        // Stop autoscroll on mouseover, resume on mouseout
        this.element.addEventListener('mouseover', () => this.stopAutoScroll());
        this.element.addEventListener('mouseout', () => this.startAutoScroll());
    }

    fetchRss() {
        fetch('/fetch-rss')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                const newsContainer = this.element.querySelector('.news');
                newsContainer.innerHTML = '';

                data.forEach((item, index) => {
                    const postItem = document.createElement('div');
                    postItem.classList.add('post-item', 'clearfix');

                    const link = document.createElement('a');
                    link.href = item.link;
                    link.textContent = item.title;
                    link.target = '_blank';

                    const title = document.createElement('h4');
                    title.style.marginLeft = 'unset';
                    title.style.fontSize = '14px';
                    title.appendChild(link);

                    const description = document.createElement('p');
                    description.style.marginLeft = 'unset';
                    description.style.fontSize = '12px';
                    description.textContent = item.description;

                    postItem.appendChild(title);
                    postItem.appendChild(description);

                    newsContainer.appendChild(postItem);
                });
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                this.element.innerHTML = 'Failed to load RSS feed.';
            });
    }

    startAutoScroll() {
        const scrollContainer = this.element.querySelector('.news');
        const scrollSpeed = 50;
        this.autoScrollInterval = setInterval(() => {
            scrollContainer.scrollTop += 1;

            if (scrollContainer.scrollTop + scrollContainer.clientHeight >= scrollContainer.scrollHeight) {
                scrollContainer.scrollTop = 0;
            }
        }, scrollSpeed);
    }

    stopAutoScroll() {
        clearInterval(this.autoScrollInterval);
    }
}
