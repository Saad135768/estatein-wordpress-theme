/**
 * Estatein front-end behaviour: banner dismissal, mobile navigation,
 * scroll-snap sliders with counters, and expandable FAQ answers.
 */
(() => {
	const storage = {
		get(key) {
			try {
				return window.localStorage.getItem(key);
			} catch (error) {
				return null;
			}
		},
		set(key, value) {
			try {
				window.localStorage.setItem(key, value);
			} catch (error) {
				return null;
			}
			return value;
		},
	};

	const initBanner = () => {
		const banner = document.querySelector('[data-banner]');
		if (!banner) {
			return;
		}
		if (storage.get('estatein-banner-dismissed') === '1') {
			banner.classList.add('is-hidden');
			return;
		}
		banner.querySelector('[data-banner-close]')?.addEventListener('click', () => {
			banner.classList.add('is-hidden');
			storage.set('estatein-banner-dismissed', '1');
		});
	};

	const initNav = () => {
		const toggle = document.querySelector('[data-nav-toggle]');
		const nav = document.querySelector('[data-nav]');
		if (!toggle || !nav) {
			return;
		}

		const setOpen = (open) => {
			toggle.setAttribute('aria-expanded', String(open));
			nav.classList.toggle('is-open', open);
		};

		toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));

		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && nav.classList.contains('is-open')) {
				setOpen(false);
				toggle.focus();
			}
		});

		nav.addEventListener('click', (event) => {
			if (event.target.closest('a')) {
				setOpen(false);
			}
		});

		window.matchMedia('(min-width: 1024px)').addEventListener('change', (event) => {
			if (event.matches) {
				setOpen(false);
			}
		});
	};

	const pad = (number) => String(number).padStart(2, '0');

	const initSlider = (slider) => {
		const track = slider.querySelector('[data-slider-track]');
		const prev = slider.querySelector('[data-slider-prev]');
		const next = slider.querySelector('[data-slider-next]');
		const current = slider.querySelector('[data-slider-current]');
		const total = slider.querySelector('[data-slider-total]');
		const items = track ? Array.from(track.children) : [];

		if (!track || items.length === 0) {
			return;
		}

		const step = () => {
			const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
			return items[0].getBoundingClientRect().width + gap;
		};

		const update = () => {
			const maxScroll = track.scrollWidth - track.clientWidth;
			const perView = Math.max(1, Math.round((track.clientWidth + 1) / step()));
			const positions = Math.max(1, items.length - perView + 1);
			const index = Math.round(track.scrollLeft / step());
			if (current) {
				current.textContent = pad(Math.min(index + 1, positions));
			}
			if (total) {
				total.textContent = pad(positions);
			}
			slider.classList.toggle('is-static', positions === 1);
			prev.disabled = track.scrollLeft <= 2;
			next.disabled = track.scrollLeft >= maxScroll - 2;

			items.forEach((item) => {
				const box = item.getBoundingClientRect();
				const view = track.getBoundingClientRect();
				const visible = box.left >= view.left - 2 && box.right <= view.right + 2;
				item.toggleAttribute('inert', !visible);
				item.setAttribute('aria-hidden', String(!visible));
			});
		};

		let frame = 0;
		track.addEventListener(
			'scroll',
			() => {
				cancelAnimationFrame(frame);
				frame = requestAnimationFrame(update);
			},
			{ passive: true }
		);

		prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
		next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));

		track.addEventListener('keydown', (event) => {
			if (event.key === 'ArrowRight') {
				event.preventDefault();
				next.click();
			}
			if (event.key === 'ArrowLeft') {
				event.preventDefault();
				prev.click();
			}
		});

		new ResizeObserver(update).observe(track);
		update();
	};

	const initFaqs = () => {
		document.querySelectorAll('[data-faq-toggle]').forEach((button) => {
			const answer = document.getElementById(button.getAttribute('aria-controls'));
			const closedLabel = button.textContent.trim();
			const openLabel = button.dataset.labelOpen;

			button.addEventListener('click', () => {
				const open = button.getAttribute('aria-expanded') !== 'true';
				button.setAttribute('aria-expanded', String(open));
				button.textContent = open ? openLabel : closedLabel;
				answer.hidden = !open;
			});
		});
	};

	const initGallery = (gallery) => {
		const track = gallery.querySelector('[data-slider-track]');
		const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
		const dots = Array.from(gallery.querySelectorAll('.gallery__dot'));
		const items = track ? Array.from(track.children) : [];
		if (!track || items.length === 0) {
			return;
		}

		const activate = (index) => {
			thumbs.forEach((thumb, i) => thumb.toggleAttribute('aria-current', i === index));
			dots.forEach((dot, i) => dot.classList.toggle('is-active', i === index));
		};

		thumbs.forEach((thumb) => {
			thumb.addEventListener('click', () => {
				const index = Number(thumb.dataset.galleryThumb);
				track.scrollTo({ left: items[index].offsetLeft - items[0].offsetLeft, behavior: 'smooth' });
				activate(index);
			});
		});

		track.addEventListener(
			'scroll',
			() => {
				const step = items[0].getBoundingClientRect().width + (parseFloat(getComputedStyle(track).columnGap) || 0);
				activate(Math.round(track.scrollLeft / step));
			},
			{ passive: true }
		);
	};

	const initTabs = () => {
		document.querySelectorAll('[data-tabs]').forEach((root) => {
			const tabs = Array.from(root.querySelectorAll('[data-tab]'));
			const cards = Array.from(root.querySelectorAll('[data-region]'));
			tabs.forEach((tab) => {
				tab.addEventListener('click', () => {
					const region = tab.dataset.tab;
					tabs.forEach((t) => t.setAttribute('aria-selected', String(t === tab)));
					cards.forEach((card) => {
						card.hidden = region !== 'all' && card.dataset.region !== region;
					});
				});
			});
		});
	};

	const initFilters = () => {
		document.querySelectorAll('[data-autosubmit]').forEach((select) => {
			select.addEventListener('change', () => select.form.requestSubmit());
		});
	};

	const initAgentPrefill = () => {
		const agent = new URLSearchParams(window.location.search).get('agent');
		const message = document.querySelector('#enquiry-contact textarea[name="message"]');
		if (agent && message && !message.value) {
			message.value = `Hi ${agent}, `;
		}
	};

	const init = () => {
		initBanner();
		initNav();
		document.querySelectorAll('[data-slider]').forEach(initSlider);
		document.querySelectorAll('[data-gallery]').forEach(initGallery);
		initFaqs();
		initTabs();
		initFilters();
		initAgentPrefill();
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
