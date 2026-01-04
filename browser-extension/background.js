/**
 * Choose90 Browser Extension - Background Service Worker
 * 
 * Handles extension lifecycle, storage, and communication
 */

// Extension installation
chrome.runtime.onInstalled.addListener((details) => {
  if (details.reason === 'install') {
    // First time installation
    chrome.storage.local.set({
      'choose90_enabled': true,
      'choose90_min_length': 50,
      'choose90_auto_detect': true,
      'choose90_platform': 'auto'
    });
    
    // Open welcome page - show PWA installation option too
    chrome.tabs.create({
      url: 'https://choose90.org/pwa.html#mobile-app'
    });
  }
});

// Handle messages from content scripts
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  if (request.action === 'rewrite_post') {
    // Forward rewrite request to API
    rewritePost(request.text, request.platform)
      .then(result => {
        sendResponse({ success: true, data: result });
      })
      .catch(error => {
        sendResponse({ success: false, error: error.message });
      });
    
    // Return true to indicate we'll send response asynchronously
    return true;
  }
  
  if (request.action === 'get_settings') {
    chrome.storage.local.get([
      'choose90_enabled',
      'choose90_min_length',
      'choose90_auto_detect'
    ], (items) => {
      sendResponse({ success: true, settings: items });
    });
    return true;
  }
});

/**
 * Rewrite post using Choose90 API
 */
async function rewritePost(text, platform = 'general') {
  const apiUrl = 'https://choose90.org/api/rewrite-post.php';
  
  try {
    const response = await fetch(apiUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        text: text,
        platform: platform,
        tone: 'positive'
      })
    });
    
    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || 'API request failed');
    }
    
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Choose90: Rewrite error:', error);
    throw error;
  }
}

