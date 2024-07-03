<?
add_action('rest_api_init', function () {
  $base = 'assistant/v1';

  register_rest_route($base, '/threads', array(
    'methods' => 'POST',
    'callback' => 'assistant_create_thread',
    'permission_callback' => 'assistant_can_run_thread_permission_callback',
  ));

  register_rest_route($base, '/threads/(?P<post_thread_id>\d+)/messages', array(
    'methods' => 'POST',
    'callback' => 'assistant_create_message',
    'permission_callback' => 'assistant_add_msg_permission_callback',
  ));

  register_rest_route($base, '/threads/(?P<post_thread_id>\d+)/runs', [
    'methods'  => 'POST',
    'callback' => 'assistant_create_run',
    'permission_callback' => 'assistant_can_run_thread_permission_callback',
  ]);
});

function assistant_create_thread(WP_REST_Request $request)
{
  $api_key = get_field('api_key', 'option');

  $body = json_decode($request->get_body());

  $client = OpenAI::factory()->withApiKey($api_key)
    ->withHttpHeader('OpenAI-Beta', 'assistants=v2')
    ->make();

  $response = $client->threads()->create([]);

  $thread_post_id = AssistantThreadService::create($response->id, $body->message);

  return new WP_REST_Response([...$response->toArray(), 'id' => $thread_post_id]);
}

function assistant_create_message(WP_REST_Request $request)
{
  $body = json_decode($request->get_body(), true);
  $post_thread_id = $request->get_param("post_thread_id");
  $thread_id = AssistantThreadService::get_thread_id($post_thread_id);


  $api_key = get_field('api_key', 'option');
  $client = OpenAI::factory()->withApiKey($api_key)
    ->withHttpHeader('OpenAI-Beta', 'assistants=v2')
    ->make();

  $api_key = get_field('api_key', 'option');


  $response = $client->threads()->messages()->create($thread_id, $body);
  ThreadLimitService::increment_message_count();


  return new WP_REST_Response(array(
    ...$response->toArray(),
    'message_count' => min(ThreadLimitService::get_message_count(), max(ThreadLimitService::get_assistant_message_limit(), 0)),
    'max_messages' =>  ThreadLimitService::get_assistant_message_limit(),
  ));
}

function assistant_create_run(WP_REST_Request $request)
{
  $api_key = get_field('api_key', 'option'); // Получаем ключ API из настроек
  $post_thread_id = $request->get_param("post_thread_id");
  $thread_id = AssistantThreadService::get_thread_id($post_thread_id);
  // Считываем тело запроса
  $request_body = json_decode($request->get_body(), true);

  $client = OpenAI::factory()->withApiKey($api_key)
    ->withHttpHeader('OpenAI-Beta', 'assistants=v2')
    ->make();

  if (!ThreadLimitService::can_add_message())
    ThreadLimitService::lock_thread();
  // Устанавливаем хедеры, тело запроса и параметры для потокового подключения

  // Выводим headers для клиентского сервиса, чтобы понимать, что происходит стриминг
  header('Content-Type: text/event-stream');
  header('Cache-Control: no-cache');
  header('Connection: keep-alive');

  $stream = $client->threads()->runs()->createStreamed(
    threadId: $thread_id,
    parameters: $request_body,
  );

  foreach ($stream as $response) {
    echo "data: " . json_encode(["event" => $response->event, "data" => $response->response]) . "\n\n"; // ThreadResponse | ThreadRunResponse | ThreadRunStepResponse | ThreadRunStepDeltaResponse | ThreadMessageResponse | ThreadMessageDeltaResponse
    flush();
  }
  echo "data: [DONE]\n\n";

  die();
}
