<? ob_start(); ?>
<? wp_interactivity_state('AssistantChat', [
  "apiUrl" => get_field('api_url', 'option'),
  "assistantId" => get_field('assistant_id', 'option'),
  "messageLimit" => ThreadLimitService::get_assistant_message_limit(),
  "messageCount" => ThreadLimitService::get_message_count(),
]); ?>
<div id="chat-app" class="max-w-lg mx-auto p-4" data-wp-interactive="AssistantChat" <?= wp_interactivity_data_wp_context([
                                                                                      "list" => [],
                                                                                      "isLoading" => false,
                                                                                      "errorMsg" => ""
                                                                                    ]); ?> data-wp-init="callbacks.init">

  <div>
    <? get_template_part('template-parts/assistant/recent-history'); ?>
  </div>
  <div>
    <? get_template_part('template-parts/assistant/chat-window'); ?>
  </div>
  <div>
    <? get_template_part('template-parts/assistant/quick-questions'); ?>
  </div>

</div>

<? $html = (string) ob_get_clean();
echo wp_interactivity_process_directives($html); ?>