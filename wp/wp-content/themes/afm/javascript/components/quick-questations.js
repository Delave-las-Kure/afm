import { store, getElement, getContext } from '@wordpress/interactivity';

console.log('red');
store('AssistanceQuickQuestions', {
	actions: {
		start() {
			const { item } = getContext();
			console.log(item);
		},
	},
});
