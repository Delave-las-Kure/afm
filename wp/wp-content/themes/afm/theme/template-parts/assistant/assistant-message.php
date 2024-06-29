<div 
  class="p-4 bg-ye my-3" 
  data-wp-interactive="AssistantChatMessage" 
  data-wp-class--assistant-chat-message--user="state.isUserMessage" 
  data-wp-class--assistant-chat-message--assistant="!state.isUserMessage"
  data-wp-init="callbacks.init"
>
  <div class="text-bold">
    <div data-wp-on-async--click="actions.delete">x</div>
    <span data-wp-bind--hidden="!state.isUserMessage">
      <?= __("User", "afm") ?>
    </span>
    <span data-wp-bind--hidden="state.isUserMessage">
      <?= __("Assistant", "afm") ?>
    </span>

  </div>
  <div class="whitespace-pre-line" data-wp-text="state.messageText"></div>
</div>