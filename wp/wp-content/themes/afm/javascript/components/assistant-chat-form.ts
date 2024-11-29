import { store, getElement, getContext } from '@wordpress/interactivity';
import { Assistant } from '../shared/assistant';
import type OpenAI from 'openai';
import { addAutoResizeTextarea } from '../libs/autoresize-textarea';
import {
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
			const assistantChatStore = AssistantChatStore;
			return assistantChatStore.state.messageLimit;
		},
		get messageCount() {
			const assistantChatStore = AssistantChatStore;
			return assistantChatStore.state.messageCount;
		},
		get isLocked() {
			const assistantChatStore = AssistantChatStore;
			return assistantChatStore.state.isLocked;
		},
		get hasError() {
			const assistantChatStore = AssistantChatStore;
			return assistantChatStore.state.hasError;
		},
		get errorMsg() {
			const assistantChatCtx =
				getContext<AssistantChatContextProps>('AssistantChat');
			return assistantChatCtx.errorMsg;
		},
		get isLoading() {
			const assistantChatCtx =
				getContext<AssistantChatContextProps>('AssistantChat');
			return assistantChatCtx.isLoading;
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
			e.preventDefault();
			const ctx = getContext<AssistantChatFormContextProps>();
			const msg = ctx.currentUserMessage;
			ctx.currentUserMessage = '';
			if (msg) AssistantChatStore.actions.runChat(msg);
		},
	},
});

const { state, actions } = AssistantChatFormStore;
