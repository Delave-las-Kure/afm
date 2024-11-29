<?
wp_interactivity_state('AssistantChat', [
  "userId" => get_current_user_id(),
  "apiUrl" => get_field('api_url', 'option'),
  "assistantId" => get_field('assistant_id', 'option'),
  "messageLimit" => ThreadLimitService::get_assistant_message_limit(),
  "messageCount" => ThreadLimitService::get_message_count(),
  "isLimitDisabled" => !!ThreadLimitService::is_limit_disabled(),
]);

ob_start();

$assistant_thread_id = $args['assistant_thread_id'] ?? null;
$list = [];

if ($assistant_thread_id) {
  try {
    $rawData = AiService::get_thread_messages(AssistantThreadService::get_thread_id($assistant_thread_id));
    if ($rawData && property_exists($rawData, 'data'))
      $list = array_reverse($rawData->data);
  } catch (\Throwable $th) {
  }
}

?>
<div id="chat-app" data-iframe-size class="grid gap-x-6 gap-y-6 grid-cols-[1fr_1fr]" data-wp-interactive="AssistantChat" <?= wp_interactivity_data_wp_context([
                                                                                                                            "list" => $list,
                                                                                                                            "isLoading" => false,
                                                                                                                            "errorMsg" => ""
                                                                                                                          ]); ?> data-wp-init="callbacks.init">

  <div class="col-[1/-1]">
    <? get_template_part('template-parts/assistant/chat-window'); ?>
  </div>
  <div class="col-[1/-1] flex gap-x-6 gap-y-6 max-sm:flex-col [&:not(:has(:is(#history-list,.qq-btn)))]:hidden">
    <div class="[&:not(:has(#history-list))]:hidden flex-1">
      <? $user_id = get_current_user_id();
      if ($user_id) {
        get_template_part('template-parts/assistant/recent-history', null, [
          'user_id' => $user_id
        ]);
      } ?>
    </div>

    <div class="[&:not(:has(.qq-btn))]:hidden flex-1">
      <? get_template_part('template-parts/assistant/quick-questions'); ?>
    </div>
  </div>


</div>

<? $html = (string) ob_get_clean();
echo wp_interactivity_process_directives($html); ?>