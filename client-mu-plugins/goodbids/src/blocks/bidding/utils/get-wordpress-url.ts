export function getWordPressUrl() {
	return window.location.href.replace(/\/auction\/.*/g, '');
}
