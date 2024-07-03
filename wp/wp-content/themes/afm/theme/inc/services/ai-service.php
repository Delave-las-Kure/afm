<?
class AiService
{

  static function create_thread(array $parameters = [])
  {
    return AiService::get_ai_assistant_client()->threads()->create($parameters);
  }

  static function create_message(string $threadId, array $parameters)
  {
    return AiService::get_ai_assistant_client()->threads()->messages()->create($threadId, $parameters);
  }

  static function get_thread_messages(string $threadId, array $parameters = [])
  {
    return AiService::get_ai_assistant_client()->threads()->messages()->list($threadId, [
      ...$parameters
    ]);
  }

  static function create_run(string $threadId, array $parameters)
  {
    return AiService::get_ai_assistant_client()->threads()->runs()->createStreamed(
      $threadId,
      $parameters,
    );
  }

  static function get_ai_assistant_client()
  {
    return OpenAI::factory()->withApiKey(get_field('api_key', 'option'))
      ->withHttpHeader('OpenAI-Beta', 'assistants=v2')->make();
  }
}
