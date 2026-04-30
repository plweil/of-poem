async function loadIncludes() {
	const targets = document.querySelectorAll('[data-include]');

	for (const el of targets) {
		const url = el.dataset.include;
		const title = el.dataset.title;

		try {
			const res = await fetch(url);
			el.innerHTML = await res.text();

			// Set page title in header, if provided
			if (title) {
				const h1 = el.querySelector('h1');
				if (h1) h1.textContent = title;
			}

			// Set current year in footer, if present
			const yearEl = el.querySelector('#year');
			if (yearEl) {
				yearEl.textContent = new Date().getFullYear();
			}

		} catch {
			el.innerHTML = '<!-- include failed -->';
		}
	}
}

document.addEventListener('DOMContentLoaded', loadIncludes);
