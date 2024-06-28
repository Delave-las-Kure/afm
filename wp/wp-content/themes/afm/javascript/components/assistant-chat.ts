import { store, getElement, getContext } from '@wordpress/interactivity';
import { Assistant } from '../shared/assistant';

interface ContextProps {
	list: any[];
	currentMessage: string;
	isLoading: boolean;
	assistant: Assistant;
}

interface StateProps {
	apiUrl: string;
	assistantId: string;
}

const { state, actions } = store('AssistantChat', {
	state: {} as StateProps,
	callbacks: {
		init() {
			console.log(state);
			const ctx = getContext<ContextProps>();
			ctx.assistant = new Assistant(state.apiUrl, state.assistantId);
		},
	},
	actions: {
		setCurrentMessage(e) {
			const ctx = getContext<ContextProps>();
			ctx.currentMessage = e.target.value;
		},

		async submit(e) {
			e.preventDefault();
			const ctx = getContext<ContextProps>();
			const msg = ctx.currentMessage;

			ctx.currentMessage = '';
			ctx.isLoading = true;

			await ctx.assistant.chat(msg);

			ctx.isLoading = false;
		},
	},
});
