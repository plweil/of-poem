// js/selectFeaturedPoem.js

export function selectFeaturedPoem(poems) {
	if (!poems.length) return null;

	const daysSinceEpoch = Math.floor(
			Date.now() / (1000 * 60 * 60 * 24)
	);

	const index = daysSinceEpoch % poems.length;
	return poems[index];
}
