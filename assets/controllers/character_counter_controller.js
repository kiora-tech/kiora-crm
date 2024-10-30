import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["textarea", "counter"]

    connect() {
        this.updateCounter();
    }

    updateCounter() {
        const maxLength = this.textareaTarget.getAttribute('maxlength');
        const currentLength = this.textareaTarget.value.length;
        const remaining = maxLength - currentLength;
        this.counterTarget.textContent = `${remaining}/${maxLength}`;
    }

    update() {
        const maxLength = parseInt(this.textareaTarget.getAttribute('maxlength'), 10);
        const currentLength = this.textareaTarget.value.length;

        if (currentLength >= maxLength && event.inputType !== 'deleteContentBackward') {
            event.preventDefault();
            this.textareaTarget.value = this.textareaTarget.value.substring(0, maxLength);
        } else {
            this.updateCounter();
        }
    }
}