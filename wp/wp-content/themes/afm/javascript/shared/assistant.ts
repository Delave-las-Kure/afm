import OpenAI from 'openai';
import { AssistantStream } from 'openai/lib/AssistantStream.mjs';

export class Assistant {
	apiUrl = '';
	assistantId = '';
	ai: OpenAI;
	threadId = '';
	stream: AssistantStream;

	constructor(apiUrl, assistantId) {
		this.apiUrl = apiUrl;
		this.assistantId = assistantId;
		this.ai = new OpenAI({
			dangerouslyAllowBrowser: true,
			baseURL: this.apiUrl,
			apiKey: 'not_see',
		});
	}

	async chat(msg) {
		if (!this.threadId) {
			await this.createThread();
		}

		await this.addMessage(msg);

		await this.createRun();
	}

	async createThread() {
		const thread = await this.ai.beta.threads.create();
		this.threadId = thread.id;
	}

	async addMessage(msg) {
		await this.ai.beta.threads.messages.create(this.threadId, {
			role: 'user',
			content: msg,
		});
	}

	async createRun() {
		this.stream = this.ai.beta.threads.runs.stream(this.threadId, {
			assistant_id: this.assistantId,
		});
		// .on('event', (evt) => {
		// 	if (evt.event == 'thread.message.delta') {
		// 		console.log(evt);
		// 	}
		// })

		// .on('textDelta', (textDelta, snapshot) =>
		// 	console.log('textDelta', textDelta)
		// );
	}
}
