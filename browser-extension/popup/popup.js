/**
 * Choose90 Extension Popup
 */

document.addEventListener('DOMContentLoaded', () => {
  // Load settings
  chrome.storage.local.get([
    'choose90_enabled',
    'choose90_min_length',
    'choose90_auto_detect'
  ], (items) => {
    const enabled = items.choose90_enabled !== false;
    const minLength = items.choose90_min_length || 50;
    const autoDetect = items.choose90_auto_detect !== false;
    
    // Set toggle states
    setToggle('toggle-enabled', enabled);
    setToggle('toggle-auto', autoDetect);
    document.getElementById('min-length').value = minLength;
    
    // Attach event listeners
    document.getElementById('toggle-enabled').addEventListener('click', () => {
      const newValue = !enabled;
      chrome.storage.local.set({ 'choose90_enabled': newValue });
      setToggle('toggle-enabled', newValue);
      // Reload current tab to apply changes
      chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
        if (tabs[0]) {
          chrome.tabs.reload(tabs[0].id);
        }
      });
    });
    
    document.getElementById('toggle-auto').addEventListener('click', () => {
      const newValue = !autoDetect;
      chrome.storage.local.set({ 'choose90_auto_detect': newValue });
      setToggle('toggle-auto', newValue);
    });
    
    document.getElementById('min-length').addEventListener('change', (e) => {
      const value = parseInt(e.target.value) || 50;
      chrome.storage.local.set({ 'choose90_min_length': value });
    });
  });
});

function setToggle(id, active) {
  const toggle = document.getElementById(id);
  const text = document.getElementById(id + '-text');
  
  if (active) {
    toggle.classList.add('active');
    text.textContent = 'On';
  } else {
    toggle.classList.remove('active');
    text.textContent = 'Off';
  }
}


