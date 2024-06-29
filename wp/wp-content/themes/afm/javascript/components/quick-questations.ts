import { store, getElement, getContext } from '@wordpress/interactivity';

store('AssistantQuickQuestions', {
	actions: {
		start() {
			const { item } = getContext();
		},
	},
});
