# Choose90 Navigation Structure

## 📋 Final Organization

### Main Navigation:
1. **Home** - `/`
2. **Our Story** - `/about.html`
3. **Pledge** (Dropdown) - `/pledge/`
4. **Resources** (Dropdown) - `/resources-hub.html`
5. **Chapters** - `/chapters/`
6. **Log In** - `/login/`
7. **Donate** - `/donate/`

---

## 🎯 Pledge Dropdown Menu:

- **Take the Pledge** - `/pledge/` (main pledge form)
- **Pledge Wall** - `/pledge-wall.html` (see 3,363+ pledges)
- **New Year's Resolution** - `/new-years-resolution.html` (seasonal campaign)

---

## 📚 Resources Dropdown Menu:

### Main:
- **Resources Hub** - `/resources-hub.html` (all resources)

### Tools:
- **Content Generator** - `/tools/content-generator.html` (AI post generator)

### Challenges:
- **30-Day Challenge** - `/resources/30-day-choose90-challenge.html`
- **7-Day Kwanzaa Challenge** - `/kwanzaa-challenge.html`

### Special Events:
- **Kwanzaa & Choose90** - `/kwanzaa-choose90.html`
- **New Year's Resolution** - `/new-years-resolution.html`

---

## 📍 Where Everything Lives:

### **Pledge Section:**
- `/pledge/` - Main pledge form (WordPress)
- `/pledge-wall.html` - Interactive wall with counter
- `/new-years-resolution.html` - New Year's campaign

### **Resources Section:**
- `/resources-hub.html` - Main resources page
- `/tools/content-generator.html` - AI content tool
- `/resources/30-day-choose90-challenge.html` - 30-day challenge
- `/kwanzaa-choose90.html` - Kwanzaa landing page
- `/kwanzaa-challenge.html` - 7-day Kwanzaa challenge

### **Tools/Installation:**
- `/pwa.html` - Extension & PWA installation guide

---

## 🎨 Visual Structure:

```
Home | Our Story | Pledge ▼ | Resources ▼ | Chapters | Log In | Donate
                      │              │
        ┌─────────────┴──────┐      └─────────────────────────┐
        │                     │                               │
   Take Pledge         Pledge Wall              Resources Hub
   New Year's                                  Content Generator
                                              30-Day Challenge
                                              7-Day Kwanzaa
                                              Kwanzaa & Choose90
                                              New Year's
```

---

## ✅ Implementation:

1. Update `static-header.html` with dropdown structure
2. Add CSS for dropdown menus
3. Add JavaScript for hover/click behavior
4. Update all pages to use new header
5. Test on mobile (dropdowns should work on touch)


