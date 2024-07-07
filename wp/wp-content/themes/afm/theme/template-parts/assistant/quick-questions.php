<div class="bg-white shadow-sm border border-surface-outline-lowest p-4 rounded" data-wp-interactive="AssistantQuickQuestions" <?= wp_interactivity_data_wp_context(["list" => get_field("questions_list", "option") ? get_field("questions_list", "option") : [] ]); ?>>
  <h2 class="font-bold mb-2 text-on-surface text-body-lg"><?= __("Quick Questions", "afm") ?></h2>
  <div class="flex flex-col max-h-64 sm:h-80 overflow-y-auto">
    <template data-wp-each="context.list" data-wp-each-key="context.item.title">
      <button class="transition-all border border-surface-outline-low hover:bg-surface-max text-on-surface-high p-2 my-1 rounded text-body-sm w-full text-left" data-wp-on-async--click="actions.start" data-wp-text="context.item.title"></button>
    </template>
  </div>
</div>