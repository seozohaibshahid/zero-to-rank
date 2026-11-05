/**
 * Zero to Rank — animations.js
 * Scroll-linked and pointer motion, layered on top of a page that already
 * works without it:
 *   - Lenis smooth scrolling (self-hosted), synced with GSAP ScrollTrigger
 *   - Hero: words spread apart and the light sinks as the panel scrolls away;
 *     the light follows the pointer
 *   - Stacked case-study cards that sink back as the next one covers them
 *   - Statement line that fills word by word
 *   - Process progress line, CTA box scale-in, footer wordmark letters rising
 *   - Magnetic buttons, card tilt + spotlight, custom cursor (fine pointers)
 * Uses GSAP + ScrollTrigger when available; everything is skipped under
 * prefers-reduced-motion.
 */
(function () {
	'use strict';

	var ZTR = window.ZTR;
	if (!ZTR || !ZTR.root) {
		return;
	}
	var root = ZTR.root;
	var gsap = window.gsap;
	var ST = window.ScrollTrigger;
	var hasGsap = typeof gsap !== 'undefined' && typeof ST !== 'undefined';
	var reduced = ZTR.reduced();
	var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

	if (hasGsap) {
		gsap.registerPlugin(ST);
	}

	/**
	 * Scroll-progress helper: calls fn(progress 0–1) as `el` travels between
	 * two viewport positions (fractions of the viewport height, measured
	 * against the element's top and bottom). GSAP ScrollTrigger when loaded,
	 * otherwise a rAF-throttled scroll listener.
	 */
	function scrub(el, startAt, endAt, fn, endEdge) {
		if (hasGsap) {
			ST.create({
				trigger: el,
				start: 'top ' + Math.round(startAt * 100) + '%',
				end: (endEdge || 'bottom') + ' ' + Math.round(endAt * 100) + '%',
				onUpdate: function (self) {
					fn(self.progress);
				},
				onRefresh: function (self) {
					fn(self.progress);
				}
			});
			return;
		}
		var ticking = false;
		function measure() {
			var r = el.getBoundingClientRect();
			var vh = window.innerHeight;
			var a = r.top - vh * startAt;
			var b = (endEdge === 'top' ? r.top : r.bottom) - vh * endAt;
			fn(b - a > 0 ? Math.min(1, Math.max(0, -a / (b - a))) : 1);
			ticking = false;
		}
		window.addEventListener(
			'scroll',
			function () {
				if (!ticking) {
					ticking = true;
					window.requestAnimationFrame(measure);
				}
			},
			{ passive: true }
		);
		window.addEventListener('resize', measure);
		measure();
	}

	/* =====================================================================
	 * Statement: words fill in as the line scrolls through the viewport.
	 * ================================================================== */
	(function statement() {
		var text = root.querySelector('[data-ztr-scrub-text]');
		if (!text || reduced || typeof ZTR.splitWords !== 'function') {
			return;
		}
		var words = ZTR.splitWords(text, 'ztr-sw');
		var emph = text.querySelector('em');
		var n = words.length;
		if (!n) {
			return;
		}
		text.classList.add('is-split');
		var last = -1;
		scrub(text, 0.85, 0.45, function (p) {
			var reach = p * (n + 2); // a little run-out so the last word lands fully
			if (Math.abs(reach - last) < 0.01) {
				return;
			}
			last = reach;
			for (var i = 0; i < n; i++) {
				var f = Math.min(1, Math.max(0, reach - i));
				words[i].style.setProperty('--f', f.toFixed(3));
			}
			if (emph) {
				emph.classList.toggle('is-lit', p > 0.92);
			}
		});
	})();

	if (reduced || ZTR.editor) {
		return; // Everything below is movement only (and off in the Elementor editor).
	}

	/* =====================================================================
	 * Case studies: stacked cards. Each card sticks (CSS); as the next card
	 * slides up over it, the covered card sinks back and dims slightly.
	 * ================================================================== */
	(function stack() {
		var cards = Array.prototype.slice.call(root.querySelectorAll('[data-ztr-scard]'));
		if (cards.length < 2) {
			return;
		}
		var wide = window.matchMedia('(min-width: 961px)');
		var ticking = false;
		var tops = [];
		// A card taller than the space under the nav pins higher, so its
		// bottom edge stays on screen while the next card slides up.
		function measure() {
			var vh = window.innerHeight;
			tops = cards.map(function (card, i) {
				var stick = 100 + i * 24;
				var fit = vh - card.offsetHeight - 16;
				var top = wide.matches ? Math.min(stick, fit) : stick;
				card.style.top = wide.matches && top !== stick ? top + 'px' : '';
				return top;
			});
		}
		function update() {
			ticking = false;
			var vh = window.innerHeight;
			cards.forEach(function (card, i) {
				var next = cards[i + 1];
				if (!next || !wide.matches) {
					card.style.transform = '';
					card.style.filter = '';
					return;
				}
				var stick = tops[i];
				var gap = next.getBoundingClientRect().top - stick;
				var p = Math.min(1, Math.max(0, 1 - gap / (vh * 0.6)));
				card.style.transform = p > 0 ? 'scale(' + (1 - p * 0.06).toFixed(4) + ')' : '';
				card.style.filter = p > 0 ? 'brightness(' + (1 - p * 0.25).toFixed(3) + ')' : '';
			});
		}
		function request() {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		}
		window.addEventListener('scroll', request, { passive: true });
		window.addEventListener('resize', function () {
			measure();
			request();
		});
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(function () {
				measure();
				request();
			});
		}
		measure();
		update();
	})();

	/* =====================================================================
	 * Smooth scrolling (Lenis), driven by GSAP's ticker when present.
	 * ================================================================== */
	// Wheel and trackpad scrolling glides to a stop at every width; touch
	// keeps the device's native momentum scrolling.
	if (typeof window.Lenis !== 'undefined') {
		var lenis = new window.Lenis({ lerp: 0.085, smoothWheel: true, wheelMultiplier: 1 });
		ZTR.lenis = lenis;
		if (hasGsap) {
			lenis.on('scroll', ST.update);
			gsap.ticker.add(function (t) {
				lenis.raf(t * 1000);
			});
			gsap.ticker.lagSmoothing(0);
		} else {
			(function raf(t) {
				lenis.raf(t);
				window.requestAnimationFrame(raf);
			})(0);
		}
	}

	if (!hasGsap) {
		return;
	}

	/* =====================================================================
	 * Hero: words spread apart, the stage fades and the light sinks as the
	 * panel scrolls away (wide screens). The light follows the pointer.
	 * ================================================================== */
	(function hero() {
		var panel = root.querySelector('[data-ztr-hero]');
		if (!panel) {
			return;
		}
		var light = panel.querySelector('[data-ztr-hero-light]');
		var stage = panel.querySelector('[data-ztr-hero-stage]');
		var leave = { trigger: panel, start: 'top top', end: 'bottom top', scrub: true };
		var mm = gsap.matchMedia();
		mm.add('(min-width: 961px)', function () {
			gsap.to('[data-ztr-hero-word="0"]', { xPercent: -14, ease: 'none', scrollTrigger: leave });
			gsap.to('[data-ztr-hero-word="2"]', { xPercent: 14, ease: 'none', scrollTrigger: leave });
			gsap.to('[data-ztr-hero-word="1"]', { yPercent: -22, ease: 'none', scrollTrigger: leave });
		});
		if (stage) {
			gsap.to(stage, { opacity: 0.25, ease: 'none', scrollTrigger: { trigger: panel, start: '35% top', end: 'bottom top', scrub: true } });
		}
		if (light) {
			gsap.to(light, { yPercent: 14, ease: 'none', scrollTrigger: leave });
			if (finePointer) {
				var lx = gsap.quickTo(light, 'x', { duration: 1.4, ease: 'power3.out' });
				var ly = gsap.quickTo(light, 'y', { duration: 1.4, ease: 'power3.out' });
				panel.addEventListener('pointermove', function (e) {
					var r = panel.getBoundingClientRect();
					lx(((e.clientX - r.left) / r.width - 0.5) * 60);
					ly(((e.clientY - r.top) / r.height - 0.5) * 40);
				});
				panel.addEventListener('pointerleave', function () {
					lx(0);
					ly(0);
				});
			}
		}
	})();

	/* =====================================================================
	 * Process: the line above the steps fills as the band scrolls past.
	 * ================================================================== */
	(function processLine() {
		var line = root.querySelector('[data-ztr-steps-line]');
		var section = line && line.closest('section');
		if (!line || !section) {
			return;
		}
		line.classList.add('is-scrubbed');
		gsap.fromTo(line, { scaleX: 0 }, {
			scaleX: 1,
			ease: 'none',
			scrollTrigger: { trigger: section, start: 'top 70%', end: 'bottom 75%', scrub: 0.6 }
		});
	})();

	/* =====================================================================
	 * CTA box grows into place as it arrives; its glow drifts.
	 * ================================================================== */
	(function ctaBox() {
		var box = root.querySelector('[data-ztr-cta-box]');
		if (!box) {
			return;
		}
		gsap.fromTo(box, { scale: 0.92, borderRadius: '80px' }, {
			scale: 1,
			borderRadius: '40px',
			ease: 'none',
			scrollTrigger: { trigger: box, start: 'top 98%', end: 'top 50%', scrub: 1 }
		});
		var orb = box.querySelector('[data-ztr-cta-orb]');
		if (orb) {
			gsap.to(orb, { yPercent: 45, xPercent: -25, ease: 'none', scrollTrigger: { trigger: box, start: 'top bottom', end: 'bottom top', scrub: true } });
		}
	})();

	/* =====================================================================
	 * Footer: wordmark letters rise one after another as the page ends.
	 * ================================================================== */
	(function wordmark() {
		var mark = root.querySelector('[data-ztr-wordmark]');
		if (!mark) {
			return;
		}
		gsap.from(mark.children, {
			yPercent: 100,
			opacity: 0,
			ease: 'power3.out',
			stagger: 0.05,
			scrollTrigger: { trigger: mark, start: 'top bottom', end: 'bottom bottom', scrub: 1 }
		});
	})();

	/* =====================================================================
	 * Pointer-only effects: magnetic buttons, card tilt, custom cursor.
	 * ================================================================== */
	if (finePointer) {
		root.querySelectorAll('[data-ztr-magnetic]').forEach(function (btn) {
			// Let the intro animation finish before taking over transform.
			btn.addEventListener('animationend', function () {
				btn.style.animation = 'none';
			});
			var xTo = gsap.quickTo(btn, 'x', { duration: 0.5, ease: 'power3.out' });
			var yTo = gsap.quickTo(btn, 'y', { duration: 0.5, ease: 'power3.out' });
			btn.addEventListener('pointermove', function (e) {
				var r = btn.getBoundingClientRect();
				xTo((e.clientX - r.left - r.width / 2) * 0.28);
				yTo((e.clientY - r.top - r.height / 2) * 0.38);
			});
			btn.addEventListener('pointerleave', function () {
				xTo(0);
				yTo(0);
			});
		});

		root.querySelectorAll('[data-ztr-tilt]').forEach(function (card) {
			var rx = null;
			var ry = null;
			var max = 4;
			card.addEventListener('pointermove', function (e) {
				var r = card.getBoundingClientRect();
				var px = (e.clientX - r.left) / r.width;
				var py = (e.clientY - r.top) / r.height;
				card.style.setProperty('--mx', (px * 100).toFixed(1) + '%');
				card.style.setProperty('--my', (py * 100).toFixed(1) + '%');
				if (card.hasAttribute('data-ztr-reveal') && !card.classList.contains('is-settled')) {
					return; // Still landing from its reveal: leave transform to CSS.
				}
				if (!rx) {
					gsap.set(card, { transformPerspective: 1100 });
					rx = gsap.quickTo(card, 'rotationX', { duration: 0.6, ease: 'power3.out' });
					ry = gsap.quickTo(card, 'rotationY', { duration: 0.6, ease: 'power3.out' });
				}
				ry((px - 0.5) * max * 2);
				rx((0.5 - py) * max * 2);
			});
			card.addEventListener('pointerleave', function () {
				if (rx) {
					rx(0);
					ry(0);
				}
			});
		});

		var cursor = root.querySelector('.ztr-cursor');
		if (cursor) {
			var dot = cursor.querySelector('.ztr-cursor__dot');
			var ring = cursor.querySelector('.ztr-cursor__ring');
			cursor.classList.add('is-on', 'is-hidden');
			gsap.set([dot, ring], { xPercent: -50, yPercent: -50 });
			var dx = gsap.quickTo(dot, 'x', { duration: 0.12 });
			var dy = gsap.quickTo(dot, 'y', { duration: 0.12 });
			var rxq = gsap.quickTo(ring, 'x', { duration: 0.45, ease: 'power3.out' });
			var ryq = gsap.quickTo(ring, 'y', { duration: 0.45, ease: 'power3.out' });
			window.addEventListener('pointermove', function (e) {
				dx(e.clientX);
				dy(e.clientY);
				rxq(e.clientX);
				ryq(e.clientY);
				cursor.classList.remove('is-hidden');
			}, { passive: true });
			document.addEventListener('pointerleave', function () {
				cursor.classList.add('is-hidden');
			});
			document.addEventListener('pointerover', function (e) {
				cursor.classList.toggle('is-hover', !!(e.target.closest && e.target.closest('a, button, summary, input, select, textarea, label, [data-ztr-tilt]')));
			});
		}
	}

	/* =====================================================================
	 * Keep trigger positions right when content changes height (tabs,
	 * accordions, late fonts and images).
	 * ================================================================== */
	var refreshTimer = null;
	function refreshSoon() {
		window.clearTimeout(refreshTimer);
		refreshTimer = window.setTimeout(function () {
			ST.refresh();
		}, 200);
	}
	if ('ResizeObserver' in window) {
		var main = root.querySelector('.ztr-main') || root;
		new ResizeObserver(refreshSoon).observe(main);
	}
	if (document.fonts && document.fonts.ready) {
		document.fonts.ready.then(refreshSoon);
	}
	window.addEventListener('load', refreshSoon);
})();
