import { store, getElement, getContext } from '@wordpress/interactivity';
import { Assistant } from '../shared/assistant';
import type OpenAI from 'openai';
import { addAutoResizeTextarea } from '../libs/autoresize-textarea';
import type {
	AssistantChatContextProps,
	AssistantChatStore,
} from './assistant-chat';

export interface AssistantChatFormContextProps {
	currentUserMessage: string;
}

export interface AssistantChatFormStateProps {
	readonly hasError: boolean;
	readonly isLocked: boolean;
	readonly messageLimit: number;
	readonly messageCount: number;
	readonly errorMsg: string;
	readonly isLoading: boolean;
}

export const AssistantChatFormStore = store('AssistantChatForm', {
	state: {
		get messageLimit() {
			const assistantChatStore =
				store<typeof AssistantChatStore>('AssistantChat');
			return assistantChatStore.state.messageLimit;
		},
		get messageCount() {
			const assistantChatStore =
				store<typeof AssistantChatStore>('AssistantChat');
			return assistantChatStore.state.messageCount;
		},
		get isLocked() {
			const assistantChatStore =
				store<typeof AssistantChatStore>('AssistantChat');
			return assistantChatStore.state.isLocked;
		},
		get hasError() {
			const assistantChatStore =
				store<typeof AssistantChatStore>('AssistantChat');
			return assistantChatStore.state.hasError;
		},
	} as AssistantChatFormStateProps,
	callbacks: {
		init() {
			const el = getElement();
			const textarea = el.ref?.querySelector('textarea');
			textarea && addAutoResizeTextarea(textarea);
		},
	},
	actions: {
		setCurrentUserMessage(e) {
			const ctx = getContext<AssistantChatFormContextProps>();
			ctx.currentUserMessage = e.target.value;
		},

		submit(e: Event) {
			console.log('red');
			e.preventDefault();
			const ctx = getContext<AssistantChatFormContextProps>();
			const msg = ctx.currentUserMessage;
			ctx.currentUserMessage = '';
			if (msg)
				store<typeof AssistantChatStore>(
					'AssistantChat'
				).actions.runChat(msg);
		},
	},
});

const { state, actions } = AssistantChatFormStore;
