# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

# Kristi Hemmer Website Project

## Project Overview
Professional website for women's leadership speaker, coach, and author Kristi Hemmer.
Tagline: "Where MOXIE becomes POWER"

## Tech Stack
- Framework: Astro (latest version)
- Styling: Tailwind CSS
- Fonts: Roc Grotesk (headers), Open Sans (body), Arsilon (script accents)

## Brand Identity

### Colors (Always use these exact values)
- **Moxie Pink** (Primary): #E91C7E / #D94780
- **Fierce Flamingo**: #F096C1
- **Sunny Yellow** (Accent): #FCEC60
- **Charcoal Black**: #000000
- **White/Cream**: Backgrounds

### Design Principles
- Clean, modern layout (reference: corrielo.com)
- Bold but professional
- Empowering and authentic
- Mobile-first responsive design
- Use graphic elements: starburst shapes, bold text overlays, black-and-white photo collages

### Typography Style
- Headers: Bold, large, impactful (Roc Grotesk)
- Body: Clean, readable (Open Sans)
- Callouts: "Moxie On!" uses Arsilon script font
- Avoid excessive bullet points in prose sections

## Site Structure

### Pages Required
1. **Homepage** - Hero, MOXIE definition, quiz, testimonials, client logos, CTA
2. **About Kristi** - Story, video, UN Global Goals, credentials
3. **Services Hub ("Changemake with Kristi")**
   - Keynotes & Workshops
   - Individual Coaching (PoWer Course)
   - Retreats & Travel
   - Emcee Services
4. **MOXIE ON** - Action/CTA landing page
5. **Contact/Inquiry** forms

### Interactive Features
- MOXIE Quiz (4 questions, routes to service recommendations)
- Newsletter signup (Substack integration)
- Contact/inquiry forms for different services
- Direct payment buttons for coaching sessions

## Component Guidelines

### Header
- Logo (left) - use LogoPink.png
- Navigation menu (Speaking, Coaching, Book, Substack)
- "MOXIE ON" CTA button (right)
- Mobile hamburger menu

### Footer
- Quick links to services
- Social media (LinkedIn, Substack)
- Newsletter signup
- Copyright info

### Buttons/CTAs
- Primary: Pink background (#E91C7E), white text, rounded corners
- Secondary: Yellow background (#FCEC60), black text
- Always include clear action text: "Book Kristi", "Join PoWer Camp", "Take the Quiz"

### Cards/Sections
- Use starburst graphic elements from brand assets
- Incorporate black-and-white photos with pink/yellow overlays
- Bold headlines with yellow or pink highlights

## Content Guidelines

### Voice & Tone
- Collaborative and inspiring (not arrogant or exclusive)
- Determined and strong (experienced, savvy, not calm or unaware)
- Genuine and trustworthy (fact-checked, intrepid, not reckless)
- Use "MOXIE" prominently - it means guts, pluck, grit, courage

### Key Messages
- "When women lead, the world is a safer and more equitable place for everyone"
- "Be unapologetic"
- "Challenge how things have always been done"
- Emphasis on disrupting systems of power for gender equity

## Asset Locations
- Logos: `/public/logos/LogoPink.png`
- Photos: `/public/images/` (Kristi travel photos, speaking events, mentoring)
- Brand elements: `/public/images/` (gradient backgrounds, starburst shapes)

## Development Notes
- Optimize for International Women's Day speaking opportunities
- SEO focus: women's leadership, keynote speaker, executive coaching, gender equity
- Fast loading performance critical
- Mobile responsiveness essential
- Form submissions need backend handling (specify service)

## Quiz Logic
4 questions (A/B/C/D answers) that route to:
- **Result A**: Speaker Spark (Speaking & Training)
- **Result B**: Coaching Glow-Up (Individual Coaching)
- **Result C**: Trailblazer Soul (PoWer Camp Retreats)
- **Result D**: Reflection Mode (Substack/Book Club)

## Reference Documents
- Brand guidelines in project files
- Website content in project documents
- Social media templates for design consistency