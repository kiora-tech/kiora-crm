import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    confirm(event) {
        const message = this.element.dataset.deleteConfirmationMessage;
        if (!confirm(message)) {
            event.preventDefault();
        }
    }
}