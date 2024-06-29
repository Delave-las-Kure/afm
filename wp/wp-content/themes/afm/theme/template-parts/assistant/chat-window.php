<?
wp_interactivity_state('AssistantChat', [
    "apiUrl" => get_field('api_url', 'options'),
    "assistantId" => get_field('assistant_id', 'options'),
]); ?>
<div class="bg-white shadow-md rounded p-4 mb-4" data-wp-interactive="AssistantChat" <?= wp_interactivity_data_wp_context([
                                                                                            "list" => [],
                                                                                            "currentUserMessage" => '',
                                                                                            "isLoading" => false
                                                                                        ]); ?> data-wp-init="callbacks.init">
    <div class="h-64 overflow-y-auto mb-4 bg-gray-100 p-2">
        <template data-wp-each--message="context.list" data-wp-each-key="context.message.id">
            <? get_template_part('template-parts/assistant/assistant-message'); ?>
        </template>
    </div>
    <form id="chat-form" class="flex" data-wp-on--submit="actions.submit">
        <textarea 
            type="text" 
            id="message-input" 
            class="flex-1 p-2 border border-gray-300 rounded mr-2" 
            placeholder="<?= __("Type your message...", "afm") ?>" 
            data-wp-bind--value="context.currentUserMessage" 
            data-wp-on--input="actions.setCurrentUserMessage"></textarea>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded"><?= __("Send", "afm") ?></button>
    </form>
</div>