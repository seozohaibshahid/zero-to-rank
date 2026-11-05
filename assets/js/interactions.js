/**
 * Zero to Rank — interactions.js
 * Component behaviour: hero visibility visual, results dashboard,
 * visibility-shift toggle, service cards, industry tabs, FAQ accordion
 * and the audit form. Vanilla JS, progressive enhancement throughout.
 */
(function () {
	'use strict';

	var ZTR = window.ZTR;
	if (!ZTR || !ZTR.root) {
		return;
	}
	var root = ZTR.root;
	var i18n = (ZTR.data && ZTR.data.i18n) || {};
	var desktopHover = window.matchMedia('(min-width: 961px) and (hover: hover)');

	function setTabState(btn, selected) {
		btn.classList.toggle('is-active', selected);
		btn.setAttribute(btn.getAttribute('role') === 'radio' ? 'aria-checked' : 'aria-selected', selected ? 'true' : 'false');
		btn.setAttribute('tabindex', selected ? '0' : '-1');
	}

	/* =====================================================================
	 * 02 — Hero: SEO / AEO / GEO visibility visual
	 * Auto-advances (with a visible timer) to show the same business winning
	 * in each channel; stops for good as soon as the visitor takes control.
	 * ================================================================== */
	(function hero() {
		var eco = root.querySelector('[data-ztr-eco]');
		if (!eco) {
			return;
		}
		var tabs = Array.prototype.slice.call(eco.querySelectorAll('[data-ztr-eco-tab]'));
		var panels = eco.querySelectorAll('[data-ztr-eco-panel]');
		var queryEl = eco.querySelector('[data-ztr-eco-query]');
		var outcomeEl = eco.querySelector('[data-ztr-eco-outcome]');
		var serp = eco.querySelector('[data-ztr-serp]');
		var DURATION = 5600;
		var timers = [];
		var autoTimer = null;
		var autoplay = !ZTR.reduced();
		var started = 0;
		var remaining = DURATION;
		var current = 0;
		var inView = true;
		var hovering = false;

		eco.style.setProperty('--ztr-dur', DURATION + 'ms');

		function later(fn, ms) {
			timers.push(window.setTimeout(fn, ms));
		}
		function clearLater() {
			timers.forEach(window.clearTimeout);
			timers = [];
		}

		function typeQuery(text) {
			if (!queryEl) {
				return;
			}
			if (ZTR.reduced()) {
				queryEl.textContent = text;
				return;
			}
			queryEl.textContent = '';
			var i = 0;
			(function step() {
				i++;
				queryEl.textContent = text.slice(0, i);
				if (i < text.length) {
					later(step, 22 + Math.random() * 26);
				}
			})();
		}

		function runSerp() {
			if (!serp) {
				return;
			}
			var rows = Array.prototype.slice.call(serp.querySelectorAll('.ztr-serp__row'));
			var you = serp.querySelector('.is-you');
			var rank = serp.querySelector('[data-ztr-rank]');
			var youStart = parseInt(you.getAttribute('data-start'), 10);

			rows.forEach(function (r) {
				r.style.setProperty('--i', r.getAttribute('data-start'));
			});
			rank.classList.remove('is-top');
			rank.textContent = '#' + (youStart + 1);

			function finish() {
				rows.forEach(function (r) {
					var s = parseInt(r.getAttribute('data-start'), 10);
					if (r === you) {
						r.style.setProperty('--i', 0);
					} else if (s < youStart) {
						r.style.setProperty('--i', s + 1);
					}
				});
				if (ZTR.reduced()) {
					rank.textContent = '#1';
					return;
				}
				var n = youStart + 1;
				(function countDown() {
					n--;
					rank.textContent = '#' + n;
					if (n > 1) {
						later(countDown, 260);
					} else {
						rank.classList.add('is-top');
					}
				})();
			}

			if (ZTR.reduced()) {
				finish();
			} else {
				later(finish, 900);
			}
		}

		function schedule(ms) {
			window.clearTimeout(autoTimer);
			if (!autoplay || !inView || hovering) {
				return;
			}
			started = Date.now();
			remaining = ms;
			autoTimer = window.setTimeout(function () {
				activate((current + 1) % tabs.length, false);
			}, ms);
		}

		function activate(index, byUser) {
			clearLater();
			current = index;
			var tab = tabs[index];
			var key = tab.getAttribute('data-ztr-eco-tab');

			tabs.forEach(function (t, i) {
				setTabState(t, i === index);
			});
			panels.forEach(function (p) {
				p.classList.toggle('is-active', p.getAttribute('data-ztr-eco-panel') === key);
			});

			var panel = eco.querySelector('[data-ztr-eco-panel="' + key + '"]');
			typeQuery(panel.getAttribute('data-query'));
			if (outcomeEl) {
				outcomeEl.textContent = tab.getAttribute('data-outcome');
			}
			if (key === 'seo') {
				runSerp();
			}

			if (byUser) {
				// The visitor is in control now: stop rotating.
				autoplay = false;
				window.clearTimeout(autoTimer);
				eco.classList.remove('is-playing', 'is-paused');
				ZTR.track('tab_select', { component: 'hero_visibility', tab_name: key });
			} else {
				// Restart the CSS timer bar.
				eco.classList.remove('is-playing');
				void eco.offsetWidth; // eslint-disable-line no-void
				if (autoplay) {
					eco.classList.add('is-playing');
				}
				schedule(DURATION);
			}
		}

		tabs.forEach(function (t, i) {
			t.addEventListener('click', function () {
				activate(i, true);
			});
		});
		ZTR.rovingKeys(tabs, function (btn) {
			activate(tabs.indexOf(btn), true);
		});

		function pause() {
			if (!autoplay) {
				return;
			}
			window.clearTimeout(autoTimer);
			remaining = Math.max(400, remaining - (Date.now() - started));
			eco.classList.add('is-paused');
		}
		function resume() {
			if (!autoplay || hovering || !inView) {
				return;
			}
			eco.classList.remove('is-paused');
			schedule(remaining);
		}

		eco.addEventListener('mouseenter', function () {
			hovering = true;
			pause();
		});
		eco.addEventListener('mouseleave', function () {
			hovering = false;
			resume();
		});
		eco.addEventListener('focusin', function () {
			hovering = true;
			pause();
		});
		eco.addEventListener('focusout', function (e) {
			if (!eco.contains(e.relatedTarget)) {
				hovering = false;
				resume();
			}
		});

		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (entries) {
				inView = entries[0].isIntersecting;
				if (inView) {
					resume();
				} else {
					pause();
				}
			}).observe(eco);
		}

		// Kick off once the hero intro has settled.
		window.setTimeout(function () {
			activate(0, false);
		}, ZTR.reduced() ? 0 : 700);
	})();

	/* =====================================================================
	 * 03 — Trust marquee: pause when off-screen (saves battery/CPU).
	 * ================================================================== */
	(function marquee() {
		var m = root.querySelector('[data-ztr-marquee]');
		if (!m || !('IntersectionObserver' in window)) {
			return;
		}
		new IntersectionObserver(function (entries) {
			m.classList.toggle('is-paused', !entries[0].isIntersecting);
		}).observe(m);
	})();

	/* =====================================================================
	 * 04 — Results dashboard: interactive metric cards drive one chart.
	 * ================================================================== */
	(function dashboard() {
		var dash = root.querySelector('[data-ztr-dash]');
		if (!dash) {
			return;
		}
		var dataEl = dash.querySelector('[data-ztr-dash-data]');
		var metrics;
		try {
			metrics = JSON.parse(dataEl.textContent);
		} catch (e) {
			return;
		}
		var tabs = Array.prototype.slice.call(dash.querySelectorAll('[data-ztr-dash-tab]'));
		var panel = dash.querySelector('#ztr-dash-panel');
		var title = dash.querySelector('[data-ztr-dash-title]');
		var line = dash.querySelector('[data-ztr-chart-line]');
		var area = dash.querySelector('[data-ztr-chart-area]');
		var signals = dash.querySelector('[data-ztr-dash-signals]');
		var check = signals.querySelector('svg') ? signals.querySelector('svg').outerHTML : '';

		function draw() {
			[line, area].forEach(function (el) {
				el.classList.remove('is-drawing');
				void el.getBoundingClientRect(); // eslint-disable-line no-void
				el.classList.add('is-drawing');
			});
		}

		function show(btn, byUser) {
			var key = btn.getAttribute('data-ztr-dash-tab');
			var m = metrics.filter(function (x) {
				return x.key === key;
			})[0];
			if (!m) {
				return;
			}
			tabs.forEach(function (t) {
				setTabState(t, t === btn);
			});
			panel.setAttribute('aria-labelledby', btn.id);

			title.classList.add('is-swapping');
			signals.parentNode.classList.add('is-swapping');
			window.setTimeout(
				function () {
					title.textContent = m.title;
					line.setAttribute('d', m.curve);
					area.setAttribute('d', m.curve + ' L500,170 L0,170 Z');
					signals.innerHTML = '';
					m.signals.forEach(function (s) {
						var li = document.createElement('li');
						li.innerHTML = check;
						var span = document.createElement('span');
						span.textContent = s;
						li.appendChild(span);
						signals.appendChild(li);
					});
					title.classList.remove('is-swapping');
					signals.parentNode.classList.remove('is-swapping');
					draw();
				},
				ZTR.reduced() ? 0 : 220
			);

			if (byUser) {
				ZTR.track('tab_select', { component: 'results_dashboard', tab_name: key });
			}
		}

		tabs.forEach(function (t) {
			t.addEventListener('click', function () {
				show(t, true);
			});
		});
		ZTR.rovingKeys(
			tabs,
			function (btn) {
				show(btn, true);
			},
			'vertical'
		);

		// Draw the first chart when the dashboard scrolls into view.
		ZTR.onVisible(
			dash,
			function () {
				dash.classList.add('is-in');
				draw();
			},
			{ threshold: 0.3 }
		);
	})();

	/* =====================================================================
	 * 05 — Visibility shift: traditional search vs AI discovery.
	 * Plays the shift once when first seen, then hands control to the visitor.
	 * ================================================================== */
	(function shift() {
		var ui = root.querySelector('[data-ztr-shift]');
		if (!ui) {
			return;
		}
		var opts = Array.prototype.slice.call(ui.querySelectorAll('[data-ztr-shift-mode]'));
		var panels = ui.querySelectorAll('[data-ztr-shift-panel]');
		var touched = false;

		function setMode(btn, byUser) {
			var mode = btn.getAttribute('data-ztr-shift-mode');
			if (ui.getAttribute('data-mode') === mode) {
				return;
			}
			ui.setAttribute('data-mode', mode);
			opts.forEach(function (o) {
				setTabState(o, o === btn);
			});
			panels.forEach(function (p) {
				p.hidden = p.getAttribute('data-ztr-shift-panel') !== mode;
			});
			if (byUser) {
				touched = true;
				ZTR.track('tab_select', { component: 'visibility_shift', tab_name: mode });
			}
		}

		opts.forEach(function (o) {
			o.addEventListener('click', function () {
				setMode(o, true);
			});
		});
		ZTR.rovingKeys(opts, function (btn) {
			setMode(btn, true);
		});

		if (!ZTR.reduced()) {
			ZTR.onVisible(
				ui,
				function () {
					window.setTimeout(function () {
						if (!touched && opts[1]) {
							setMode(opts[1], false);
						}
					}, 2600);
				},
				{ threshold: 0.55 }
			);
		}
	})();

	/* =====================================================================
	 * 06 — Service cards: expand on hover/focus (desktop) or tap (touch).
	 * ================================================================== */
	(function services() {
		var wrap = root.querySelector('[data-ztr-services]');
		if (!wrap) {
			return;
		}
		var cards = Array.prototype.slice.call(wrap.querySelectorAll('[data-ztr-service]'));
		var tracked = {};

		function open(card, byUser) {
			// Desktop: exactly one card is open. Touch/tablet: accordion that can fully close.
			var willOpen = desktopHover.matches ? true : !card.classList.contains('is-open');
			cards.forEach(function (c) {
				var on = c === card ? willOpen : false;
				c.classList.toggle('is-open', on);
				c.querySelector('[data-ztr-service-toggle]').setAttribute('aria-expanded', on ? 'true' : 'false');
			});
			var key = card.getAttribute('data-ztr-service');
			if (byUser && !tracked[key]) {
				tracked[key] = true;
				ZTR.track('service_expand', { service_name: key });
			}
		}

		cards.forEach(function (card) {
			var btn = card.querySelector('[data-ztr-service-toggle]');
			btn.addEventListener('click', function () {
				open(card, true);
			});
			card.addEventListener('mouseenter', function () {
				if (desktopHover.matches && !card.classList.contains('is-open')) {
					open(card, true);
				}
			});
			card.addEventListener('focusin', function () {
				if (desktopHover.matches && !card.classList.contains('is-open')) {
					open(card, true);
				}
			});
		});
	})();

	/* Service CTAs pre-select the audit focus so the form starts half-filled. */
	root.addEventListener('click', function (e) {
		var cta = e.target.closest('[data-ztr-focus]');
		var select = root.querySelector('[data-ztr-focus-select]');
		if (cta && select) {
			select.value = cta.getAttribute('data-ztr-focus');
		}
	});

	/* =====================================================================
	 * 09 — Generic tabs (industries).
	 * ================================================================== */
	root.querySelectorAll('[data-ztr-tabs]').forEach(function (group) {
		var tabs = Array.prototype.slice.call(group.querySelectorAll('[data-ztr-tab]'));
		var panels = group.querySelectorAll('[data-ztr-panel]');
		var name = group.getAttribute('data-ztr-tabs');

		function select(tab, byUser) {
			tabs.forEach(function (t) {
				setTabState(t, t === tab);
			});
			panels.forEach(function (p) {
				p.hidden = p.id !== tab.getAttribute('aria-controls');
			});
			if (byUser) {
				tab.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: ZTR.reduced() ? 'auto' : 'smooth' });
				ZTR.track('tab_select', { component: name, tab_name: tab.textContent.trim() });
			}
		}
		tabs.forEach(function (t) {
			t.addEventListener('click', function () {
				select(t, true);
			});
		});
		ZTR.rovingKeys(tabs, function (btn) {
			select(btn, true);
		});
	});

	/* =====================================================================
	 * 11 — FAQ accordion: smooth height on native <details>.
	 * ================================================================== */
	root.querySelectorAll('[data-ztr-accordion] details').forEach(function (details) {
		var summary = details.querySelector('summary');
		var content = details.querySelector('.ztr-acc__a');
		var group = details.closest('[data-ztr-accordion]');
		var openEvent = (group && group.getAttribute('data-ztr-accordion')) || 'faq_open';
		var trackOpen = function () {
			var label = summary.querySelector('h3') ? summary.querySelector('h3').textContent.trim() : summary.textContent.trim();
			ZTR.track(openEvent, openEvent === 'faq_open' ? { faq_question: label } : { item_name: label });
		};
		var animating = false;

		summary.addEventListener('click', function (e) {
			if (ZTR.reduced() || !content.animate) {
				if (!details.open) {
					trackOpen();
				}
				return; // Native behaviour.
			}
			e.preventDefault();
			if (animating) {
				return;
			}
			animating = true;

			if (!details.open) {
				details.open = true;
				var h = content.scrollHeight;
				var a = content.animate([{ height: '0px' }, { height: h + 'px' }], { duration: 420, easing: 'cubic-bezier(0.22, 1, 0.36, 1)' });
				a.onfinish = function () {
					animating = false;
				};
				trackOpen();
			} else {
				var start = content.offsetHeight;
				var b = content.animate([{ height: start + 'px' }, { height: '0px' }], { duration: 320, easing: 'cubic-bezier(0.65, 0, 0.35, 1)' });
				b.onfinish = function () {
					details.open = false;
					animating = false;
				};
			}
		});
	});

	/* =====================================================================
	 * 10 — Audit form: inline validation + async submit with no-JS fallback.
	 * ================================================================== */
	(function auditForm() {
		var form = root.querySelector('[data-ztr-form]');
		if (!form) {
			return;
		}
		var status = form.querySelector('[data-ztr-form-status]');
		var submit = form.querySelector('[data-ztr-submit]');
		var label = submit.querySelector('.ztr-btn__label');
		var labelText = label.textContent;
		var success = root.querySelector('[data-ztr-form-success]');
		var started = false;

		function looksLikeUrl(v) {
			v = v.trim();
			if (!v) {
				return false;
			}
			if (!/^https?:\/\//i.test(v)) {
				v = 'https://' + v;
			}
			try {
				var u = new URL(v);
				return /\./.test(u.hostname) && !/\s/.test(v);
			} catch (e) {
				return false;
			}
		}

		function fieldWrap(input) {
			return input.closest('.ztr-field');
		}

		function setError(input, msg) {
			var wrap = fieldWrap(input);
			var err = wrap && wrap.querySelector('[data-ztr-error]');
			if (err) {
				err.textContent = msg || '';
			}
			if (wrap) {
				wrap.classList.toggle('is-invalid', !!msg);
				wrap.classList.toggle('is-valid', !msg && input.value.trim() !== '' && input.type !== 'checkbox');
			}
			input.setAttribute('aria-invalid', msg ? 'true' : 'false');
		}

		function validate(input) {
			var v = input.type === 'checkbox' ? input.checked : input.value.trim();
			var msg = '';
			if (input.required && !v) {
				msg = input.type === 'checkbox' ? i18n.consent : i18n.required;
			} else if (input.type === 'email' && v && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) {
				msg = i18n.email;
			} else if (input.hasAttribute('data-ztr-url') && v && !looksLikeUrl(v)) {
				msg = i18n.url;
			}
			setError(input, msg);
			return !msg;
		}

		var inputs = Array.prototype.slice.call(form.querySelectorAll('input[required], [data-ztr-url]'));
		inputs.forEach(function (input) {
			input.addEventListener('blur', function () {
				if (input.value.trim() !== '' || input.getAttribute('aria-invalid') === 'true') {
					validate(input);
				}
			});
			input.addEventListener('input', function () {
				if (input.getAttribute('aria-invalid') === 'true') {
					validate(input);
				}
			});
			if (input.type === 'checkbox') {
				input.addEventListener('change', function () {
					validate(input);
				});
			}
		});

		form.addEventListener('input', function () {
			if (!started) {
				started = true;
				ZTR.track('form_start', { form_name: 'visibility_audit' });
			}
		});

		function showStatus(msg, state) {
			status.hidden = !msg;
			status.textContent = msg || '';
			status.setAttribute('data-state', state || '');
		}

		function fail(msg) {
			submit.classList.remove('is-loading');
			label.textContent = labelText;
			submit.classList.remove('is-error');
			void submit.offsetWidth; // eslint-disable-line no-void
			submit.classList.add('is-error');
			showStatus(msg, 'error');
		}

		form.addEventListener('submit', function (e) {
			var ok = inputs.map(validate).every(Boolean);
			if (!ok) {
				e.preventDefault();
				var firstBad = form.querySelector('[aria-invalid="true"]');
				if (firstBad) {
					firstBad.focus();
				}
				fail('');
				status.hidden = true;
				ZTR.track('form_error', { form_name: 'visibility_audit', error_type: 'validation' });
				return;
			}

			// No fetch support or no REST URL: let the classic POST happen.
			if (!window.fetch || !ZTR.data.restUrl) {
				return;
			}
			e.preventDefault();

			submit.classList.add('is-loading');
			label.textContent = i18n.sending || labelText;
			showStatus('', '');

			var body = new URLSearchParams(new FormData(form));
			fetch(ZTR.data.restUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8', Accept: 'application/json' },
				body: body.toString()
			})
				.then(function (res) {
					return res.json().then(
						function (json) {
							return { ok: res.ok, json: json };
						},
						function () {
							return { ok: false, json: {} };
						}
					);
				})
				.then(function (r) {
					if (r.ok && r.json && r.json.success) {
						var focus = form.querySelector('[name="ztr_focus"]');
						ZTR.track('generate_lead', { form_name: 'visibility_audit', audit_focus: focus ? focus.value : '' });
						if (r.json.message && success.querySelector('[data-ztr-success-text]')) {
							success.querySelector('[data-ztr-success-text]').textContent = r.json.message;
						}
						form.hidden = true;
						success.hidden = false;
						success.focus();
						return;
					}
					var fields = (r.json && r.json.fields) || {};
					Object.keys(fields).forEach(function (name) {
						var input = form.querySelector('[name="' + name + '"]');
						if (input) {
							setError(input, fields[name]);
						}
					});
					var bad = form.querySelector('[aria-invalid="true"]');
					if (bad) {
						bad.focus();
					}
					fail((r.json && r.json.message) || i18n.error);
					ZTR.track('form_error', { form_name: 'visibility_audit', error_type: 'server' });
				})
				.catch(function () {
					fail(i18n.error);
					ZTR.track('form_error', { form_name: 'visibility_audit', error_type: 'network' });
				});
		});

		// No-JS fallback redirect landed back here with ?ztr_audit=success.
		if (status.getAttribute('data-state') === 'success') {
			ZTR.track('generate_lead', { form_name: 'visibility_audit', audit_focus: '' });
		}
	})();
})();
