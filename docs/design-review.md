# Design review: reference sites vs. Zero to Rank (v1.0 → v1.1)

Reviewed 5 Nov 2026 at 1440×900 desktop and 390×844 mobile, scrolling each page step by step so scroll-triggered motion would play.
The reference sites were used only to study their visual language. Nothing was copied from them: no layouts, assets, copy or brand elements.

| Reference | What it does well |
|---|---|
| aovix.framer.website | A giant display wordmark in the hero; blur-to-sharp word reveals on headings; pill tags with a dot; a thin rule under the nav with a caret marking the active item; logo marquee with faded edges; corner-dot "bracket" cards |
| ai-kit-preview.framer.website | Centred nav links inside a bordered pill; a CTA wrapped in a second frame; a radial glow behind the hero; dark bento cards with a 1px border and an inner gradient glow; product screens framed as app windows |
| knotch.framer.ai | The hero is an inset, rounded panel on a dark page; very tight display tracking (around −0.04em); oversized ghost numerals behind a statement; numbered step cards; consistent rounded sections and a calm, slow reveal rhythm |

## Gaps found in v1.0

| Area | v1.0 | Gap |
|---|---|---|
| Typography | Strong hero; H2 tracking at −0.04em | Display type was slightly loose. Section heads appeared all at once rather than unfolding |
| Spacing | Generous, consistent | Fine. The page lacked one quiet "breathing" moment between the trust bar and the data-heavy sections |
| Cards | Pearl cards with a lift on hover | No light or material response to the pointer, and no signature detail |
| Nav | Full-width bar that turned solid on scroll; the current link was marked with a dot | Read as a utility bar, not a crafted object. No capsule or sliding indicator |
| Transitions | Hard colour cuts between dark and pearl sections | Sections didn't feel like layered sheets, and the hero simply scrolled away |
| Scroll effects | Timeline fill, count-ups, glow parallax | No scrubbed storytelling moment |

## What changed in v1.1 (kept original)

- **Nav capsule.** On desktop, the links sit in a centred glass pill with a sliding indicator that follows hover and focus and rests on the section in view. On scroll, the whole bar condenses into one floating capsule. The mobile menu is unchanged.
- **Receding hero.** The hero has rounded bottom corners. As you scroll past, it insets into a panel and its content drifts back.
- **Statement section** (new, `statement.php`). A point-of-view line fills in word by word as you scroll. The closing phrase gets a lime highlighter. It contains no figures or claims.
- **Word-by-word heading reveal.** Words go from blurred to sharp. Inline `<em>` and `<br>` are kept.
- **Sheets.** Dark sections and the footer are inset, rounded panels on the pearl page, and they settle from 95% scale as they enter.
- **Cards.** A pointer-following lime spotlight (`data-ztr-spot`) plus our own "focus brackets" (`.ztr-brackets`): viewfinder corners that lock onto the card, which is a visual metaphor for being found and chosen.
- **Type and pills.** H2 tracking is tightened to −0.048em. The numbered eyebrow chips now sit inside an outlined pill.

All of this is skipped under `prefers-reduced-motion`, and the page reads fully without JavaScript.
Lighthouse (mobile, local): Performance 98, Accessibility 100, Best Practices 100, SEO 100, CLS 0.

## v1.2: cleaner, proof-led

Feedback: the page still read as "AI-made", and it needed real proof.

- New palette: Ink `#010102`, Lavender `#AD96F1`, Mint `#58F6B1`. The whole page now sits on the ink base, and the inset panels are a slightly lifted surface.
- Removed the "AI look": the animated fake search panel in the hero, glows, the grid backdrop, pulsing dots, shimmering placeholder lines, the typing caret and the illustrative chart.
- Centred hero in the style of the references, with three real results underneath that open the matching case study.
- Case studies replace the illustrative dashboard. Each one shows its headline metrics, AI-assistant sources from GA4, and the original Search Console / GA4 screenshots as evidence. Client names are withheld.
