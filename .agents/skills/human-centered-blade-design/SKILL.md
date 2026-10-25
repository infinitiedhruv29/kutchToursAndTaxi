---
name: human-centered-blade-design
description: Designs and improves professional, customer-centered Laravel Blade frontends that feel deliberately human-designed rather than generic AI templates. Use for new pages, redesigns, UI polish, responsive improvements, and frontend reviews in Laravel projects using Blade, HTML, CSS, and JavaScript.
---

# Human-Centered Laravel Blade Design

## Mission

Create professional, clear, attractive, customer-centered websites that feel intentionally art-directed for their specific business. Avoid the recognizable sameness of generic AI-generated interfaces. Good design is not decoration: it helps real customers understand, trust, navigate, and complete their tasks.

This skill is for Laravel projects whose frontend is built with Blade templates, HTML, CSS, and JavaScript. Work with the project's existing stack. Do not migrate frameworks or introduce a new design system without a specific, justified need.

## Non-negotiable design principles

1. **Design for the business, not for a trend.** Understand the business model, audience, context, content, brand, and main customer tasks before choosing a visual direction.
2. **Simple, polished, professional.** Favor clarity, proportion, typography, good imagery, spacing, and alignment over decorative effects.
3. **Human judgment over template patterns.** Every section must have a reason to exist. Vary layouts where content genuinely differs; do not force every section into identical cards.
4. **Customer-first usability.** Make navigation understandable, content scannable, important actions obvious, and forms easy to complete.
5. **Restrained geometry.** Default to square or gently rounded corners. Use modest radii consistently; avoid making every surface, image, button, input, and container pill-shaped or heavily rounded.
6. **Realistic content.** Use the actual content and business facts available. Never invent testimonials, awards, ratings, statistics, customer counts, certifications, or promises.
7. **Responsive from the start.** Treat mobile as a primary experience, not a desktop layout squeezed into a small screen.
8. **Preserve working behavior.** Design improvements must not silently break Laravel routes, validation, authentication, permissions, forms, payments, filters, or JavaScript interactions.

## First: inspect before changing

Before editing, inspect the existing project and identify:

- Laravel version and folder structure.
- Relevant routes, controllers, Blade layouts, includes, components, and page templates.
- Existing CSS approach, design tokens, JavaScript, installed libraries, asset pipeline, and build commands.
- Existing brand assets, logo, fonts, color palette, imagery, and page content.
- Existing interactions and backend expectations that must remain unchanged.
- Whether the task is a new page, a targeted refinement, or a larger redesign.

Do not assume Tailwind, Bootstrap, Alpine, Livewire, Vite, or any other tool is present. Check first. Reuse the existing approach and avoid parallel CSS frameworks or unnecessary dependencies.

If the request is ambiguous in a way that materially affects the design, ask one concise question. Otherwise, make reasonable decisions based on the codebase and explain any important assumptions.

## Establish a visual direction

Before implementation, define a compact design direction based on the specific project:

- **Audience and intent:** Who visits this page, what do they need, and what is the primary task?
- **Character:** e.g. trustworthy and practical, refined and editorial, warm and local, technical and precise, or calm and service-oriented.
- **Visual hierarchy:** What should be noticed first, second, and third?
- **Typography:** Choose a readable type system with a clear hierarchy. Avoid using oversized display typography everywhere.
- **Color:** Use a restrained palette that supports the brand, contrast, and information hierarchy. Do not default to purple/blue gradients or use gradients merely to make a page feel modern.
- **Layout:** Use a consistent spacing rhythm, strong alignment, sensible content widths, and deliberate variation when the content calls for it.
- **Imagery:** Select relevant, credible images with consistent art direction and intentional crops.
- **Components:** Define a small set of reusable patterns for buttons, links, forms, navigation, alerts, tables, cards (only where useful), and section spacing.

Do not apply the same visual recipe to every business. A footwear store, SaaS product, healthcare service, corporate website, and admin panel should not all look alike.

## Avoid generic AI-generated aesthetics

Do not automatically produce the following patterns:

- A giant centered headline, vague slogan, gradient background, and two oversized buttons as the default hero.
- Purple/blue/pink gradients without a brand-specific reason.
- Glassmorphism, glow effects, floating blobs, decorative orbs, and excessive shadows as filler.
- A grid of identical rounded cards for every kind of content.
- Pills for every label, navigation item, filter, and button.
- Excessive border radii, bubbly UI, oversized whitespace that hides useful information, or arbitrary asymmetry.
- Repeated three-column feature grids with generic line icons and vague benefit copy.
- Fake dashboard metrics, fabricated social proof, fake reviews, invented customer logos, or placeholder marketing claims presented as real.
- Unnecessary animations, scroll hijacking, parallax, autoplay media, or motion that delays access to content.
- Emoji used as a substitute for a consistent icon set.
- Generic stock images that do not match the product, audience, context, or copy.
- Unmotivated font mixing, too many colors, random font weights, inconsistent spacing, and one-off component styles.
- Copy such as “Revolutionize your journey” or “Unlock the power of…” unless it is genuinely appropriate to the brand.

These are warning signs, not an inflexible ban. Use any pattern only when it has a clear purpose and fits the brand.

## Corner radius and shape policy

Use restrained, consistent geometry as the default:

- Buttons and inputs: usually subtle rounding, not full pills.
- Cards and image containers: square or modestly rounded corners.
- Large panels: avoid excessive rounding that makes the UI feel toy-like.
- Pills: reserve for compact statuses, tags, or filters where the pattern improves comprehension.
- Shadows: subtle and functional; do not add shadows to every element.
- Borders and dividers: often preferable to shadows for a clean, professional structure.

Follow an existing, coherent brand system if the project already has one, unless the user asks to change it.

## Research and reference workflow

When web access or browsing tools are available and the task benefits from visual inspiration:

1. Research relevant, established websites in the same industry or serving a similar audience. Prefer live professional websites for real-world usability patterns.
2. Explore Dribbble, Pinterest, Awwwards, Behance, or similar galleries for visual ideas when accessible.
3. Look at references with a specific question in mind: navigation clarity, typography, content hierarchy, product presentation, forms, spacing, responsive behavior, or image treatment.
4. Compare several references rather than copying a single screen.
5. Record what is useful and why; translate the principle into the current brand and customer journey.
6. Verify that inspiration does not compromise readability, accessibility, performance, responsive behavior, or business credibility.
7. Do not claim to have visited or inspected a reference if browsing was unavailable. Do not fabricate URLs, screenshots, or design findings.
8. Do not copy a reference site pixel-for-pixel, reproduce its branding, or reuse its proprietary assets. Take inspiration from patterns and adapt them.

Research should inform decisions, not delay straightforward tasks. If a user supplies references, inspect those first.

## Customer-centered information architecture

- Put the information customers need most where they can find it quickly.
- Use labels customers understand rather than internal business jargon.
- Make primary and secondary actions visually distinct.
- Keep navigation concise and predictable.
- Keep forms short, clearly labelled, and helpful about errors.
- Explain empty, loading, success, and error states when applicable.
- Use meaningful link text and visible focus indicators.
- Do not add sales funnels, popups, banners, or conversion tactics that the user did not request.
- For informational or corporate websites, do not force e-commerce patterns such as “Shop Now” or “Add to Cart.”
- Avoid hiding essential details behind decorative tabs, carousels, or interactions without a strong reason.

## Mobile-first responsive behavior

Design and check the smallest supported screen first, then enhance larger layouts.

- Avoid horizontal overflow and clipped text.
- Ensure navigation remains easy to use with touch.
- Keep body text readable and line lengths comfortable.
- Make buttons and controls easy to tap without making every control oversized.
- Check image crops, heading wraps, table behavior, modal dimensions, sticky elements, and footer layout.
- Avoid desktop-only hover interactions for essential functionality.
- Use responsive layouts that adapt to content rather than simply shrinking fixed-width elements.
- Check at least a narrow mobile viewport and a typical desktop viewport when browser preview is available.

## Laravel Blade implementation rules

- Use Blade layouts, sections, includes, slots, and components in keeping with the project's existing conventions.
- Keep presentation in Blade/CSS and behavior in the project's existing JavaScript approach; do not move backend business logic into templates.
- Preserve route names, HTTP methods, CSRF protection, validation feedback, old input, authorization checks, and accessibility semantics.
- Use `@csrf` and existing method spoofing patterns where appropriate; never weaken security for visual convenience.
- Escape dynamic content by default with `{{ }}`. Use unescaped output only where trusted and sanitized HTML is explicitly required.
- Preserve existing IDs, data attributes, selectors, and event hooks that JavaScript depends on unless all references are updated.
- Avoid duplicated CSS, excessive `!important`, broad selectors that unexpectedly affect other pages, and unnecessary inline styles.
- Prefer existing CSS variables/design tokens. If the project lacks them, introduce a small, coherent set rather than scattered arbitrary values.
- Do not add a dependency just to achieve a minor visual effect.
- Keep assets local where practical and respect the existing build/deployment setup.
- Preserve browser compatibility consistent with the project requirements.

## Accessibility and performance

- Use semantic HTML landmarks and a logical heading hierarchy.
- Provide accessible labels for inputs and useful alternative text for meaningful images.
- Maintain sufficient contrast and visible keyboard focus.
- Ensure interactive controls are keyboard accessible and have understandable names.
- Respect reduced-motion preferences if adding animation.
- Optimize image dimensions and formats where feasible.
- Avoid layout shifts, heavy animation, unnecessary JavaScript, and oversized external assets.
- Treat accessibility and performance as part of design quality, not optional finishing touches.

## Required workflow for each task

### Phase 1 — Understand
Inspect the codebase, relevant pages, assets, current behavior, and existing design system. Identify the actual user problem rather than redesigning for novelty.

### Phase 2 — Plan
Write a short internal plan:
- customer goal,
- visual direction,
- layout and hierarchy,
- components to reuse,
- responsive considerations,
- behavior that must be preserved,
- reference research needed (if any).

Do not over-plan small changes.

### Phase 3 — Implement
Make cohesive, maintainable changes. Avoid changing unrelated files. Reuse existing patterns when they are good; improve them when there is a clear problem.

### Phase 4 — Verify
When tools are available:
- run the project's appropriate build or asset compilation command,
- check relevant routes/pages in the browser,
- inspect mobile and desktop layouts,
- test links, buttons, menus, forms, and important interactions,
- check console errors and obvious accessibility issues,
- fix issues found.

Do not claim a test or visual inspection was completed unless it actually was. If browser preview is unavailable, say so and provide the checks that remain.

### Phase 5 — Report
Summarize:
- what changed and why,
- important files changed,
- functionality preserved,
- checks actually run,
- known limitations or follow-up work.

## Final design review checklist

Before considering the task complete, ask:

- Does this look appropriate for this specific business and audience?
- Could this be mistaken for a generic AI landing-page template? If yes, what concrete patterns make it feel generic, and how can they be removed?
- Is the hierarchy clear within a few seconds?
- Are spacing, alignment, type, imagery, color, and corner radii consistent?
- Are rounded shapes and decorative effects restrained?
- Does each section earn its place?
- Is the content credible, specific, and easy to scan?
- Is the main customer task easy to complete?
- Does mobile feel intentionally designed, not merely compressed?
- Have existing Laravel behavior and security protections been preserved?
- Have claims about testing and reference research been kept honest?

The goal is not to make every website unusual. The goal is to make it clear, credible, refined, useful, and specific to its customers.
