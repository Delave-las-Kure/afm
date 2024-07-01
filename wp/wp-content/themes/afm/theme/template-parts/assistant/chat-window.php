<?
wp_interactivity_state('AssistantChat', [
    "apiUrl" => get_field('api_domain', 'option'),
    "assistantId" => get_field('assistant_id', 'option'),
]); ?>
<div class="bg-white shadow-md rounded p-4 mb-4" 
    data-wp-interactive="AssistantChat" 
    <?= wp_interactivity_data_wp_context([
        "list" => [],
        "currentUserMessage" => '',
        "isLoading" => false,
        "messageLimit" => get_assistant_message_limit(),
        "messageCount" => get_message_count()
    ]); ?> 
    data-wp-init="callbacks.init"
>
    <div class="h-64 overflow-y-auto mb-4 bg-gray-100 p-2">
        <div class="overflow-auto max-h-full flex flex-col-reverse">
            <div>
                <template data-wp-each--message="context.list" data-wp-each-key="context.message.id">
                    <? get_template_part('template-parts/assistant/assistant-message'); ?>
                </template>
            </div>
        </div>
    </div>
    
    <form id="chat-form" class="flex flex-col" data-wp-on--submit="actions.submit">
        <textarea 
            type="text" 
            id="message-input" 
            class="flex-1 p-2 border border-gray-300 rounded resize-none" 
            placeholder="<?= __("Type your message...", "afm") ?>" 
            data-wp-bind--value="context.currentUserMessage" 
            data-wp-on--input="actions.setCurrentUserMessage">
        </textarea>
        <div class="flex justify-between mt-2 items-center">
            <div>
                <span data-wp-text="context.messageCount"></span> / <span data-wp-text="context.messageLimit"></span>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded"><?= __("Send", "afm") ?></button>
        </div>
            
    </form>
</div>