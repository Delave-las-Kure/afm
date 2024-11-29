import { store, getElement, getContext } from '@wordpress/interactivity';
import { AssistantChatStore } from './assistant-chat';

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

			const chatStore = AssistantChatStore;
			chatStore.actions.reset();
			chatStore.actions.runChat(item.question || item.title);
		},
	},
});
