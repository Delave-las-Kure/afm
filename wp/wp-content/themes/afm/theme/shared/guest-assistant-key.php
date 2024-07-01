<?
function get_guest_cookie_identifier()
{
  return isset($_COOKIE['assistant_guest_cookie']) ? $_COOKIE['assistant_guest_cookie'] : '';
}

function set_guest_cookie_identifier($val)
{
  setcookie('assistant_guest_cookie', $val, time() + (DAY_IN_SECONDS * 30), COOKIEPATH, COOKIE_DOMAIN);
}
