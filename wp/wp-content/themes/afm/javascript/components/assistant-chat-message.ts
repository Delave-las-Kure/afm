import { store, getElement, getContext } from '@wordpress/interactivity';
import { Assistant } from '../shared/assistant';
import type OpenAI from 'openai';
import {
	AssistantChatContextProps,
	AssistantChatStore,
} from './assistant-chat';

interface AssistantChatMessageContextProps {}
interface AssistantChatMessageStateProps {
	messageText: string;
	isUserMessage: boolean;
}

const AssistantChatMessageStore = store('AssistantChatMessage', {
	state: {
		get messageId() {
			const ctx = getContext<AssistantChatContextProps>('AssistantChat');
			return ctx.list.find((el) => el.id == ctx.message.id)!.id;
		},
		get messageText() {
			const ctx = getContext<AssistantChatContextProps>('AssistantChat');
			const message = ctx.list.find((el) => el.id == ctx.message.id);
			const text = message!.content.reduce(
				(prev, cur) =>
					prev + (cur.type == 'text' ? cur.text.value : ''),
				''
			);
			return text;
		},

		get isUserMessage() {
			const ctx = getContext<AssistantChatContextProps>('AssistantChat');
			const msg = ctx.list.find((el) => el.id == ctx.message.id);

			return msg?.role == 'user';
		},
	},
	callbacks: {
		init() {},
	},
	actions: {
		async delete() {
			await store<typeof AssistantChatStore>(
				'AssistantChat'
			).actions.deleteMessage(state.messageId);
		},
	},
});

const { state, actions } = AssistantChatMessageStore;
