<?php

use Nerbiz\PrivateStats\PrivateStats;
use Nerbiz\PrivateStats\Query\ReadQuery;

if (! isset($privateStats) || ! ($privateStats instanceof PrivateStats)) {
    return;
}

$lastMonthQuery = (new ReadQuery())
    ->addWhere('timestamp', strtotime('-1 month'), '>=')
    ->addWhere('timestamp', time(), '<=')
    ->setOrderBy('timestamp')
    // Dit moet ergens anders in, zodat het query object herbruikbaar is
    ->chunkByMinutes();
?>

<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.css">
<script src="//cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.js"></script>

<h1>
    Private Stats
</h1>

<div style="max-width: 800px;">
    <div class="ct-chart ct-major-tenth" id="private-stats-last-month"></div>
</div>

<script>
    var chartOptions = {
        low: 0,
        showArea: true,
    };

    new Chartist.Line('#private-stats-last-month', {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
        series: [
            [5, 2, 4, 2, 0],
        ],
    }, chartOptions);
</script>
