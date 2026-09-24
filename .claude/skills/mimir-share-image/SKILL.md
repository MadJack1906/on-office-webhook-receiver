---
name: mimir-share-image
description: Share images with the user natively in the task chat by saving them to .mimir/shared-images/.
---

# Sharing images with the user

To show the user a visual (screenshot, diagram, rendered UI, chart), save it into the shared-images outbox:

    .mimir/shared-images/

Rules:
- Flat files only, one image per file. Use descriptive filenames (login-page.png, error-toast.webp).
- Supported formats: PNG, JPEG, GIF, WebP, AVIF. Max 20 MB per file.
- Save BEFORE you finish your turn: Mimir collects the outbox when the turn ends and attaches the images to your chat reply automatically.
- Do not stop early to wait for the user to confirm receipt — finish your work; delivery is automatic.

Good use cases: screenshots of UI you built or fixed, diagrams that explain an architecture, before/after comparisons, charts from data you analyzed.
