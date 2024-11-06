import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container'];
    static values = { index: Number, template: String };

    addItem(event) {
        event.preventDefault();

        const prototype = (''+this.templateValue).replace(/__name__/g, this.indexValue);
        this.containerTarget.insertAdjacentHTML('beforeend', prototype);
        this.indexValue++;
    }

    removeItem(event) {
        event.preventDefault();
        event.target.closest('.form-item').remove();
    }
}