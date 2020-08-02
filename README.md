# subconverter-php
Utility to convert between various proxy subscription formats.
# Supported Types
SS/SSR/V2Ray to Clash Tag:clash<br>
SS/SSR/V2Ray to QuantumultX Tag:quanx
# Quick Usage
http://xxx.com/sub.php?url=config link(need urlencode);
# Filter Node
http://xxx.com/sub.php?preg=regex&url=config link(need urlencode);
# Others
subconverter-php use UA to convert config link,so you don't need to add tags to identify.If you need tags,you can like this:<br>
http://xxx.com/sub.php?target=clash&url=config link(need urlencode);
