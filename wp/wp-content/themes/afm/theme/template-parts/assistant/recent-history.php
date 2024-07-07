<? $user_id = $args['user_id'] ?? null;
if ($user_id) { ?>
    <div id="recent-history" class="bg-white shadow-sm border border-surface-outline-lowest p-4 rounded">
        <div class="font-bold mb-2 text-on-surface text-body-lg"><?= __("Recent History", "afm") ?></div>
        <div id="history-list" class="flex flex-col max-h-64 sm:h-80 overflow-y-auto">
            <? $args = array(
                'post_type'      => 'assistant-thread',
                'posts_per_page' => 20,
                'author'         => $user_id,
                'orderby'        => 'date',
                'order'          => 'DESC'
            );

            $query = new WP_Query($args);
            if ($query->have_posts()) {

            ?>

                <? while ($query->have_posts()) {
                    $query->the_post(); ?>
                    <a class="transition-all border border-surface-outline-low hover:bg-surface-max text-on-surface-high p-2 my-1 rounded text-body-sm w-full text-left" href="<?= get_permalink() ?>"><?= get_the_title() ?></a>
                <? } ?>

            <? } ?>
        </div>
    </div>
<? } ?>