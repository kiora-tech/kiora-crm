import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["defaultColor", "primaryColor", "secondaryColor", "tertiaryColor", "saveButton"];

  showPreview(event) {
    event.preventDefault();

    const defaultColor = this.defaultColorTarget.querySelector('input').value || '#444444';
    const primaryColor = this.primaryColorTarget.querySelector('input').value || '#4154F1';
    const secondaryColor = this.secondaryColorTarget.querySelector('input').value || '#012970';
    const tertiaryColor = this.tertiaryColorTarget.querySelector('input').value || '#2C384E';

    let existingOverlay = document.getElementById("color-preview-overlay");
    if (existingOverlay) {
      existingOverlay.remove();
    }

    const overlay = document.createElement("div");
    overlay.id = "color-preview-overlay";
    overlay.style.position = "fixed";
    overlay.style.top = 0;
    overlay.style.left = 0;
    overlay.style.width = "100%";
    overlay.style.height = "100%";
    overlay.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
    overlay.style.zIndex = 1000;
    overlay.style.display = "flex";
    overlay.style.alignItems = "center";
    overlay.style.justifyContent = "center";


    overlay.innerHTML = `
    <div style="background: white; padding: 20px; border-radius: 8px; max-width: 90%; max-height: 90%; overflow: auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
      <!-- Default Color Section -->
      <div class="color-section" style="color: ${defaultColor}; border: 1px solid ${defaultColor}; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
        <h3 style="color: ${defaultColor};">Default Color</h3>
        <label for="default-input" style="color: ${defaultColor};">Default Input Label</label>
        <input type="text" id="default-input" style="color: ${defaultColor}; border: 1px solid ${defaultColor}; background-color: transparent; padding: 5px; margin-bottom: 10px;">
        <button class="btn" style="background-color: ${defaultColor}; color: white;">Default Button</button>
        <p style="color: ${defaultColor}; margin-top: 10px;">This is a paragraph in the default color section.</p>
        <a href="#" style="color: ${defaultColor}; text-decoration: underline; display: block; margin-top: 10px;">Default Color Link</a>
        <select style="color: ${defaultColor}; border: 1px solid ${defaultColor}; background-color: transparent; padding: 5px; margin-top: 10px;">
          <option>Option 1</option>
          <option>Option 2</option>
        </select>
      </div>
      
      <!-- Primary Color Section -->
      <div class="color-section" style="color: ${primaryColor}; border: 1px solid ${primaryColor}; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
        <h3 style="color: ${primaryColor};">Primary Color</h3>
        <label for="primary-input" style="color: ${primaryColor};">Primary Input Label</label>
        <input type="text" id="primary-input" style="color: ${primaryColor}; border: 1px solid ${primaryColor}; background-color: transparent; padding: 5px; margin-bottom: 10px;">
        <button class="btn" style="background-color: ${primaryColor}; color: white;">Primary Button</button>
        <p style="color: ${primaryColor}; margin-top: 10px;">This is a paragraph in the primary color section.</p>
        <a href="#" style="color: ${primaryColor}; text-decoration: underline; display: block; margin-top: 10px;">Primary Color Link</a>
        <select style="color: ${primaryColor}; border: 1px solid ${primaryColor}; background-color: transparent; padding: 5px; margin-top: 10px;">
          <option>Option 1</option>
          <option>Option 2</option>
        </select>
      </div>
      
      <!-- Secondary Color Section -->
      <div class="color-section" style="color: ${secondaryColor}; border: 1px solid ${secondaryColor}; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
        <h3 style="color: ${secondaryColor};">Secondary Color</h3>
        <label for="secondary-input" style="color: ${secondaryColor};">Secondary Input Label</label>
        <input type="text" id="secondary-input" style="color: ${secondaryColor}; border: 1px solid ${secondaryColor}; background-color: transparent; padding: 5px; margin-bottom: 10px;">
        <button class="btn" style="background-color: ${secondaryColor}; color: white;">Secondary Button</button>
        <p style="color: ${secondaryColor}; margin-top: 10px;">This is a paragraph in the secondary color section.</p>
        <a href="#" style="color: ${secondaryColor}; text-decoration: underline; display: block; margin-top: 10px;">Secondary Color Link</a>
        <select style="color: ${secondaryColor}; border: 1px solid ${secondaryColor}; background-color: transparent; padding: 5px; margin-top: 10px;">
          <option>Option 1</option>
          <option>Option 2</option>
        </select>
      </div>
      
      <!-- Tertiary Color Section -->
      <div class="color-section" style="color: ${tertiaryColor}; border: 1px solid ${tertiaryColor}; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
        <h3 style="color: ${tertiaryColor};">Tertiary Color</h3>
        <label for="tertiary-input" style="color: ${tertiaryColor};">Tertiary Input Label</label>
        <input type="text" id="tertiary-input" style="color: ${tertiaryColor}; border: 1px solid ${tertiaryColor}; background-color: transparent; padding: 5px; margin-bottom: 10px;">
        <button class="btn" style="background-color: ${tertiaryColor}; color: white;">Tertiary Button</button>
        <p style="color: ${tertiaryColor}; margin-top: 10px;">This is a paragraph in the tertiary color section.</p>
        <a href="#" style="color: ${tertiaryColor}; text-decoration: underline; display: block; margin-top: 10px;">Tertiary Color Link</a>
        <select style="color: ${tertiaryColor}; border: 1px solid ${tertiaryColor}; background-color: transparent; padding: 5px; margin-top: 10px;">
          <option>Option 1</option>
          <option>Option 2</option>
        </select>
      </div>
      
      <!-- Action Buttons -->
      <div class="d-flex" style="display: flex; gap: 10px; margin-top: 20px;">
        <button id="close-preview-btn" class="btn" style="background-color: #6c757d; color: white;">Close Preview</button>
        <button id="validate-preview-btn" class="btn" style="background-color: #007bff; color: white;">Validate Preview</button>
      </div>
    </div>
    `;
    
    


    document.body.appendChild(overlay);


    document.getElementById("close-preview-btn").addEventListener("click", () => {
      overlay.remove();
    });

    document.getElementById("validate-preview-btn").addEventListener("click", () => {
      overlay.remove();
      this.saveButtonTarget.click();
    });
  }
}
