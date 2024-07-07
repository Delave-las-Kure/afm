<div class="bg-white shadow-md rounded p-4 mb-4 border border-surface-outline-lowest">
    <div class="h-64 overflow-y-auto mb-4 bg-surface-high p-2 rounded">
        <div class="overflow-auto max-h-full flex flex-col-reverse">
            <div>
                <template data-wp-each--message="context.list" data-wp-each-key="context.message.id">
                    <? get_template_part('template-parts/assistant/assistant-message'); ?>
                </template>
            </div>
        </div>
    </div>
    <? wp_interactivity_state('AssistantChatForm', [
        "messageLimit" => ThreadLimitService::get_assistant_message_limit(),
        "messageCount" => ThreadLimitService::get_message_count(),
    ]); ?>
    <div data-wp-interactive="AssistantChatForm" <?= wp_interactivity_data_wp_context([
                                                        "currentUserMessage" => '',
                                                    ]); ?> data-wp-init="callbacks.init">
        <form id="chat-form" class="flex flex-col" data-wp-on--submit="actions.submit">
            <textarea type="text" id="message-input" class="flex-1 p-2 border border-gray-300 rounded resize-none" placeholder="<?= __("Type your message...", "afm") ?>" data-wp-bind--value="context.currentUserMessage" data-wp-on--input="actions.setCurrentUserMessage">
        </textarea>
            <div class="flex justify-between mt-2 items-center">
                <div class="text-body-sm">
                    <span data-wp-text="state.messageCount"></span> / <span data-wp-text="state.messageLimit"></span>
                </div>
                <? get_template_part('template-parts/atoms/button', null, [
                    'content' => __("Send", "afm"),
                    'is_loading' => true,
                    'data' => [
                        "wp-bind--disabled" => "state.isLocked",
                        "wp-class--afm-button--pending" => "state.isLoading"
                    ]
                ]); ?>
            </div>
            <div class="text-error-high text-center mt-3" data-wp-bind--hidden="!state.hasError" data-wp-text="state.errorMsg"></div>
        </form>
    </div>
</div>