import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    switch(event) {
        event.preventDefault();

        const id = event.params.id;
        if (id === undefined) {
            console.error('No tab id provided');
            return;
        }

        const tab = document.getElementById(id);
        if (tab === null) {
            console.error(`Tab with id ${id} not found`);
            return;
        }

        tab.click();
        window.scrollTo({ top: 0, left: window.scrollX, behavior: 'smooth' })
    }
}