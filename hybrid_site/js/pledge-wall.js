/**
 * Choose90 Pledge Wall
 * Interactive counter and public pledge feed
 */

// Initial counter value (seeded for social proof)
let pledgeCount = 3363;

// Format number with commas
function formatNumber(num) {
    return num.toLocaleString();
}

// Update counter display
function updateCounter() {
    const counterEl = document.getElementById('pledge-counter');
    if (counterEl) {
        // Animate the number change
        const currentNum = parseInt(counterEl.textContent.replace(/,/g, ''));
        const targetNum = pledgeCount;
        
        if (currentNum !== targetNum) {
            animateCounter(currentNum, targetNum, counterEl);
        }
    }
}

// Animate counter
function animateCounter(start, end, element) {
    const duration = 1000; // 1 second
    const steps = 30;
    const increment = (end - start) / steps;
    let current = start;
    let step = 0;
    
    const timer = setInterval(() => {
        step++;
        current += increment;
        
        if (step >= steps) {
            current = end;
            clearInterval(timer);
        }
        
        element.textContent = formatNumber(Math.round(current));
    }, duration / steps);
}

// Submit pledge
async function submitPledge(event) {
    event.preventDefault();
    
    const name = document.getElementById('pledge-name').value.trim();
    const message = document.getElementById('pledge-message').value.trim();
    const isPublic = document.getElementById('pledge-public').checked;
    const submitBtn = document.getElementById('submit-btn');
    const successMsg = document.getElementById('success-message');
    
    if (!name) {
        alert('Please enter your name!');
        return;
    }
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.textContent = 'Adding Your Pledge...';
    
    try {
        // Submit to API
        const response = await fetch('/api/submit-pledge.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                message: message,
                public: isPublic
            })
        });
        
        if (!response.ok) {
            throw new Error('Failed to submit pledge');
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Increment counter
            pledgeCount++;
            updateCounter();
            
            // Hide only the submit button and form inputs, keep social sharing visible
            const form = document.getElementById('pledge-form');
            const submitBtn = document.getElementById('submit-btn');
            const formInputs = form.querySelectorAll('input, textarea, .checkbox-group');
            
            // Hide form inputs and submit button
            formInputs.forEach(input => {
                input.style.display = 'none';
            });
            if (submitBtn) {
                submitBtn.style.display = 'none';
            }
            
            // Hide the entire add-pledge-section heading and description
            const addPledgeSection = document.querySelector('.add-pledge-section');
            if (addPledgeSection) {
                const heading = addPledgeSection.querySelector('h3');
                const description = addPledgeSection.querySelector('p');
                if (heading) heading.style.display = 'none';
                if (description) description.style.display = 'none';
            }
            
            // Show success message
            successMsg.style.display = 'block';
            
            // Scroll to success message
            successMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            
            // Social sharing section should remain visible (it's outside the form)
        } else {
            throw new Error(data.error || 'Failed to submit pledge');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('There was an error submitting your pledge. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Add My Pledge';
    }
}

// Add pledge to feed
function addPledgeToFeed(pledge) {
    const container = document.getElementById('pledges-container');
    
    // Remove empty state if present
    const emptyState = container.querySelector('.empty-state');
    if (emptyState) {
        emptyState.remove();
    }
    
    // Create pledge card
    const card = document.createElement('div');
    card.className = 'pledge-card';
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    
    const date = new Date(pledge.date || Date.now());
    const dateStr = date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    card.innerHTML = `
        <div class="pledge-name">${escapeHtml(pledge.name)}</div>
        ${pledge.message ? `<div class="pledge-message">${escapeHtml(pledge.message)}</div>` : ''}
        <div class="pledge-date">Pledged on ${dateStr}</div>
    `;
    
    // Insert at top
    container.insertBefore(card, container.firstChild);
    
    // Animate in
    setTimeout(() => {
        card.style.transition = 'all 0.3s ease-out';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 10);
}

// Load pledge count from server (pledge feed removed per user request)
async function loadPledges() {
    // Function renamed but kept for compatibility
    // No longer displays recent pledges - only updates counter
    try {
        const response = await fetch('/api/get-pledge-count.php');
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.count) {
                if (data.count > pledgeCount) {
                    pledgeCount = data.count;
                    updateCounter();
                }
            }
        }
    } catch (error) {
        console.error('Error loading pledge count:', error);
    }
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    updateCounter();
    loadPledges();
    
    // Update counter every 30 seconds (in case others are adding pledges)
    setInterval(() => {
        fetch('/api/get-pledge-count.php')
            .then(r => r.json())
            .then(data => {
                if (data.success && data.count > pledgeCount) {
                    pledgeCount = data.count;
                    updateCounter();
                }
            })
            .catch(() => {});
    }, 30000);
});

// Make submitPledge available globally
window.submitPledge = submitPledge;

