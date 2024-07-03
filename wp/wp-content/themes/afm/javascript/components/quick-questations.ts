import { store, getElement, getContext } from '@wordpress/interactivity';
import type { AssistantChatStore } from './assistant-chat';

interface AssistantQuickQuestionsContextProps {
	item: {
		title: string;
		question?: string;
	};
}

store('AssistantQuickQuestions', {
	actions: {
		start() {
			const { item } = getContext<AssistantQuickQuestionsContextProps>();

			const chatStore = store<typeof AssistantChatStore>('AssistantChat');
			chatStore.actions.reset();
			chatStore.actions.runChat(item.question || item.title);
		},
	},
});
