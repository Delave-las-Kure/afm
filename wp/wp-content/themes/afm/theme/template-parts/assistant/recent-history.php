<? $user_id = $args['user_id'] ?? null;
if ($user_id) { ?>
    <div id="recent-history" class="bg-white shadow-md rounded p-4 mb-4">
        <h2 class="text-lg font-bold mb-2"><?= __("Recent History", "afm") ?></h2>
        <div id="history-list" class="h-48 overflow-y-auto">
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
                <div>
                    <? while ($query->have_posts()) {
                        $query->the_post(); ?>
                        <a href="<?= get_permalink() ?>"><?= get_the_title() ?></a>
                    <? } ?>
                </div>
            <? } ?>
        </div>
    </div>
<? } ?>