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
			baseURL: this.apiUrl + '/v1/',
			apiKey: 'not_see',
		});
	}

	async createThread() {
		const thread = await this.ai.beta.threads.create(undefined, {
			headers: {
				Authorization: await this.getJWT(),
			},
		});
		this.threadId = thread.id;
	}

	async addMessage(msg: string) {
		if (!this.threadId) {
			await this.createThread();
		}

		const aiMsg = await this.ai.beta.threads.messages.create(
			this.threadId,
			{
				role: 'user',
				content: msg,
			},
			{
				headers: {
					Authorization: await this.getJWT(),
				},
			}
		);

		return aiMsg;
	}

	async deleteMessage(id: string) {
		return this.ai.beta.threads.messages.del(this.threadId, id, {
			headers: {
				Authorization: await this.getJWT(),
			},
		});
	}

	async *createRun() {
		this.stream = await this.ai.beta.threads.runs.create(
			this.threadId,
			{
				assistant_id: this.assistantId,
				stream: true,
			},
			{
				headers: {
					Authorization: await this.getJWT(),
				},
			}
		);

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

	async incrementMsgCount() {
		const res = await fetch(`${API_URL}assistant/v1/increment-msg-count/`, {
			method: 'PUT',
			headers: {
				'X-WP-Nonce': REST_NONCE,
			},
		});
		const data: { message_count: number; max_messages: number } =
			await res.json();
		return data;
	}

	async getJWT() {
		const res = await fetch(`${API_URL}assistant/v1/jwt/`, {
			headers: {
				'X-WP-Nonce': REST_NONCE,
			},
		});
		const data: { token: string } = await res.json();
		return data.token;
	}
}
