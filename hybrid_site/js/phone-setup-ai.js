/**
 * Choose90 Phone Setup AI Integration
 * 
 * Provides interactive AI-powered instructions for phone setup optimization
 */

class PhoneSetupAI {
    constructor() {
        // Determine API endpoint based on current page location
        const isResourcePage = window.location.pathname.includes('/resources/');
        this.apiEndpoint = isResourcePage ? '../api/phone-setup-ai.php' : '/api/phone-setup-ai.php';
        this.currentStep = null;
        this.deviceInfo = {
            brand: '',
            model: '',
            software_version: ''
        };
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupEventListeners());
        } else {
            this.setupEventListeners();
        }
    }

    setupEventListeners() {
        // Find all step boxes and add AI buttons
        const stepBoxes = document.querySelectorAll('.step-box');
        stepBoxes.forEach((box, index) => {
            const stepTitle = box.querySelector('h4');
            if (stepTitle) {
                // Create AI button
                const aiButton = document.createElement('button');
                aiButton.className = 'ai-instructions-btn';
                aiButton.innerHTML = '🤖 Get AI-Powered Instructions';
                aiButton.setAttribute('data-step', index + 1);
                aiButton.setAttribute('data-task', stepTitle.textContent);
                
                // Add click handler
                aiButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleAIClick(e.target);
                });

                // Insert button after title
                stepTitle.insertAdjacentElement('afterend', aiButton);
            }
        });

        // Device info form (if exists)
        const deviceForm = document.getElementById('device-info-form');
        if (deviceForm) {
            deviceForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveDeviceInfo();
            });
        }
    }

    handleAIClick(button) {
        const step = button.getAttribute('data-step');
        const task = button.getAttribute('data-task');

        // Check if device info is collected
        if (!this.deviceInfo.brand || !this.deviceInfo.model) {
            this.showDeviceInfoModal(step, task);
            return;
        }

        // Get AI instructions
        this.getAIInstructions(step, task);
    }

    showDeviceInfoModal(step, task) {
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'ai-modal-overlay';
        modal.innerHTML = `
            <div class="ai-modal">
                <div class="ai-modal-header">
                    <h3>📱 Tell Us About Your Phone</h3>
                    <button class="ai-modal-close">&times;</button>
                </div>
                <div class="ai-modal-body">
                    <p>To provide you with exact step-by-step instructions, we need to know your device details.</p>
                    
                    <form id="device-info-form-modal">
                        <div class="form-group">
                            <label for="phone-brand">Phone Brand *</label>
                            <select id="phone-brand" required>
                                <option value="">Select brand...</option>
                                <option value="Apple">Apple (iPhone)</option>
                                <option value="Samsung">Samsung</option>
                                <option value="Google">Google (Pixel)</option>
                                <option value="OnePlus">OnePlus</option>
                                <option value="Xiaomi">Xiaomi</option>
                                <option value="Huawei">Huawei</option>
                                <option value="Motorola">Motorola</option>
                                <option value="LG">LG</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone-model">Phone Model *</label>
                            <input type="text" id="phone-model" placeholder="e.g., iPhone 15 Pro, Galaxy S23, Pixel 7" required>
                            <small>Don't know? Check Settings → About Phone</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="software-version">Software Version</label>
                            <input type="text" id="software-version" placeholder="e.g., iOS 17.2, Android 14">
                            <small>Optional but helps us be more accurate</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn-primary">Get Instructions</button>
                            <button type="button" class="btn-secondary ai-modal-close">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Close handlers
        modal.querySelectorAll('.ai-modal-close').forEach(btn => {
            btn.addEventListener('click', () => modal.remove());
        });

        // Form submit
        modal.querySelector('#device-info-form-modal').addEventListener('submit', (e) => {
            e.preventDefault();
            this.deviceInfo = {
                brand: document.getElementById('phone-brand').value,
                model: document.getElementById('phone-model').value,
                software_version: document.getElementById('software-version').value || 'Latest'
            };
            modal.remove();
            this.getAIInstructions(step, task);
        });
    }

    async getAIInstructions(step, task) {
        // Find the step box
        const stepBox = document.querySelector(`[data-step="${step}"]`)?.closest('.step-box');
        if (!stepBox) return;

        // Show loading state
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'ai-loading';
        loadingDiv.innerHTML = `
            <div class="ai-loading-spinner"></div>
            <p>Getting personalized instructions for your ${this.deviceInfo.brand} ${this.deviceInfo.model}...</p>
        `;
        stepBox.appendChild(loadingDiv);

        try {
            // Check if we're running from file:// protocol (local file)
            if (window.location.protocol === 'file:') {
                throw new Error('This feature requires a web server. Please access this page through http://localhost or deploy it to your web server.');
            }

            const response = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    brand: this.deviceInfo.brand,
                    model: this.deviceInfo.model,
                    software_version: this.deviceInfo.software_version,
                    step: step,
                    task: task
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                let errorData;
                try {
                    errorData = JSON.parse(errorText);
                } catch (e) {
                    throw new Error(`Server error (${response.status}): ${errorText.substring(0, 100)}`);
                }
                throw new Error(errorData.error || `Server error: ${response.status}`);
            }

            const data = await response.json();

            // Remove loading
            loadingDiv.remove();

            if (!data.success) {
                throw new Error(data.error || 'Failed to get instructions');
            }

            // Display AI instructions
            this.displayAIInstructions(stepBox, data.instructions, task);

        } catch (error) {
            loadingDiv.remove();
            console.error('AI API Error:', error);
            this.showError(stepBox, error.message);
        }
    }

    displayAIInstructions(stepBox, instructions, task) {
        // Check if AI instructions already exist
        let aiSection = stepBox.querySelector('.ai-instructions');
        if (!aiSection) {
            aiSection = document.createElement('div');
            aiSection.className = 'ai-instructions';
            stepBox.appendChild(aiSection);
        }

        aiSection.innerHTML = `
            <div class="ai-instructions-header">
                <h5>🤖 AI-Powered Instructions for Your Device</h5>
                <button class="ai-instructions-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
            <div class="ai-instructions-content">
                ${this.formatInstructions(instructions)}
            </div>
            <div class="ai-instructions-footer">
                <small>These instructions are customized for your ${this.deviceInfo.brand} ${this.deviceInfo.model}</small>
            </div>
        `;

        // Scroll to instructions
        aiSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    formatInstructions(text) {
        // Convert numbered lists and formatting
        let formatted = text
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>')
            .replace(/(\d+)\.\s/g, '<strong>$1.</strong> ');

        return `<p>${formatted}</p>`;
    }

    showError(stepBox, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'ai-error';
        
        // Provide helpful error messages
        let helpfulMessage = message;
        if (message.includes('file://') || message.includes('web server')) {
            helpfulMessage = 'This feature requires a web server. Please access this page through http://localhost:8000/resources/phone-setup-optimizer.html or deploy it to your web server.';
        } else if (message.includes('Failed to fetch') || message.includes('NetworkError')) {
            helpfulMessage = 'Unable to connect to the server. Make sure you\'re accessing this page through a web server (not by opening the file directly). Try: http://localhost:8000/resources/phone-setup-optimizer.html';
        }
        
        errorDiv.innerHTML = `
            <p><strong>⚠️ ${helpfulMessage}</strong></p>
            <p><small>You can still follow the general instructions above.</small></p>
        `;
        stepBox.appendChild(errorDiv);
    }

    saveDeviceInfo() {
        // Save to localStorage for future use
        localStorage.setItem('choose90_device_info', JSON.stringify(this.deviceInfo));
    }

    loadDeviceInfo() {
        // Load from localStorage
        const saved = localStorage.getItem('choose90_device_info');
        if (saved) {
            this.deviceInfo = JSON.parse(saved);
        }
    }
}

// Initialize when page loads
if (typeof window !== 'undefined') {
    window.phoneSetupAI = new PhoneSetupAI();
    window.phoneSetupAI.loadDeviceInfo();
}

