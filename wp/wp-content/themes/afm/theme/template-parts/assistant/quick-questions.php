<div class="bg-white shadow-md rounded p-4" data-wp-interactive="AssistantQuickQuestions" 
<?= wp_interactivity_data_wp_context(["list" => get_field("questions_list", "option")]); ?>>
  <h2 class="text-lg font-bold mb-2"><?= __("Quick Questions", "afm") ?></h2>
  <div class="flex flex-wrap">
    <template data-wp-each="context.list" data-wp-each-key="context.item.title">
      <button class="bg-gray-200 text-gray-700 p-2 m-1 rounded" data-wp-on-async--click="actions.start" data-wp-text="context.item.title"></button>
    </template>
  </div>
</div>