<?php
// TODO: Query results in array, double for loop

includeWithVariables(projectRoot() . '/templates/base.php', ['pageTitle' => 'Totaal']);

$connection = getConn();

// Queries {{{
// First: total per activity per user
$totalItems = $connection->query("
    SELECT
        m.naam AS medewerker,
        a.naam AS activiteit,
        SUM(u.minuten) AS totaal
    FROM
        urenregistratie AS u
    LEFT JOIN medewerker AS m
    ON
        (
            u.medewerker_id = m.medewerker_id
        )
    LEFT JOIN activiteit AS a
    ON
        (
            u.activiteit_id = a.activiteit_id
        )
    WHERE
        m.actief = 'ja'
    GROUP BY
        m.naam,
        a.naam
    ORDER BY
        3
    DESC;
");

// Second: total minutes per activity
$totalActs = $connection->query("
    SELECT
        a.naam AS activiteit,
        SUM(u.minuten) AS totaal
    FROM
        urenregistratie AS u
    LEFT JOIN activiteit AS a
    ON
        (
            u.activiteit_id = a.activiteit_id
        )
    WHERE
        a.actief = 'ja';
");

// Last: total minutes per user
$totalUsers = $connection->query("
    SELECT
        m.naam AS medewerker,
        SUM(u.minuten) AS totaal
    FROM
        urenregistratie AS u
    LEFT JOIN medewerker AS m
    ON
        (
            u.medewerker_id = m.medewerker_id
        )
    WHERE
        m.actief = 'ja';
");
// }}}

?>

<h1>Totaalaantallen</h1>
<table>
    <tr>
        <th>
            Medewerker
        </th>
        <th>
            Activiteit
        </th>
        <th>
            Totaal
        </th>
    </tr>
    <?php
    foreach ($totalItems as $item) {
        echo "<tr>";
        echo "<td>{$item["medewerker"]}</td>";
        echo "<td>{$item["activiteit"]}</td>";
        echo "<td>{$item["totaal"]}</td>";
        echo "</tr>";
    }
    ?>
</table>
<table>
    <tr>
        <th>
            Activiteit
        </th>
        <th>
            Totaal
        </th>
    </tr>
    <?php
    foreach ($totalActs as $item) {
        echo "<tr>";
        echo "<td>{$item["activiteit"]}</td>";
        echo "<td>{$item["totaal"]}</td>";
        echo "</tr>";
    }
    ?>
</table>
<table>
    <tr>
        <th>
            Medewerker
        </th>
        <th>
            Totaal
        </th>
    </tr>
    <?php
    foreach ($totalUsers as $item) {
        echo "<tr>";
        echo "<td>{$item["medewerker"]}</td>";
        echo "<td>{$item["totaal"]}</td>";
        echo "</tr>";
    }
    ?>
</table>
