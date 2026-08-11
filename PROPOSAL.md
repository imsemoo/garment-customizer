# Upwork Proposal — Clothing Customization Module (Fabric.js + Core PHP)

> Paste the text below into Upwork. Replace the two [BRACKETED] placeholders
> with your own links before sending. Attach `mockup-front-navy.png` (export one
> from the demo) as the proposal image.

---

Hi,

Instead of describing what I *would* build, I built a working demo of your module before writing this proposal:

**Live demo: [LINK — host the `garment-customizer` folder anywhere static, e.g. yourdomain.com/demo]**

It already covers your three core requirements:

- **Artwork upload & positioning** — users upload PNG/JPG/SVG (button or drag-and-drop) and get full Fabric.js controls: drag, resize, rotate, flip, duplicate, layer order, keyboard nudging, plus CustomInk-style touches like a dashed print-area boundary and center-snap guides.
- **Garment color selection that updates the illustration** — 18 colors recolor a layered SVG tee instantly. Shading, hems and wrinkles are separate overlay layers, so the garment keeps realistic depth in every color, and new text automatically defaults to white on dark garments.
- **Polished UX** — front/back views with independent designs, undo/redo, text tool with fonts/outline, live per-unit pricing with bulk breaks, and high-res PNG mockup export.

**How I'd integrate it into your core PHP environment**

1. **Front end:** the customizer is a self-contained JS module (Fabric.js, no framework lock-in). It mounts into any PHP-rendered page and reads product/color/print-area config from a JSON block your PHP templates output.
2. **Design persistence:** every design serializes to Fabric JSON per side. A core-PHP endpoint validates and stores it (MySQL), returning a design ID that flows into your cart/order pipeline. The demo ships with a sample `save-design.php` showing the exact contract.
3. **Artwork handling:** uploads go through a PHP endpoint that validates MIME/size, re-encodes images (strips EXIF/payloads), and returns hosted URLs — keeping design JSON small and safe.
4. **Production output:** the same design JSON renders server-side into print-ready 300-DPI files for the print shop, so what the customer sees is exactly what gets printed.

**Relevant work:** [LINK — your portfolio / past customizer or e-commerce projects]

Happy to walk you through the demo code on a call — every line is mine and written this week.

Two quick questions so I can scope accurately:
1. Which PHP setup is the module integrating into — custom code, CodeIgniter, or a CMS?
2. Do you need print-ready output generation (vector/300-DPI) in phase 1, or is on-screen mockup + design data enough to start?

Best regards,
[Name]

---

## Suggested milestones (for the contract, not the cover letter)

| # | Milestone | Scope |
|---|-----------|-------|
| 1 | Core customizer | Upload, position/scale/rotate, garment recolor, print-area clipping, front/back |
| 2 | PHP integration | Save/load designs, artwork upload pipeline, cart/order hookup |
| 3 | Polish + output | Text tool, pricing rules, mockup export, server-side print files, QA on mobile |
