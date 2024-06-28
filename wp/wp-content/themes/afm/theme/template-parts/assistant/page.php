<div id="chat-app" class="max-w-lg mx-auto p-4">
  <div>
    <?
    ob_start();

    get_template_part('template-parts/assistant/recent-history');
    $html = (string) ob_get_clean();
    echo wp_interactivity_process_directives($html);
    ?>
  </div>
  <div>
    <?
    ob_start();

    get_template_part('template-parts/assistant/chat-window');
    $html = (string) ob_get_clean();
    echo wp_interactivity_process_directives($html);
    ?>
  </div>
  <div>
    <?
    ob_start();
    get_template_part('template-parts/assistant/quick-questions');
    $html = (string) ob_get_clean();
    echo wp_interactivity_process_directives($html);
    ?>
  </div>

</div>