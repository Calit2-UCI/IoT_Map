<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['string'] = "/.*/";
$config['email'] = "/^([a-z\d-_\.]+)@([a-z\d-_]+)\.([a-z]+)([a-z\d-_\.]*)$/i";
$config['login'] = "/^.{5,20}$/";
$config['nickname'] = "/^[A-z0-9_\-]{5,15}$/";
$config['password'] = "/^.{6,20}$/i";
$config['price'] = "/^\d+\.?\d*$/";
$config['zip'] = "/.{5,8}/";
$config['unsigned_integer'] = "/^\d+$/";
$config['signed_integer'] = "/^[-]?\d+$/";
$config['unsigned_float'] = "/^\d+\.?\d*$/";
$config['signed_float'] = "/^[-]?\d+\.?\d*$/";
$config['url'] = "/^(http:\/\/|https:\/\/|)([^\.\/]+\.)*([a-zA-Z0-9])([a-zA-Z0-9-]*)\.([a-zA-Z]{2,4})(\/.*)?$/i";
$config['html_tags'] = "/^(?!(.*<\/?(A|ABBR|ACRONYM|ADDRESS|AREA|B|BASE|BASEFONT|BDO|BGSOUND|BIG|BLOCKQUOTE|BODY|BR|BUTTON|CAPTION|CENTER|CITE|CODE|COL|COLGROUP|DD|DEL|DFN|DIV|DL|DT|EM|EMBED|FIELDSET|FONT|FORM|FRAME|FRAMESET|H1|H2|H3|H4|H5|H6|HEAD|HR|HTML|I|IFRAME|IMG|INPUT|INS|KBD|LABEL|LEGEND|LI|LINK|MAP|MARQUEE|META|NOBR|NOEMBED|NOFRAMES|NOSCRIPT|OBJECT|OL|OPTGROUP|OPTION|P|PARAM|PRE|Q|SAMP|SCRIPT|SELECT|SMALL|SPAN|STRIKE|STRONG|STYLE|SUB|SUP|TABLE|TBODY|TD|TEXTAREA|TFOOT|TH|THEAD|TITLE|TR|TT|UL|VAR|WBR|XMP)\s{0,}.*>))/i";
$config['not_literal'] = '/[^\pL\pN\pM_]+/ui';