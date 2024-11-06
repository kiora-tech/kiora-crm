import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['category', 'nature'];

    updateNature(event) {
        const category = event.target.value;
        const natureSelect = this.natureTargets.find(
            target => target.closest('.form-item') === event.target.closest('.form-item')
        );

        if (category === '') {
            natureSelect.disabled = true;
            natureSelect.innerHTML = '<option value="">Select a category first</option>';
        } else {
            fetch(`/dynamic-form/nature-options/${category}`)
                .then(response => response.json())
                .then(data => {
                    natureSelect.innerHTML = '';
                    const choices = data.choices;
                    if (Object.keys(choices).length === 0) {
                        natureSelect.disabled = true;
                        natureSelect.innerHTML = '<option value="">Select a category first</option>';
                    } else {
                        natureSelect.disabled = false;
                        Object.entries(choices).forEach(([label, value]) => {
                            const option = document.createElement('option');
                            option.value = value;
                            option.textContent = label;
                            natureSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching nature options:', error);
                    natureSelect.innerHTML = '<option value="">Error loading options</option>';
                    natureSelect.disabled = true;
                });
        }
    }
}
