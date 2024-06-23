<div id="chat-window" class="bg-white shadow-md rounded p-4 mb-4">
    <div id="messages" class="h-64 overflow-y-auto mb-4 bg-gray-100 p-2">
        <!-- Chat messages will appear here -->
    </div>
    <form id="chat-form" class="flex">
        <textarea type="text" id="message-input" class="flex-1 p-2 border border-gray-300 rounded mr-2" placeholder="<?= __("Type your message...", "afm")?>" ></textarea>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded"><?= __("Send", "afm") ?></button>
    </form>
</div>