<div 
  class="p-4 my-3 assistant-chat-message" 
  data-wp-interactive="AssistantChatMessage" 
  data-wp-class--assistant-chat-message--user="state.isUserMessage" 
  data-wp-class--assistant-chat-message--assistant="!state.isUserMessage"
  data-wp-init="callbacks.init"
>
  <div class="text-bold">
    <span class="text-body-sm font-bold text-on-surface-low" data-wp-bind--hidden="!state.isUserMessage">
      <?= __("User", "afm") ?>
    </span>
    <span class="text-body-sm font-bold text-on-surface-low" data-wp-bind--hidden="state.isUserMessage">
      <?= __("Assistant", "afm") ?>
    </span>
  </div>
  <div class="whitespace-pre-line" data-wp-text="state.messageText"></div>
</div>