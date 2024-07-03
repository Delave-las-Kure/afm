import OpenAI from 'openai';
import {
	filter,
	find,
	has,
	isArray,
	isObject,
	map,
	merge,
	mergeWith,
	union,
} from 'lodash';

import type { Stream } from 'openai/streaming.mjs';
import { API_URL, REST_NONCE } from './consts';

export class Assistant {
	apiUrl = '';
	assistantId = '';
	ai: OpenAI;
	threadId = '';
	stream: Stream<OpenAI.Beta.Assistants.AssistantStreamEvent>;

	constructor(apiUrl, assistantId) {
		this.apiUrl = apiUrl;
		this.assistantId = assistantId;
		this.ai = new OpenAI({
			dangerouslyAllowBrowser: true,
			baseURL: this.apiUrl,
			apiKey: 'not_see',
			defaultHeaders: {
				'X-WP-Nonce': REST_NONCE,
			},
		});
	}

	async createThread(msg: string) {
		const thread = await this.ai.beta.threads.create({
			message: msg,
		} as any);
		this.threadId = thread.id;
	}

	async addMessage(msg: string) {
		if (!this.threadId) {
			await this.createThread(msg);
		}

		const aiMsg = (await this.ai.beta.threads.messages.create(
			this.threadId,
			{
				role: 'user',
				content: msg,
			}
		)) as OpenAI.Beta.Threads.Messages.Message & {
			message_count: number;
			max_messages: number;
		};

		return aiMsg;
	}

	async deleteMessage(id: string) {
		return this.ai.beta.threads.messages.del(this.threadId, id);
	}

	async *createRun() {
		this.stream = await this.ai.beta.threads.runs.create(this.threadId, {
			assistant_id: this.assistantId,
			stream: true,
		});

		let msg!: OpenAI.Beta.Threads.Messages.Message;

		for await (const event of this.stream) {
			if (event.event == 'thread.message.created') {
				msg = event.data;
				yield msg;
			} else if (event.event == 'thread.message.delta') {
				msg = mergeWith({}, msg, event.data.delta, this.mergeDelta);
				yield msg;
			}
		}
	}

	private mergeDelta(objValue: any, srcValue: any) {
		if (Array.isArray(objValue) && Array.isArray(srcValue)) {
			const result: any[] = [];

			srcValue.forEach((srcItem) => {
				const matchIndex = objValue.findIndex(
					(objItem) => objItem.index === srcItem.index
				);

				if (matchIndex > -1) {
					if (
						objValue[matchIndex].type === 'text' &&
						srcItem.type === 'text'
					) {
						objValue[matchIndex].text.value += srcItem.text.value;
					}
					result.push(objValue[matchIndex]);
				} else {
					result.push(srcItem);
				}
			});

			objValue.forEach((objItem) => {
				if (
					!srcValue.some((srcItem) => srcItem.index === objItem.index)
				) {
					result.push(objItem);
				}
			});

			return result;
		}
	}
}
