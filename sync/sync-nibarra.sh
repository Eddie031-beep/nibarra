#!/usr/bin/env bash
cd /var/www/nibarra/sync/pending 2>/dev/null || exit 0
shopt -s nullglob
for f in *.json; do
  php -r '
    $p = json_decode(file_get_contents($argv[1]), true);
    require __DIR__ . "/../src/helpers/sync.php";
    $r = sync_exec($p["sql"], $p["params"]);
    if ($r["ok"]) unlink($argv[1]); else fwrite(STDERR, "retry-fail\n");
  ' "$f"
done
