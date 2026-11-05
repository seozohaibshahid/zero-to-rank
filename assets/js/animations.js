/**
 * Zero to Rank — animations.js
 * Scroll-linked motion. Uses GSAP + ScrollTrigger when available (loaded
 * deferred, self-hosted) and falls back to Intersection Observer / rAF.
 * Everything is skipped or reduced under prefers-reduced-motion.
 */
(function () {
	'use strict';

	var ZTR = window.ZTR;
	if (!ZTR || !ZTR.root) {
		return;
	}
	var root = ZTR.root;
	var hasGsap = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';

	if (hasGsap) {
		window.gsap.registerPlugin(window.ScrollTrigger);
	}

	/* =====================================================================
	 * 07 — Count-up statistics (triggered once, on view).
	 * ================================================================== */
	root.querySelectorAll('[data-ztr-count]').forEach(function (el) {
		var target = parseInt(el.getAttribute('data-ztr-count'), 10) || 0;
		if (ZTR.reduced()) {
			return; // Final number is already in the HTML.
		}
		el.textContent = '0';
		ZTR.onVisible(
			el,
			function () {
				var duration = 1400 + Math.min(target, 100) * 6;
				var start = null;
				function frame(ts) {
					if (!start) {
						start = ts;
					}
					var p = Math.min(1, (ts - start) / duration);
					var eased = 1 - Math.pow(1 - p, 4); // easeOutQuart
					el.textContent = String(Math.round(target * eased));
					if (p < 1) {
						window.requestAnimationFrame(frame);
					}
				}
				window.requestAnimationFrame(frame);
			},
			{ threshold: 0.6 }
		);
	});

	/* =====================================================================
	 * 08 — Process timeline: the rail fills as you scroll and each step
	 * activates as the line reaches it.
	 * ================================================================== */
	(function timeline() {
		var tl = root.querySelector('[data-ztr-timeline]');
		if (!tl) {
			return;
		}
		var fill = tl.querySelector('[data-ztr-timeline-fill]');
		var steps = Array.prototype.slice.call(tl.querySelectorAll('[data-ztr-step]'));

		if (ZTR.reduced()) {
			steps.forEach(function (s) {
				s.classList.add('is-active');
			});
			return;
		}

		// End the rail at the centre of the last step's dot.
		var rail = tl.querySelector('.ztr-timeline__rail');
		function sizeRail() {
			var lastDot = steps[steps.length - 1].querySelector('.ztr-timeline__dot');
			var firstDot = steps[0].querySelector('.ztr-timeline__dot');
			var top = firstDot.offsetTop + steps[0].offsetTop + firstDot.offsetHeight / 2;
			var bottom = tl.offsetHeight - (steps[steps.length - 1].offsetTop + lastDot.offsetTop + lastDot.offsetHeight / 2);
			rail.style.top = top + 'px';
			rail.style.bottom = Math.max(0, bottom) + 'px';
		}
		sizeRail();
		window.addEventListener('resize', sizeRail);

		tl.classList.add('ztr-timeline--enhanced');
		fill.style.setProperty('--ztr-progress', 0);

		function update(progress) {
			fill.style.setProperty('--ztr-progress', progress.toFixed(4));
			var r = rail.getBoundingClientRect();
			var reach = r.top + r.height * progress;
			steps.forEach(function (s, i) {
				var dot = s.querySelector('.ztr-timeline__dot').getBoundingClientRect();
				s.classList.toggle('is-active', i === 0 ? progress > 0.001 : reach >= dot.top + dot.height / 2 - 4);
			});
		}

		if (hasGsap) {
			window.ScrollTrigger.create({
				trigger: tl,
				start: 'top 65%',
				end: 'bottom 55%',
				scrub: 0.4,
				onUpdate: function (self) {
					update(self.progress);
				},
				onLeave: function () {
					update(1);
				}
			});
			return;
		}

		// Fallback: rAF-throttled scroll listener.
		var ticking = false;
		function measure() {
			var r = tl.getBoundingClientRect();
			var vh = window.innerHeight;
			var start = vh * 0.65;
			var end = vh * 0.55;
			var total = r.height + (start - end);
			var progress = Math.min(1, Math.max(0, (start - r.top) / total));
			update(progress);
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
		measure();
	})();

	/**
	 * Scroll-progress helper: calls fn(progress 0–1) as `el` travels between
	 * two viewport positions (fractions of the viewport height, measured
	 * against the element's top and bottom). GSAP ScrollTrigger when loaded,
	 * otherwise a rAF-throttled scroll listener.
	 */
	function scrub(el, startAt, endAt, fn, endEdge) {
		if (hasGsap) {
			window.ScrollTrigger.create({
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
			var a = r.top - vh * startAt; // scroll distance until the start point
			var b = (endEdge === 'top' ? r.top : r.bottom) - vh * endAt; // …until the end point
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
		if (!text || ZTR.reduced() || typeof ZTR.splitWords !== 'function') {
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

	/* =====================================================================
	 * Immersive transitions (GSAP only — decorative, so no fallback):
	 * - the hero recedes into an inset panel as you scroll past it;
	 * - dark sections settle from a slightly smaller, rounder sheet.
	 * ================================================================== */
	if (hasGsap && !ZTR.reduced()) {
		var hero = root.querySelector('.ztr-hero');
		var heroLayout = hero && hero.querySelector('.ztr-hero__layout');
		if (hero) {
			var radius = function () {
				return parseFloat(getComputedStyle(hero).borderBottomLeftRadius) || 0;
			};
			window.ScrollTrigger.create({
				trigger: hero,
				start: 'top top',
				end: 'bottom top',
				scrub: 0.3,
				onUpdate: function (self) {
					var p = self.progress;
					var inset = (p * Math.min(28, window.innerWidth * 0.025)).toFixed(1);
					var r = radius() + p * 24;
					hero.style.clipPath = 'inset(0px ' + inset + 'px 0px ' + inset + 'px round 0px 0px ' + r.toFixed(1) + 'px ' + r.toFixed(1) + 'px)';
					if (heroLayout) {
						heroLayout.style.transform = 'translate3d(0,' + (p * 90).toFixed(1) + 'px,0)';
						heroLayout.style.opacity = (1 - p * 0.7).toFixed(3);
					}
				}
			});
		}

		root.querySelectorAll('.ztr-section.ztr-dark, .ztr-footer').forEach(function (sheet) {
			window.gsap.fromTo(
				sheet,
				{ scale: 0.95 },
				{
					scale: 1,
					ease: 'none',
					scrollTrigger: { trigger: sheet, start: 'top bottom', end: 'top 35%', scrub: 0.4 }
				}
			);
		});
	}

	/* =====================================================================
	 * Hero depth: the background glows drift with scroll (GSAP only —
	 * purely ambient, so no fallback needed).
	 * ================================================================== */
	if (hasGsap && !ZTR.reduced()) {
		root.querySelectorAll('[data-ztr-parallax]').forEach(function (el) {
			var speed = parseFloat(el.getAttribute('data-ztr-parallax')) || 0;
			window.gsap.to(el, {
				yPercent: speed * 100,
				ease: 'none',
				scrollTrigger: {
					trigger: el.closest('section'),
					start: 'top top',
					end: 'bottom top',
					scrub: true
				}
			});
		});

		// Footer wordmark rises into place as the page ends.
		var mark = root.querySelector('.ztr-footer__wordmark');
		if (mark) {
			window.gsap.from(mark, {
				yPercent: 40,
				opacity: 0.2,
				ease: 'none',
				scrollTrigger: { trigger: mark, start: 'top bottom', end: 'bottom bottom', scrub: true }
			});
		}

		// Recalculate trigger positions after fonts load (layout can shift).
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(function () {
				window.ScrollTrigger.refresh();
			});
		}
	}
})();
