/**
 * Choose90 Browser Extension - Content Script
 * 
 * Detects text inputs on social media platforms and offers to rewrite posts
 */

(function() {
  'use strict';
  
  let isExtensionEnabled = true;
  let minLength = 50;
  let autoDetect = true;
  let currentPlatform = 'general';
  let rewriteOverlay = null;
  
  // Detect platform
  const hostname = window.location.hostname;
  if (hostname.includes('twitter.com') || hostname.includes('x.com')) {
    currentPlatform = 'twitter';
  } else if (hostname.includes('facebook.com')) {
    currentPlatform = 'facebook';
  } else if (hostname.includes('linkedin.com')) {
    currentPlatform = 'linkedin';
  }
  
  // Get settings from storage
  chrome.storage.local.get([
    'choose90_enabled',
    'choose90_min_length',
    'choose90_auto_detect'
  ], (items) => {
    isExtensionEnabled = items.choose90_enabled !== false;
    minLength = items.choose90_min_length || 50;
    autoDetect = items.choose90_auto_detect !== false;
    
    if (isExtensionEnabled) {
      init();
    }
  });
  
  function init() {
    // Watch for new text inputs (social media sites are dynamic)
    observeTextInputs();
    
    // Also check existing inputs
    setTimeout(() => {
      attachToExistingInputs();
    }, 1000);
  }
  
  function observeTextInputs() {
    // Use MutationObserver to watch for new text inputs
    const observer = new MutationObserver((mutations) => {
      attachToExistingInputs();
    });
    
    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }
  
  function attachToExistingInputs() {
    // Find text inputs based on platform
    let selectors = [];
    
    if (currentPlatform === 'twitter') {
      selectors = [
        'div[data-testid="tweetTextarea_0"]',
        'div[contenteditable="true"][role="textbox"]',
        'div[data-testid="tweetTextarea_0"] div[contenteditable="true"]'
      ];
    } else if (currentPlatform === 'facebook') {
      selectors = [
        'div[contenteditable="true"][role="textbox"]',
        'div[data-testid="status-attachment-mentions-input"]',
        'div[contenteditable="true"][aria-label*="Write"]'
      ];
    } else if (currentPlatform === 'linkedin') {
      selectors = [
        'div[contenteditable="true"][role="textbox"]',
        'div[contenteditable="true"][aria-label*="What"]'
      ];
    }
    
    selectors.forEach(selector => {
      const inputs = document.querySelectorAll(selector);
      inputs.forEach(input => {
        if (!input.dataset.choose90Attached) {
          attachToInput(input);
          input.dataset.choose90Attached = 'true';
        }
      });
    });
  }
  
  function attachToInput(input) {
    let lastText = '';
    let checkTimeout = null;
    
    // Monitor text changes
    input.addEventListener('input', (e) => {
      const text = getTextFromInput(input);
      
      // Debounce checks
      clearTimeout(checkTimeout);
      checkTimeout = setTimeout(() => {
        if (text.length >= minLength && text !== lastText) {
          lastText = text;
          showRewriteSuggestion(input, text);
        } else if (text.length < minLength) {
          hideRewriteSuggestion();
        }
      }, 500);
    });
    
    // Also check on focus
    input.addEventListener('focus', () => {
      const text = getTextFromInput(input);
      if (text.length >= minLength) {
        showRewriteSuggestion(input, text);
      }
    });
  }
  
  function getTextFromInput(input) {
    // Handle contenteditable divs
    if (input.contentEditable === 'true') {
      return input.innerText || input.textContent || '';
    }
    // Handle regular inputs
    return input.value || '';
  }
  
  function showRewriteSuggestion(inputElement, text) {
    // Remove existing overlay
    hideRewriteSuggestion();
    
    // Create suggestion indicator
    const indicator = document.createElement('div');
    indicator.className = 'choose90-indicator';
    indicator.innerHTML = '✨ Choose90 can help make this more positive';
    indicator.style.cssText = `
      position: absolute;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      z-index: 10000;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      transition: transform 0.2s, box-shadow 0.2s;
    `;
    
    indicator.addEventListener('mouseenter', () => {
      indicator.style.transform = 'scale(1.05)';
      indicator.style.boxShadow = '0 6px 16px rgba(0,0,0,0.2)';
    });
    
    indicator.addEventListener('mouseleave', () => {
      indicator.style.transform = 'scale(1)';
      indicator.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    });
    
    indicator.addEventListener('click', () => {
      showRewriteOverlay(inputElement, text);
    });
    
    // Position indicator near the input
    const rect = inputElement.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
    
    indicator.style.top = (rect.bottom + scrollTop + 10) + 'px';
    indicator.style.left = (rect.left + scrollLeft) + 'px';
    
    document.body.appendChild(indicator);
    
    // Store reference
    rewriteOverlay = { indicator, inputElement };
  }
  
  function hideRewriteSuggestion() {
    if (rewriteOverlay && rewriteOverlay.indicator) {
      rewriteOverlay.indicator.remove();
    }
    rewriteOverlay = null;
  }
  
  async function showRewriteOverlay(inputElement, originalText) {
    hideRewriteSuggestion();
    
    // Show loading state
    const loadingOverlay = createOverlay('Loading...', 'Rewriting your post with Choose90...');
    document.body.appendChild(loadingOverlay);
    
    try {
      // Check if chrome.runtime is available
      if (typeof chrome === 'undefined' || !chrome.runtime || !chrome.runtime.sendMessage) {
        throw new Error('Extension context invalidated. Please reload the extension.');
      }
      
      // Send rewrite request
      const response = await chrome.runtime.sendMessage({
        action: 'rewrite_post',
        text: originalText,
        platform: currentPlatform
      });
      
      if (response && response.success) {
        // Show rewrite overlay with comparison
        showRewriteComparison(inputElement, originalText, response.data.rewritten);
      } else {
        showError('Failed to rewrite post: ' + (response?.error || 'Unknown error'));
      }
    } catch (error) {
      console.error('Choose90: Rewrite error:', error);
      const errorMessage = error.message || 'Failed to connect to Choose90';
      showError(errorMessage + '. Please try reloading the extension.');
    } finally {
      loadingOverlay.remove();
    }
  }
  
  function showRewriteComparison(inputElement, original, rewritten) {
    // Generate unique IDs for this overlay instance
    const overlayId = 'choose90-overlay-' + Date.now();
    const inputId = inputElement.id || 'choose90-input-' + Date.now();
    if (!inputElement.id) {
      inputElement.id = inputId;
    }
    
    // Store data on window object for global handlers (workaround for CSP)
    if (!window.choose90Overlays) {
      window.choose90Overlays = {};
    }
    window.choose90Overlays[overlayId] = {
      inputId: inputId,
      rewritten: rewritten,
      overlay: null
    };
    
    const overlay = document.createElement('div');
    overlay.id = overlayId;
    overlay.className = 'choose90-overlay';
    overlay.style.zIndex = '999999';
    overlay.style.pointerEvents = 'auto';
    
    // Create buttons with data attributes
    overlay.innerHTML = `
      <div class="choose90-overlay-content" style="pointer-events: auto;">
        <div class="choose90-overlay-header">
          <h3>✨ Choose90 Rewrite</h3>
          <button type="button" class="choose90-close choose90-close-btn" data-action="close" data-overlay="${overlayId}">×</button>
        </div>
        <div class="choose90-comparison">
          <div class="choose90-original">
            <h4>Your Original Post</h4>
            <div class="choose90-text">${escapeHtml(original)}</div>
          </div>
          <div class="choose90-rewritten">
            <h4>Choose90 Version</h4>
            <div class="choose90-text">${escapeHtml(rewritten)}</div>
          </div>
        </div>
        <div class="choose90-actions">
          <button type="button" class="choose90-btn choose90-btn-secondary choose90-keep-original" data-action="close" data-overlay="${overlayId}">
            Keep Original
          </button>
          <button type="button" class="choose90-btn choose90-btn-primary choose90-use-rewritten" data-action="use" data-overlay="${overlayId}">
            Use This Version
          </button>
        </div>
        <div class="choose90-footer">
          <a href="https://choose90.org/" target="_blank">Learn more about Choose90 →</a>
        </div>
      </div>
    `;
    
    window.choose90Overlays[overlayId].overlay = overlay;
    document.body.appendChild(overlay);
    
    // Create global handler functions if they don't exist
    if (!window.choose90HandleButtonClick) {
      window.choose90HandleButtonClick = function(overlayId, action) {
        const overlayData = window.choose90Overlays && window.choose90Overlays[overlayId];
        if (!overlayData || !overlayData.overlay) return;
        
        const overlay = overlayData.overlay;
        
        if (action === 'close') {
          overlay.remove();
          delete window.choose90Overlays[overlayId];
        } else if (action === 'use') {
          const inputElement = document.getElementById(overlayData.inputId);
          if (inputElement) {
            if (inputElement.contentEditable === 'true') {
              inputElement.innerText = overlayData.rewritten;
              inputElement.textContent = overlayData.rewritten;
              ['input', 'change', 'keyup', 'paste'].forEach(eventType => {
                inputElement.dispatchEvent(new Event(eventType, { bubbles: true }));
              });
            } else {
              inputElement.value = overlayData.rewritten;
              ['input', 'change', 'keyup'].forEach(eventType => {
                inputElement.dispatchEvent(new Event(eventType, { bubbles: true }));
              });
            }
          }
          overlay.remove();
          delete window.choose90Overlays[overlayId];
        }
      };
    }
    
    // Attach listeners using delegation on overlay
    overlay.addEventListener('click', function(e) {
      const button = e.target.closest('[data-action]');
      if (button) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        const action = button.getAttribute('data-action');
        const id = button.getAttribute('data-overlay');
        window.choose90HandleButtonClick(id, action);
        return false;
      }
      
      // Click outside to close
      if (e.target === overlay) {
        overlay.remove();
        delete window.choose90Overlays[overlayId];
      }
    }, true);
    
    // Also try mousedown (fires before click)
    overlay.addEventListener('mousedown', function(e) {
      const button = e.target.closest('[data-action]');
      if (button) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        const action = button.getAttribute('data-action');
        const id = button.getAttribute('data-overlay');
        window.choose90HandleButtonClick(id, action);
        return false;
      }
    }, true);
    
    // Escape key to close
    const escapeHandler = function(e) {
      if (e.key === 'Escape' && document.body.contains(overlay)) {
        overlay.remove();
        delete window.choose90Overlays[overlayId];
        document.removeEventListener('keydown', escapeHandler);
      }
    };
    document.addEventListener('keydown', escapeHandler);
  }
  
  function createOverlay(title, message) {
    const overlay = document.createElement('div');
    overlay.className = 'choose90-overlay';
    overlay.innerHTML = `
      <div class="choose90-overlay-content">
        <div class="choose90-loading">
          <div class="choose90-spinner"></div>
          <h3>${title}</h3>
          <p>${message}</p>
        </div>
      </div>
    `;
    return overlay;
  }
  
  function showError(message) {
    // Remove any existing error overlays first
    const existingOverlays = document.querySelectorAll('.choose90-overlay');
    existingOverlays.forEach(ov => ov.remove());
    
    const overlay = document.createElement('div');
    overlay.className = 'choose90-overlay';
    
    // Create the button element separately so we can attach listener before appending
    const okButton = document.createElement('button');
    okButton.className = 'choose90-btn choose90-btn-primary choose90-error-ok';
    okButton.textContent = 'OK';
    okButton.type = 'button'; // Prevent form submission if inside a form
    
    // Add click handler BEFORE appending to DOM
    okButton.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      overlay.remove();
    });
    
    overlay.innerHTML = `
      <div class="choose90-overlay-content">
        <div class="choose90-error">
          <h3>⚠️ Error</h3>
          <p>${escapeHtml(message)}</p>
        </div>
      </div>
    `;
    
    // Insert the button into the error div
    const errorDiv = overlay.querySelector('.choose90-error');
    if (errorDiv) {
      errorDiv.appendChild(okButton);
    }
    
    // Also allow clicking outside to close
    overlay.addEventListener('click', function(e) {
      if (e.target === overlay) {
        overlay.remove();
      }
    });
    
    // Also allow Escape key to close
    const escapeHandler = function(e) {
      if (e.key === 'Escape' && document.body.contains(overlay)) {
        overlay.remove();
        document.removeEventListener('keydown', escapeHandler);
      }
    };
    document.addEventListener('keydown', escapeHandler);
    
    document.body.appendChild(overlay);
    
    // Focus the OK button for keyboard accessibility
    setTimeout(() => okButton.focus(), 100);
  }
  
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
  
})();


