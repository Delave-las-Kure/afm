import { store, getElement, getContext } from '@wordpress/interactivity';
import { Assistant } from '../shared/assistant';
import type OpenAI from 'openai';
import { ChatCompletionRole } from 'openai/resources/index.mjs';
import { addAutoResizeTextarea } from '../libs/autoresize-textarea';

export interface AssistantChatContextProps {
	currentUserMessage: string;
	isLoading: boolean;
	assistant: Assistant;
	message: OpenAI.Beta.Threads.Messages.Message;
	list: OpenAI.Beta.Threads.Messages.Message[];
}

export interface AssistantChatStateProps {
	apiUrl: string;
	assistantId: string;
}

export const AssistantChatStore = store('AssistantChat', {
	state: {} as AssistantChatStateProps,
	callbacks: {
		init() {
			const ctx = getContext<AssistantChatContextProps>();
			ctx.assistant = new Assistant(state.apiUrl, state.assistantId);

			const el = getElement();
			const textarea = el.ref?.querySelector('textarea');
			textarea && addAutoResizeTextarea(textarea);
		},
	},
	actions: {
		setCurrentUserMessage(e) {
			const ctx = getContext<AssistantChatContextProps>();
			ctx.currentUserMessage = e.target.value;
		},

		async deleteMessage(id: string) {
			const ctx = getContext<AssistantChatContextProps>();
			console.log(await ctx.assistant.deleteMessage(id));
			ctx.list = ctx.list.filter((i) => i.id != id);
		},

		async submit(e: Event) {
			e.preventDefault();
			const ctx = getContext<AssistantChatContextProps>();
			const msg = ctx.currentUserMessage;

			ctx.currentUserMessage = '';
			ctx.isLoading = true;

			const message = await ctx.assistant.addMessage(msg);

			ctx.list.push(message);

			for await (const msg of ctx.assistant.createRun()) {
				const ind = ctx.list.findIndex((cMsg) => cMsg.id == msg.id);
				if (ind != -1) {
					ctx.list.splice(ind, 1, msg);
				} else {
					ctx.list.push(msg);
				}
			}

			ctx.isLoading = false;
		},
	},
});

const { state, actions } = AssistantChatStore;
