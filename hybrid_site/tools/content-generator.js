/**
 * Choose90 Content Generator
 * AI-powered post and quote generation
 */

// Templates database
const templates = {
    gratitude: [
        "Today I'm grateful for the small moments of joy that make life beautiful. What are you grateful for today? #Choose90 #Gratitude",
        "Taking a moment to appreciate the people who make my world brighter. Thank you for being you! #Choose90",
        "Gratitude turns what we have into enough. Today, I'm choosing to focus on all the good in my life. #Choose90"
    ],
    encouragement: [
        "You're doing better than you think. Keep going! 💪 #Choose90",
        "Remember: progress, not perfection. Every step forward counts. #Choose90",
        "You have the strength to handle whatever comes your way today. I believe in you! #Choose90"
    ],
    celebration: [
        "Celebrating the wins, big and small! Today was a good day. 🎉 #Choose90",
        "Sometimes we forget to celebrate ourselves. Today, I'm choosing to recognize how far I've come. #Choose90",
        "Here's to the people who lift others up! You know who you are. 🌟 #Choose90"
    ],
    inspiration: [
        "Be the reason someone feels seen, heard, and valued today. #Choose90",
        "Small acts of kindness create ripples of positivity. What will you do today? #Choose90",
        "Your words have power. Use them to lift others up. #Choose90"
    ],
    community: [
        "Together, we can make the internet a more positive place. Join the movement: #Choose90",
        "Community is built one positive interaction at a time. Let's build something beautiful together. #Choose90",
        "When we choose to be 90% positive, we create space for everyone to thrive. #Choose90"
    ],
    'new-year': [
        "My New Year's resolution is simple: Choose90. Choosing to make most of my online influence positive, uplifting, and constructive. Join me! #Choose90",
        "This year, I'm choosing positivity. 90% positive influence. Intentional, uplifting, human. #Choose90",
        "2026: The year I choose to be 90% positive online. Who's with me? #Choose90"
    ]
};

// Load templates
function loadTemplates() {
    const category = document.getElementById('template-category').value;
    const list = document.getElementById('templates-list');
    
    if (!category) {
        list.innerHTML = '<p style="color: #6b7280; text-align: center; padding: 40px;">Select a category to see templates</p>';
        return;
    }
    
    const categoryTemplates = templates[category] || [];
    
    if (categoryTemplates.length === 0) {
        list.innerHTML = '<p style="color: #6b7280; text-align: center; padding: 40px;">No templates available for this category</p>';
        return;
    }
    
    list.innerHTML = categoryTemplates.map((template, index) => `
        <div style="background: #f9fafb; border-left: 4px solid #667eea; padding: 20px; border-radius: 8px; margin-bottom: 16px;">
            <p style="margin: 0 0 12px 0; font-size: 16px; line-height: 1.6; color: #1f2937;">${template}</p>
            <button class="action-btn copy-btn" onclick="copyTemplate(${index}, '${category}')" style="font-size: 14px; padding: 8px 16px;">📋 Copy</button>
        </div>
    `).join('');
}

// Copy template
function copyTemplate(index, category) {
    const template = templates[category][index];
    navigator.clipboard.writeText(template).then(() => {
        const btn = event.target;
        const original = btn.textContent;
        btn.textContent = '✓ Copied!';
        btn.classList.add('copied');
        setTimeout(() => {
            btn.textContent = original;
            btn.classList.remove('copied');
        }, 2000);
    });
}

// Generate post
async function generatePost() {
    const topic = document.getElementById('post-topic').value.trim();
    const tone = document.getElementById('post-tone').value;
    const platform = document.getElementById('post-platform').value;
    
    if (!topic) {
        alert('Please enter what your post is about!');
        return;
    }
    
    // Show loading
    document.getElementById('loading-post').classList.add('active');
    document.getElementById('result-post').classList.remove('active');
    document.querySelector('#tab-post .generate-btn').disabled = true;
    
    try {
        const response = await fetch('/api/generate-content.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'post',
                topic: topic,
                tone: tone,
                platform: platform
            })
        });
        
        if (!response.ok) {
            throw new Error('Failed to generate post');
        }
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('result-post-text').textContent = data.content;
            document.getElementById('result-post').classList.add('active');
            
            // Track content generation
            if (typeof window.trackContentGeneratorUsed === 'function') {
                window.trackContentGeneratorUsed('post_suggestion');
            }
        } else {
            throw new Error(data.error || 'Failed to generate post');
        }
    } catch (error) {
        console.error('Error:', error);
        // Fallback to simple generation
        const fallback = generateFallbackPost(topic, tone);
        document.getElementById('result-post-text').textContent = fallback;
        document.getElementById('result-post').classList.add('active');
    } finally {
        document.getElementById('loading-post').classList.remove('active');
        document.querySelector('#tab-post .generate-btn').disabled = false;
    }
}

// Generate quote
async function generateQuote() {
    const topic = document.getElementById('quote-topic').value.trim();
    
    // Show loading
    document.getElementById('loading-quote').classList.add('active');
    document.getElementById('result-quote').classList.remove('active');
    document.querySelector('#tab-quote .generate-btn').disabled = true;
    
    try {
        const response = await fetch('/api/generate-content.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type: 'quote',
                topic: topic || 'general'
            })
        });
        
        if (!response.ok) {
            throw new Error('Failed to generate quote');
        }
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('result-quote-text').textContent = data.quote;
            document.getElementById('result-quote-author').textContent = data.author || '— Choose90';
            document.getElementById('result-quote').classList.add('active');
            
            // Track content generation
            if (typeof window.trackContentGeneratorUsed === 'function') {
                window.trackContentGeneratorUsed('quote');
            }
        } else {
            throw new Error(data.error || 'Failed to generate quote');
        }
    } catch (error) {
        console.error('Error:', error);
        // Fallback to predefined quotes
        const fallback = getFallbackQuote(topic);
        document.getElementById('result-quote-text').textContent = fallback.quote;
        document.getElementById('result-quote-author').textContent = fallback.author;
        document.getElementById('result-quote').classList.add('active');
    } finally {
        document.getElementById('loading-quote').classList.remove('active');
        document.querySelector('#tab-quote .generate-btn').disabled = false;
    }
}

// Fallback post generation (if API fails)
function generateFallbackPost(topic, tone) {
    const tones = {
        uplifting: "I'm so inspired by",
        encouraging: "Here's something that might help",
        grateful: "I'm grateful for",
        inspiring: "This reminds me that",
        celebratory: "Celebrating"
    };
    
    const start = tones[tone] || "I wanted to share";
    return `${start} ${topic}. It's moments like these that remind us of the good in the world. #Choose90`;
}

// Fallback quotes
const fallbackQuotes = [
    { quote: "Be the reason someone feels seen, heard, and valued today.", author: "— Choose90" },
    { quote: "Your words have power. Use them to lift others up.", author: "— Choose90" },
    { quote: "Small acts of kindness create ripples of positivity.", author: "— Choose90" },
    { quote: "Choose to be 90% positive. The other 10%? That's being human.", author: "— Choose90" },
    { quote: "Every post is a chance to add light to someone's day.", author: "— Choose90" },
    { quote: "When we choose encouragement over outrage, we make the internet a better place.", author: "— Choose90" }
];

function getFallbackQuote(topic) {
    return fallbackQuotes[Math.floor(Math.random() * fallbackQuotes.length)];
}

