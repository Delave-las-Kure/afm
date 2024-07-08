interface Props {
	event: string;
	options?: Record<string, any>;
}

export default function track({ event, options = {} }: Props) {
	if (typeof window == 'undefined') return;

	(window as any).dataLayer = (window as any).dataLayer || [];
	(window as any).dataLayer.push({ event, ...options });

	/*console.info(
		`Sent ${event} to GTM with options: ${JSON.stringify(options)}`
	);*/
}
