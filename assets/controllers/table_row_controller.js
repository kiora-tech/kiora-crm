import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = { url: String }

    connect() {
        this.element.style.cursor = 'pointer';
    }

    navigate() {
        window.location.href = this.urlValue;
    }
}
