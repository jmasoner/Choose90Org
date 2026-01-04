/**
 * Choose90 PWA - Main Application Logic
 */

const dailyQuotes = [
  {
    text: "Be the reason someone feels seen, heard, and valued today.",
    author: "Choose90"
  },
  {
    text: "Your words have power. Use them to lift others up.",
    author: "Choose90"
  },
  {
    text: "Small acts of kindness create ripples of positivity.",
    author: "Choose90"
  },
  {
    text: "Choose to be 90% positive. The other 10%? That's being human.",
    author: "Choose90"
  },
  {
    text: "Every post is a chance to add light to someone's day.",
    author: "Choose90"
  }
];

let currentQuoteIndex = 0;
let originalText = '';
let rewrittenText = '';

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  // Check URL for action
  const urlParams = new URLSearchParams(window.location.search);
  const action = urlParams.get('action');
  
  if (action === 'compose') {
    showComposer();
  } else {
    showDailyInspiration();
  }
  
  // Set up daily quote rotation
  setDailyQuote();
  setInterval(rotateQuote, 10000); // Rotate every 10 seconds
  
  // Set up event listeners
  setupEventListeners();
  
  // Register service worker
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/pwa/service-worker.js')
      .then(reg => console.log('Service Worker registered'))
      .catch(err => console.log('Service Worker registration failed:', err));
  }
});

function setupEventListeners() {
  // Daily inspiration - compose button
  const composeBtn = document.createElement('button');
  composeBtn.className = 'btn btn-primary';
  composeBtn.textContent = 'Compose Post';
  composeBtn.style.marginTop = '20px';
  composeBtn.onclick = showComposer;
  document.getElementById('daily-inspiration').appendChild(composeBtn);
  
  // Composer
  const postText = document.getElementById('post-text');
  const rewriteBtn = document.getElementById('rewrite-btn');
  const clearBtn = document.getElementById('clear-btn');
  const charCount = document.getElementById('char-count');
  
  postText.addEventListener('input', (e) => {
    const length = e.target.value.length;
    charCount.textContent = length;
    
    // Enable rewrite button if text is long enough
    if (length >= 10) {
      rewriteBtn.disabled = false;
    } else {
      rewriteBtn.disabled = true;
    }
  });
  
  rewriteBtn.addEventListener('click', () => {
    const text = postText.value.trim();
    if (text.length >= 10) {
      rewritePost(text);
    }
  });
  
  clearBtn.addEventListener('click', () => {
    postText.value = '';
    charCount.textContent = '0';
    rewriteBtn.disabled = true;
  });
  
  // Comparison
  const backBtn = document.getElementById('back-btn');
  const copyRewrittenBtn = document.getElementById('copy-rewritten-btn');
  const useRewrittenBtn = document.getElementById('use-rewritten-btn');
  
  backBtn.addEventListener('click', () => {
    showComposer();
    // Restore original text
    document.getElementById('post-text').value = originalText;
  });
  
  copyRewrittenBtn.addEventListener('click', async () => {
    // Copy rewritten text to clipboard
    try {
      await navigator.clipboard.writeText(rewrittenText);
      copyRewrittenBtn.textContent = '✅ Copied!';
      copyRewrittenBtn.style.background = '#10b981';
      setTimeout(() => {
        copyRewrittenBtn.textContent = '📋 Copy to Clipboard';
        copyRewrittenBtn.style.background = '';
      }, 2000);
      
      // Show helpful message
      showNativeAppTip();
    } catch (err) {
      console.error('Failed to copy:', err);
      // Fallback: select text
      const textEl = document.getElementById('rewritten-text');
      const range = document.createRange();
      range.selectNodeContents(textEl);
      const selection = window.getSelection();
      selection.removeAllRanges();
      selection.addRange(range);
      copyRewrittenBtn.textContent = '📋 Text Selected - Copy Manually';
    }
  });
  
  useRewrittenBtn.addEventListener('click', () => {
    // Replace text in composer with rewritten version
    document.getElementById('post-text').value = rewrittenText;
    showComposer();
  });
}

function showDailyInspiration() {
  document.getElementById('daily-inspiration').style.display = 'block';
  document.getElementById('composer').classList.remove('active');
  document.getElementById('comparison').classList.remove('active');
}

function showComposer() {
  document.getElementById('daily-inspiration').style.display = 'none';
  document.getElementById('composer').classList.add('active');
  document.getElementById('comparison').classList.remove('active');
  document.getElementById('post-text').focus();
}

function showComparison() {
  document.getElementById('daily-inspiration').style.display = 'none';
  document.getElementById('composer').classList.remove('active');
  document.getElementById('comparison').classList.add('active');
}

function setDailyQuote() {
  // Use date to get consistent quote per day
  const today = new Date();
  const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / 1000 / 60 / 60 / 24);
  currentQuoteIndex = dayOfYear % dailyQuotes.length;
  
  const quote = dailyQuotes[currentQuoteIndex];
  document.getElementById('daily-quote').textContent = `"${quote.text}"`;
  document.querySelector('.quote-author').textContent = `— ${quote.author}`;
}

function rotateQuote() {
  currentQuoteIndex = (currentQuoteIndex + 1) % dailyQuotes.length;
  const quote = dailyQuotes[currentQuoteIndex];
  
  const quoteEl = document.getElementById('daily-quote');
  quoteEl.style.opacity = '0';
  
  setTimeout(() => {
    quoteEl.textContent = `"${quote.text}"`;
    document.querySelector('.quote-author').textContent = `— ${quote.author}`;
    quoteEl.style.opacity = '1';
  }, 300);
}

async function rewritePost(text) {
  originalText = text;
  
  // Show loading state
  showComposer();
  const rewriteBtn = document.getElementById('rewrite-btn');
  rewriteBtn.disabled = true;
  rewriteBtn.textContent = 'Rewriting...';
  
  // Hide error
  document.getElementById('error-message').classList.remove('active');
  
  try {
    const response = await fetch('/api/rewrite-post.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        text: text,
        platform: 'general',
        tone: 'positive'
      })
    });
    
    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || 'Failed to rewrite post');
    }
    
    const data = await response.json();
    
    if (data.success) {
      rewrittenText = data.rewritten;
      
      // Show comparison
      document.getElementById('original-text').textContent = data.original;
      document.getElementById('rewritten-text').textContent = data.rewritten;
      showComparison();
    } else {
      throw new Error('Invalid response from server');
    }
  } catch (error) {
    console.error('Rewrite error:', error);
    showError(error.message);
    showComposer();
  } finally {
    rewriteBtn.disabled = false;
    rewriteBtn.textContent = '✨ Make it Choose90';
  }
}

function showError(message) {
  const errorEl = document.getElementById('error-message');
  errorEl.textContent = `Error: ${message}`;
  errorEl.classList.add('active');
}

function showNativeAppTip() {
  // Show tip about using with native apps
  const tip = document.createElement('div');
  tip.className = 'native-app-tip';
  tip.style.cssText = `
    background: #dbeafe;
    border: 2px solid #3b82f6;
    border-radius: 12px;
    padding: 16px;
    margin: 16px 0;
    text-align: center;
    color: #1e40af;
    font-size: 14px;
  `;
  tip.innerHTML = `
    <strong>💡 Tip:</strong> Using X app or Facebook app?<br>
    Copy the text above, then paste it into your native app!
  `;
  
  const comparison = document.getElementById('comparison');
  const actions = comparison.querySelector('.actions');
  if (actions && !comparison.querySelector('.native-app-tip')) {
    actions.parentNode.insertBefore(tip, actions);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
      tip.style.opacity = '0';
      tip.style.transition = 'opacity 0.3s';
      setTimeout(() => tip.remove(), 300);
    }, 5000);
  }
}

// Handle install prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;
  
  // Show install button (you can add this to the UI)
  const installBtn = document.createElement('button');
  installBtn.className = 'btn btn-primary';
  installBtn.textContent = 'Install Choose90 App';
  installBtn.style.marginTop = '20px';
  installBtn.onclick = async () => {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      const { outcome } = await deferredPrompt.userChoice;
      console.log('User choice:', outcome);
      deferredPrompt = null;
      installBtn.remove();
    }
  };
  document.querySelector('.footer').prepend(installBtn);
});

