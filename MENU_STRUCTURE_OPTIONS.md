# Menu Structure Options - After Replacing "Pledge" with "Join"

## Current State (Before Change)
- When **NOT logged in**: Shows "Pledge" dropdown + "Log In" link
- When **logged in**: Hides "Pledge" dropdown, shows "My Account" dropdown

## After Changing "Pledge" to "Join"

We have several options:

---

## Option 1: Keep Separate (Recommended) ⭐

### When NOT Logged In:
- **"Join"** dropdown (with "Join the Movement", etc.)
- **"Log In"** link (separate)

### When Logged In:
- Hide "Join" dropdown
- Hide "Log In" link
- Show **"My Account"** dropdown (with "My Account", "Log Out")

### Pros:
- ✅ Clear separation of actions
- ✅ "Join" is for new users, "Log In" is for existing users
- ✅ Less confusing - users know exactly where to go
- ✅ Follows common web patterns

### Cons:
- ⚠️ Takes up slightly more menu space
- ⚠️ Two separate menu items

---

## Option 2: Combine into One Menu

### When NOT Logged In:
- **"Join / Log In"** dropdown with:
  - "Join the Movement" (links to /pledge/)
  - "Log In" (links to /login/)
  - Divider
  - "New Year's Resolution"

### When Logged In:
- Hide "Join / Log In" dropdown
- Show **"My Account"** dropdown

### Pros:
- ✅ Saves menu space
- ✅ Single menu item
- ✅ All auth-related actions in one place

### Cons:
- ⚠️ Less clear - mixing new user and existing user actions
- ⚠️ "Join" and "Log In" are different actions
- ⚠️ Could be confusing for users

---

## Option 3: Smart Single Menu Item

### When NOT Logged In:
- **"Join"** dropdown that shows:
  - "Join the Movement (New Users)" - /pledge/
  - "Log In (Existing Users)" - /login/
  - Divider
  - "Join Wall"
  - "New Year's Resolution"

### When Logged In:
- Hide "Join" dropdown
- Show **"My Account"** dropdown

### Pros:
- ✅ Saves menu space
- ✅ Still clear with labels
- ✅ Single menu item

### Cons:
- ⚠️ Still mixing two different actions
- ⚠️ "Join" as menu label doesn't include "Log In"

---

## My Recommendation: **Option 1 - Keep Separate** ⭐

### Why?
1. **Clarity**: "Join" and "Log In" are fundamentally different actions
   - Join = New user creating account
   - Log In = Existing user accessing account

2. **User Experience**: Users know exactly where to go:
   - New users → "Join"
   - Existing users → "Log In"

3. **Common Pattern**: Most websites keep these separate
   - Examples: Facebook, Twitter, LinkedIn, etc.

4. **When Logged In**: Both are hidden anyway, so it doesn't matter

---

## Implementation Details

### Current Behavior (Should Continue):
- When **NOT logged in**:
  - Show "Join" dropdown
  - Show "Log In" link
  
- When **logged in**:
  - Hide "Join" dropdown ✅ (already implemented)
  - Hide "Log In" link ✅ (already implemented)
  - Show "My Account" dropdown ✅ (already implemented)

### What We Need to Update:
1. Change "Pledge" → "Join" in navigation
2. Change "Take the Pledge" → "Join the Movement"
3. Ensure hiding logic still works (it should - same IDs)

---

## Alternative: If Menu Space is Limited

If menu space is an issue, we could:

### Option 4: Conditional Single Item

**When NOT logged in:**
- Show "Join / Log In" as a single menu item
- Dropdown contains both options

**When logged in:**
- Hide "Join / Log In"
- Show "My Account"

This saves space while keeping things organized.

---

## Summary

**Recommended: Keep "Join" and "Log In" separate** ✅

- Clear and intuitive
- Follows web conventions
- Better user experience
- Already works correctly when logged in

**If space is tight: Combine them into "Join / Log In" dropdown**

What do you think? Should we keep them separate or combine them?
