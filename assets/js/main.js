/**
 * Zero to Rank — main.js
 * Core utilities shared by the other scripts, plus site-wide behaviour:
 * sticky navigation, mobile menu, scroll reveals, scroll-spy and analytics.
 * No dependencies.
 */
(function () {
	'use strict';

	var root = document.querySelector('.ztr');
	if (!root) {
		return;
	}

	var reducedQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

	/* ---------------------------------------------------------------------
	 * Shared namespace
	 * ------------------------------------------------------------------ */
	var ZTR = (window.ZTR = window.ZTR || {});

	ZTR.root = root;
	ZTR.data = window.ztrData || { i18n: {} };

	/** True when the visitor prefers reduced motion (live value). */
	ZTR.reduced = function () {
		return reducedQuery.matches;
	};

	/**
	 * Analytics: pushes to the GTM dataLayer and, if present, GA4 gtag().
	 * Event names follow GA4 conventions (cta_click, generate_lead, …).
	 */
	ZTR.track = function (event, params) {
		params = params || {};
		try {
			window.dataLayer = window.dataLayer || [];
			var payload = { event: event, ztr_page: 'landing' };
			Object.keys(params).forEach(function (k) {
				payload[k] = params[k];
			});
			window.dataLayer.push(payload);
			if (typeof window.gtag === 'function') {
				window.gtag('event', event, params);
			}
		} catch (e) {
			/* Analytics must never break the page. */
		}
	};

	/**
	 * Run a callback once when an element enters the viewport.
	 */
	ZTR.onVisible = function (el, cb, options) {
		if (!el) {
			return;
		}
		if (!('IntersectionObserver' in window)) {
			cb(el);
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					io.unobserve(entry.target);
					cb(entry.target);
				}
			});
		}, options || { threshold: 0.2 });
		io.observe(el);
	};

	/**
	 * Arrow-key navigation for a group of tabs/radios (WAI-ARIA pattern).
	 */
	ZTR.rovingKeys = function (buttons, activate, orientation) {
		buttons.forEach(function (btn, i) {
			btn.addEventListener('keydown', function (e) {
				var next = null;
				var horiz = orientation !== 'vertical';
				if ((horiz && e.key === 'ArrowRight') || (!horiz && e.key === 'ArrowDown')) {
					next = buttons[(i + 1) % buttons.length];
				} else if ((horiz && e.key === 'ArrowLeft') || (!horiz && e.key === 'ArrowUp')) {
					next = buttons[(i - 1 + buttons.length) % buttons.length];
				} else if (e.key === 'Home') {
					next = buttons[0];
				} else if (e.key === 'End') {
					next = buttons[buttons.length - 1];
				}
				if (next) {
					e.preventDefault();
					next.focus();
					activate(next, true);
				}
			});
		});
	};

	/* ---------------------------------------------------------------------
	 * Sticky navigation: solid background after the hero edge, hides on
	 * scroll down and returns on scroll up so content keeps the focus.
	 * ------------------------------------------------------------------ */
	var nav = root.querySelector('[data-ztr-nav]');
	var toggle = root.querySelector('[data-ztr-menu-toggle]');
	var menu = root.querySelector('[data-ztr-menu]');
	var lastY = window.pageYOffset;
	var ticking = false;

	function isMenuOpen() {
		return nav && nav.classList.contains('is-open');
	}

	function onScroll() {
		var y = window.pageYOffset;
		if (nav) {
			nav.classList.toggle('is-scrolled', y > 24);
			var goingDown = y > lastY && y > 480;
			nav.classList.toggle('is-hidden', goingDown && !isMenuOpen() && !nav.contains(document.activeElement));
		}
		lastY = y;
		ticking = false;
	}

	window.addEventListener(
		'scroll',
		function () {
			if (!ticking) {
				window.requestAnimationFrame(onScroll);
				ticking = true;
			}
		},
		{ passive: true }
	);
	onScroll();

	/* Mobile menu */
	function setMenu(open) {
		if (!nav || !toggle) {
			return;
		}
		nav.classList.toggle('is-open', open);
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		var label = toggle.querySelector('[data-ztr-menu-label]');
		if (label) {
			if (!label.getAttribute('data-menu')) {
				label.setAttribute('data-menu', label.textContent);
			}
			label.textContent = open ? (ZTR.data.i18n.close || 'Close') : label.getAttribute('data-menu');
		}
		if (ZTR.lenis) {
			if (open) {
				ZTR.lenis.stop();
			} else {
				ZTR.lenis.start();
			}
		}
		document.documentElement.style.overflow = open ? 'hidden' : '';
		if (open) {
			var firstLink = menu && menu.querySelector('a');
			if (firstLink) {
				firstLink.focus({ preventScroll: true });
			}
		}
	}

	if (toggle) {
		toggle.addEventListener('click', function () {
			setMenu(!isMenuOpen());
		});
	}
	if (menu) {
		menu.addEventListener('click', function (e) {
			if (e.target.closest('a')) {
				setMenu(false);
			}
		});
	}
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && isMenuOpen()) {
			setMenu(false);
			toggle.focus();
		}
	});
	window.addEventListener('resize', function () {
		if (window.innerWidth > 960 && isMenuOpen()) {
			setMenu(false);
		}
	});

	/* ---------------------------------------------------------------------
	 * Word splitting: wraps each word of an element's text in a span while
	 * keeping inline markup (<em>, <strong>, <br>) intact. Screen readers
	 * still read the heading as one string.
	 * ------------------------------------------------------------------ */
	ZTR.splitWords = function (el, className) {
		var words = [];
		var walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null);
		var nodes = [];
		while (walker.nextNode()) {
			nodes.push(walker.currentNode);
		}
		nodes.forEach(function (node) {
			var parts = node.nodeValue.split(/(\s+)/);
			if (parts.length === 1 && !parts[0].trim()) {
				return;
			}
			var frag = document.createDocumentFragment();
			parts.forEach(function (part) {
				if (!part) {
					return;
				}
				if (/^\s+$/.test(part)) {
					frag.appendChild(document.createTextNode(part));
					return;
				}
				var span = document.createElement('span');
				span.className = className;
				span.textContent = part;
				frag.appendChild(span);
				words.push(span);
			});
			node.parentNode.replaceChild(frag, node);
		});
		return words;
	};

	/* ---------------------------------------------------------------------
	 * Card spotlight: a soft lime light follows the pointer across cards
	 * marked [data-ztr-spot] (fine pointers only).
	 * ------------------------------------------------------------------ */
	if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
		root.addEventListener(
			'pointermove',
			function (e) {
				var card = e.target.closest && e.target.closest('[data-ztr-spot]');
				if (!card) {
					return;
				}
				var r = card.getBoundingClientRect();
				card.style.setProperty('--mx', (e.clientX - r.left).toFixed(0) + 'px');
				card.style.setProperty('--my', (e.clientY - r.top).toFixed(0) + 'px');
			},
			{ passive: true }
		);
	}

	/* ---------------------------------------------------------------------
	 * Headings marked [data-ztr-words] rise word by word: each word gets its
	 * own mask (.ztr-w) and a stagger index. Skipped with reduced motion.
	 * ------------------------------------------------------------------ */
	var wordEls = root.querySelectorAll('[data-ztr-words]');
	if (!ZTR.reduced()) {
		wordEls.forEach(function (el) {
			var words = ZTR.splitWords(el, 'ztr-w');
			words.forEach(function (w, i) {
				var inner = document.createElement('span');
				inner.textContent = w.textContent;
				inner.style.setProperty('--i', i);
				w.textContent = '';
				w.appendChild(inner);
			});
			el.classList.add('is-split');
		});
	}

	/* ---------------------------------------------------------------------
	 * Scroll reveals (Intersection Observer, one-shot). Cards are marked
	 * settled once they land, so pointer effects can take over transform.
	 * ------------------------------------------------------------------ */
	function settle(el) {
		if (el.getAttribute('data-ztr-reveal') !== 'card') {
			return;
		}
		var done = function () {
			el.classList.add('is-settled');
		};
		if (ZTR.reduced()) {
			done();
			return;
		}
		window.setTimeout(done, 1400);
	}
	var revealEls = root.querySelectorAll('[data-ztr-reveal], [data-ztr-words]');
	if (!('IntersectionObserver' in window) || ZTR.reduced()) {
		revealEls.forEach(function (el) {
			el.classList.add('is-in');
			settle(el);
		});
	} else {
		var revealIO = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-in');
						settle(entry.target);
						revealIO.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.12, rootMargin: '0px 0px -6% 0px' }
		);
		revealEls.forEach(function (el) {
			revealIO.observe(el);
		});
	}

	/* ---------------------------------------------------------------------
	 * Count-up: figures marked [data-ztr-countup] count from zero when they
	 * come into view, keeping their prefix, suffix, decimals and separators.
	 * The final text is already in the HTML (no-JS, reduced motion, SEO).
	 * ------------------------------------------------------------------ */
	root.querySelectorAll('[data-ztr-countup]').forEach(function (el) {
		var text = el.textContent.trim();
		var m = text.match(/^([^0-9]*)([0-9][0-9,]*(?:\.[0-9]+)?)(.*)$/);
		if (!m || ZTR.reduced()) {
			return;
		}
		var prefix = m[1];
		var raw = m[2];
		var suffix = m[3];
		var target = parseFloat(raw.replace(/,/g, ''));
		var decimals = raw.indexOf('.') > -1 ? raw.split('.')[1].length : 0;
		var commas = raw.indexOf(',') > -1;
		var format = function (v) {
			var s = v.toFixed(decimals);
			if (commas) {
				s = s.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
			}
			return prefix + s + suffix;
		};
		el.textContent = format(0);
		ZTR.onVisible(
			el,
			function () {
				var inHero = !!el.closest('.ztr-hero');
				var duration = 1600;
				var delay = inHero ? 1100 : 0;
				var start = null;
				function frame(ts) {
					if (!start) {
						start = ts;
					}
					var p = Math.min(1, Math.max(0, (ts - start - delay) / duration));
					var eased = 1 - Math.pow(1 - p, 4);
					el.textContent = p >= 1 ? text : format(target * eased);
					if (p < 1) {
						window.requestAnimationFrame(frame);
					}
				}
				window.requestAnimationFrame(frame);
			},
			{ threshold: 0.5 }
		);
	});

	/* ---------------------------------------------------------------------
	 * Scroll-spy: highlight the nav link for the section in view.
	 * ------------------------------------------------------------------ */
	/* Sliding indicator in the desktop nav capsule: follows hover, rests on
	 * the current section's link, hides when neither applies. */
	var indicator = nav && nav.querySelector('[data-ztr-nav-indicator]');
	var moveIndicator = function () {};
	if (indicator && menu) {
		nav.classList.add('ztr-nav--pill');
		moveIndicator = function (link) {
			if (!link || window.innerWidth <= 960) {
				indicator.classList.remove('is-on');
				return;
			}
			var m = menu.getBoundingClientRect();
			var r = link.getBoundingClientRect();
			indicator.style.setProperty('--ztr-ind-x', (r.left - m.left - 1).toFixed(1) + 'px');
			indicator.style.setProperty('--ztr-ind-w', r.width.toFixed(1) + 'px');
			indicator.classList.add('is-on');
		};
		var currentLink = function () {
			return menu.querySelector('.ztr-nav__list a.is-current');
		};
		menu.querySelectorAll('.ztr-nav__list a').forEach(function (a) {
			a.addEventListener('mouseenter', function () {
				moveIndicator(a);
			});
			a.addEventListener('focus', function () {
				moveIndicator(a);
			});
		});
		menu.addEventListener('mouseleave', function () {
			moveIndicator(currentLink());
		});
		menu.addEventListener('focusout', function (e) {
			if (!menu.contains(e.relatedTarget)) {
				moveIndicator(currentLink());
			}
		});
		window.addEventListener('resize', function () {
			moveIndicator(currentLink());
		});
		// The capsule changes width when the page scrolls; re-measure after.
		nav.querySelector('.ztr-nav__inner').addEventListener('transitionend', function (e) {
			if (e.propertyName === 'max-width' && !menu.matches(':hover')) {
				moveIndicator(currentLink());
			}
		});
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(function () {
				moveIndicator(currentLink());
			});
		}
	}

	if ('IntersectionObserver' in window && nav) {
		var links = {};
		nav.querySelectorAll('.ztr-nav__list a[href^="#"]').forEach(function (a) {
			links[a.getAttribute('href').slice(1)] = a;
		});
		var spyIO = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					var link = links[entry.target.id];
					if (!link) {
						return;
					}
					if (entry.isIntersecting) {
						Object.keys(links).forEach(function (k) {
							links[k].classList.remove('is-current');
							links[k].removeAttribute('aria-current');
						});
						link.classList.add('is-current');
						link.setAttribute('aria-current', 'true');
						if (!menu.matches(':hover')) {
							moveIndicator(link);
						}
					}
				});
			},
			{ rootMargin: '-45% 0px -50% 0px' }
		);
		Object.keys(links).forEach(function (id) {
			var section = document.getElementById(id);
			if (section) {
				spyIO.observe(section);
			}
		});
	}

	/* ---------------------------------------------------------------------
	 * In-page anchors: move focus to the target for keyboard/screen-reader
	 * users (the CSS handles smooth scrolling and sticky-header offset).
	 * ------------------------------------------------------------------ */
	root.addEventListener('click', function (e) {
		var a = e.target.closest('a[href^="#"]');
		if (!a) {
			return;
		}
		var id = a.getAttribute('href').slice(1);
		var target = id === 'ztr-top' ? document.body : document.getElementById(id);
		if (!target) {
			return;
		}
		// Links to a tab panel (e.g. a case study) open that tab first and
		// scroll to the tab group so the tabs stay in view.
		if (target.hasAttribute('data-ztr-panel')) {
			var tab = root.querySelector('[data-ztr-tab][aria-controls="' + id + '"]');
			if (tab) {
				tab.click();
				target = target.closest('section') || target;
			}
		}
		e.preventDefault();
		var behavior = ZTR.reduced() ? 'auto' : 'smooth';
		if (ZTR.lenis) {
			ZTR.lenis.scrollTo(id === 'ztr-top' ? 0 : target, { offset: id === 'ztr-top' ? 0 : -96 });
			if (id !== 'ztr-top') {
				if (!target.hasAttribute('tabindex')) {
					target.setAttribute('tabindex', '-1');
				}
				target.focus({ preventScroll: true });
			}
		} else if (id === 'ztr-top') {
			window.scrollTo({ top: 0, behavior: behavior });
		} else {
			target.scrollIntoView({ behavior: behavior, block: 'start' });
			if (!target.hasAttribute('tabindex')) {
				target.setAttribute('tabindex', '-1');
			}
			target.focus({ preventScroll: true });
		}
		if (window.history && window.history.replaceState) {
			window.history.replaceState(null, '', '#' + id);
		}
	});

	/* ---------------------------------------------------------------------
	 * Analytics: declarative click tracking via data attributes.
	 * ------------------------------------------------------------------ */
	root.addEventListener('click', function (e) {
		var el = e.target.closest('[data-ztr-track]');
		if (!el) {
			return;
		}
		ZTR.track(el.getAttribute('data-ztr-track'), {
			cta_location: el.getAttribute('data-ztr-location') || '',
			cta_label: el.getAttribute('data-ztr-label') || '',
			link_url: el.getAttribute('href') || ''
		});
	});

	/* Section views — useful for funnel analysis (each fires once). */
	root.querySelectorAll('[data-ztr-section]').forEach(function (section) {
		ZTR.onVisible(
			section,
			function () {
				ZTR.track('section_view', { section_name: section.getAttribute('data-ztr-section') });
			},
			{ threshold: 0.35 }
		);
	});
})();
