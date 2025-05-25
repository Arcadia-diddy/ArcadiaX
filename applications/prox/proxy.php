<?php
// Get query or URL
$query = isset($_GET['q']) ? $_GET['q'] : null;
$url   = isset($_GET['url']) ? $_GET['url'] : null;

// Set user-agent to mimic a browser
$context = stream_context_create([
    "http" => [
        "user_agent" => "Mozilla/5.0"
    ]
]);

// If a URL is requested, proxy that site
if ($url) {
    $target = urldecode($url);
    $html = @file_get_contents($target, false, $context);
    echo $html ? $html : "Failed to load $target";
    exit;
}

// Otherwise, do a DuckDuckGo search
if ($query) {
    $searchUrl = "https://html.duckduckgo.com/html/?q=" . urlencode($query);
    $html = @file_get_contents($searchUrl, false, $context);

    if (!$html) {
        echo "Search failed.";
        exit;
    }

    // Rewrite all result links to go through the proxy
    $html = preg_replace_callback(
        '/<a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/i',
        function ($matches) {
            $href = htmlspecialchars($matches[1]);
            $text = $matches[2];
            if (strpos($href, 'http') === 0) {
                $proxied = 'proxy.php?url=' . urlencode($href);
                return "<a href=\"$proxied\">$text</a>";
            }
            return $matches[0];
        },
        $html
    );

    echo $html;
    exit;
}

// Default: show help
echo "Missing parameters.";
