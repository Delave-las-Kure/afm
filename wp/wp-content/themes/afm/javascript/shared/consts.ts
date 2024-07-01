export const API_URL =
	document.querySelector<HTMLLinkElement>('link[rel="https://api.w.org/"]')
		?.href || '/wp-json/';

export const REST_NONCE =
	document.querySelector<HTMLMetaElement>('meta[name="rest-nonce"]')
		?.content || '';
