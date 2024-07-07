import { store, getElement, getContext } from '@wordpress/interactivity';
import { Assistant } from '../shared/assistant';
import type OpenAI from 'openai';
import { addAutoResizeTextarea } from '../libs/autoresize-textarea';

export interface AssistantChatContextProps {
	isLoading: boolean;
	assistant: Assistant;
	list: OpenAI.Beta.Threads.Messages.Message[];
	message: OpenAI.Beta.Threads.Message;
	errorMsg: string;
}

export interface AssistantChatStateProps {
	apiUrl: string;
	assistantId: string;
	messageLimit: number;
	messageCount: number;
	readonly hasError: boolean;
	readonly isLocked: boolean;
}

export const AssistantChatStore = store('AssistantChat', {
	state: {
		get isLocked() {
			const { isLoading } = getContext<AssistantChatContextProps>();
			return state.messageCount >= state.messageLimit || isLoading;
		},
		get hasError() {
			return !!getContext<AssistantChatContextProps>().errorMsg;
		},
	} as AssistantChatStateProps,
	callbacks: {
		init() {
			const ctx = getContext<AssistantChatContextProps>();
			ctx.assistant = new Assistant(state.apiUrl, state.assistantId);
		},
	},
	actions: {
		reset() {
			const ctx = getContext<AssistantChatContextProps>();
			ctx.list = [];
			ctx.assistant = new Assistant(state.apiUrl, state.assistantId);
		},

		async deleteMessage(id: string) {
			const ctx = getContext<AssistantChatContextProps>();
			console.log(await ctx.assistant.deleteMessage(id));
			ctx.list = ctx.list.filter((i) => i.id != id);
		},

		async runChat(messageText: string) {
			const ctx = getContext<AssistantChatContextProps>();
			ctx.errorMsg = '';

			try {
				ctx.isLoading = true;

				const message = await ctx.assistant.addMessage(messageText);

				ctx.list.push(message);

				state.messageCount = message.message_count;
				state.messageLimit = message.max_messages;

				for await (const msg of ctx.assistant.createRun()) {
					const ind = ctx.list.findIndex((cMsg) => cMsg.id == msg.id);
					if (ind != -1) {
						ctx.list.splice(ind, 1, msg);
					} else {
						ctx.list.push(msg);
					}
				}
			} catch (error) {
				console.error(error);
				ctx.errorMsg = error.message;
			}

			ctx.isLoading = false;
		},
	},
});

const { state, actions } = AssistantChatStore;