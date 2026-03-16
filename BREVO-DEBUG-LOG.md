# Brevo Quiz Integration - Debug Log
**Last updated: 2026-03-11**

---

## Current Status: QUIZ_RESULT attribute not saving to contact record

The quiz form is working end-to-end (success screen shows, contact is added to Brevo list), but the `QUIZ_RESULT` attribute value is not appearing on the contact record in Brevo.

---

## What Is Working
- Quiz modal opens, questions display, scoring works correctly
- Email form submits to `/api/subscribe.php` on Cloudways
- PHP reads `.env` file from `public_html/` and parses API key correctly
- Brevo API returns 201 or 204 (contact created/updated successfully)
- Success screen shows with correct chapter result, download link, and Substack CTA

## What Is NOT Working
- `QUIZ_RESULT` text value (`chapter3`, `chapter8`, or `chapter9`) is not appearing on the Brevo contact record after submission

---

## What Has Been Ruled Out
- ✅ Payload is correct — DevTools confirms `{ email: "...", quizResult: "chapter3" }` is sent
- ✅ Attribute name matches — Brevo Contact Attributes shows `QUIZ_RESULT` as **Text** type (was previously Category/multi-select, now fixed)
- ✅ PHP is sending `'QUIZ_RESULT' => $body['quizResult']` in the attributes object
- ✅ Brevo returns 2xx success status

## Most Likely Remaining Cause
The contact already existed in Brevo (removing from a **list** does not delete the **contact record**). When `updateEnabled: true` is used on an existing contact, Brevo may return 204 but silently skip updating the `QUIZ_RESULT` attribute — possibly a Brevo quirk with attribute updates on existing contacts.

---

## Next Debugging Steps

### Step 1: Check the debug response
The current `subscribe.php` (already on server) returns debug info on success:
```json
{ "success": true, "debug_status": 201, "debug_response": "..." }
```
- Submit the quiz
- Open DevTools → Network → click `subscribe.php` → **Response** tab
- Note the `debug_status` value:
  - `201` = new contact created
  - `204` = existing contact updated
- Note the `debug_response` value (may be empty for 204)

### Step 2: Force a fresh contact (most likely fix)
- In Brevo → Contacts, **search for the test email**
- Click the contact → **Delete contact** (full delete, not just remove from list)
- Re-submit the quiz with the same email
- Check if `QUIZ_RESULT` now appears on the new contact record

### Step 3: If Step 2 works but Step 1 shows 204 for existing contacts
The issue is that Brevo doesn't update attributes on existing contacts via `updateEnabled: true`. Fix options:
- **Option A**: Use the Brevo "Update Contact" endpoint (`PUT /v3/contacts/{email}`) separately after the initial create call
- **Option B**: Accept that QUIZ_RESULT only populates for new contacts (reasonable for a lead capture flow)

### Step 4: Once QUIZ_RESULT is confirmed working
Remove debug output from `subscribe.php`:
```php
// Change this:
echo json_encode(['success' => true, 'debug_status' => $statusCode, 'debug_response' => $response]);
// Back to this:
echo json_encode(['success' => true]);
```

---

## File Locations

| File | Purpose |
|------|---------|
| `src/components/BookQuiz.astro` | Quiz UI + JS logic + success screen |
| `public/api/subscribe.php` | Production API endpoint (Cloudways) |
| `src/pages/api/subscribe.ts` | Dev-only Astro API route (not used in production) |
| `public/.htaccess` | Blocks direct browser access to `.env` |
| `.env` | Contains `BREVO_API_KEY` and `BREVO_LIST_ID` (also uploaded to `public_html/` on Cloudways) |

## Cloudways Notes
- PHP version: 8.0+ (was 7.4, upgraded during this session)
- `putenv()` is disabled — env vars must be read directly from `.env` file via PHP `file()` parsing
- No Environment Variables panel available for PHP apps in Cloudways dashboard
- Varnish caching is enabled (purge cache after uploading PHP file changes)

---

## Brevo Setup
- List ID: configured in `.env` as `BREVO_LIST_ID`
- Contact attribute `QUIZ_RESULT`: **Normal Attribute**, **Text** type (previously Category — was changed during this session)
- Transactional emails for chapter delivery: already configured in Brevo (not yet tested end-to-end due to QUIZ_RESULT issue)
