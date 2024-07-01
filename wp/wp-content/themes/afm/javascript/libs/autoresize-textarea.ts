export function addAutoResizeTextarea(el: HTMLTextAreaElement) {
	el.style.boxSizing = 'border-box';
	var offset = el.offsetHeight - el.clientHeight;
	el.addEventListener('input', () => {
		el.style.height = 'auto';
		el.style.height = el.scrollHeight + offset + 'px';
	});
	el.removeAttribute('data-autoresize');
}
